<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /** Statuses that count toward revenue. */
    private const REVENUE_STATUSES = [
        Order::STATUS_COMPLETED,
        Order::STATUS_SHIPPING,
        Order::STATUS_CONFIRMED,
    ];

    private const MIN_YEAR = 2020;

    public function index(Request $request)
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalRevenue = Order::whereIn('status', self::REVENUE_STATUSES)->sum('total_amount');

        $monthRevenue = Order::whereIn('status', self::REVENUE_STATUSES)
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        $todayOrders = Order::whereDate('created_at', $today)->count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();

        $totalBooks = Book::count();
        $outOfStockBooks = Book::where('quantity', 0)->count();
        $lowStockBooks = Book::with('category')->where('quantity', '>', 0)->where('quantity', '<', 5)->take(5)->get();

        $totalCustomers = User::customers()->count();

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        // Top sellers theo kỳ chọn (tuần / tháng / tất cả) — chỉ tính đơn hoàn thành.
        $topPeriod = in_array($request->input('top_period'), ['week', 'month', 'all'], true)
            ? $request->input('top_period')
            : 'all';
        $topBooks = $this->topBooks($topPeriod);

        // Doanh thu theo ngày trong khoảng được chọn (mặc định 14 ngày gần nhất).
        [$revFrom, $revTo] = $this->resolveRevenueRange($request);
        $revenueByDay = $this->revenueByDay($revFrom, $revTo);

        // Doanh thu 12 tháng của một năm.
        $year = $this->resolveYear($request);
        $revenueByMonth = $this->revenueByMonth($year);
        $yearOptions = range(Carbon::now()->year, self::MIN_YEAR);

        $statusDistribution = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'totalRevenue',
            'monthRevenue',
            'todayOrders',
            'pendingOrders',
            'totalBooks',
            'outOfStockBooks',
            'lowStockBooks',
            'totalCustomers',
            'recentOrders',
            'topBooks',
            'topPeriod',
            'revenueByDay',
            'revFrom',
            'revTo',
            'revenueByMonth',
            'year',
            'yearOptions',
            'statusDistribution',
            'startOfMonth',
            'endOfMonth'
        ));
    }

    /**
     * Top 5 sách bán chạy — tổng số lượng từ đơn ĐÃ HOÀN THÀNH trong kỳ.
     * withSum dùng correlated subquery (không GROUP BY ở query ngoài) nên
     * an toàn với ONLY_FULL_GROUP_BY và chạy được trên mọi engine.
     */
    private function topBooks(string $period)
    {
        return Book::withSum(['orderItems as total_sold' => function ($query) use ($period) {
                $query->whereHas('order', function ($order) use ($period) {
                    $order->where('status', Order::STATUS_COMPLETED);
                    if ($period === 'week') {
                        $order->where('created_at', '>=', Carbon::now()->subDays(7));
                    } elseif ($period === 'month') {
                        $order->where('created_at', '>=', Carbon::now()->subDays(30));
                    }
                });
            }], 'quantity')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
    }

    /**
     * Chuẩn hoá khoảng ngày cho biểu đồ doanh thu theo ngày.
     * Mặc định: 14 ngày gần nhất. Giới hạn tối đa 366 ngày để vòng lặp theo ngày luôn bị chặn.
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    private function resolveRevenueRange(Request $request): array
    {
        $toDate = $this->parseDate($request->input('rev_to'), Carbon::today())->endOfDay();
        $fromDate = $this->parseDate($request->input('rev_from'), Carbon::today()->subDays(13))->startOfDay();

        if ($fromDate->gt($toDate)) {
            [$fromDate, $toDate] = [$toDate->copy()->startOfDay(), $fromDate->copy()->endOfDay()];
        }

        if ($fromDate->diffInDays($toDate) > 366) {
            $fromDate = $toDate->copy()->subDays(366)->startOfDay();
        }

        return [$fromDate, $toDate];
    }

    private function parseDate(?string $value, Carbon $default): Carbon
    {
        if (!$value) {
            return $default->copy();
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return $default->copy();
        }
    }

    private function resolveYear(Request $request): int
    {
        $year = (int) $request->input('year', Carbon::now()->year);

        if ($year < self::MIN_YEAR || $year > Carbon::now()->year) {
            return Carbon::now()->year;
        }

        return $year;
    }

    private function revenueByDay(Carbon $from, Carbon $to): array
    {
        $raw = Order::select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total_amount) as total'))
            ->whereIn('status', self::REVENUE_STATUSES)
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('d')
            ->pluck('total', 'd');

        $labels = [];
        $values = [];
        $cursor = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();

        while ($cursor->lte($end)) {
            $labels[] = $cursor->format('d/m');
            $values[] = (float) ($raw[$cursor->toDateString()] ?? 0);
            $cursor->addDay();
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function revenueByMonth(int $year): array
    {
        $raw = Order::select(DB::raw('MONTH(created_at) as m'), DB::raw('SUM(total_amount) as total'))
            ->whereIn('status', self::REVENUE_STATUSES)
            ->whereYear('created_at', $year)
            ->groupBy('m')
            ->pluck('total', 'm');

        $labels = [];
        $values = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = 'Th' . $m;
            $values[] = (float) ($raw[$m] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
