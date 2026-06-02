<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();

        $totalRevenue = Order::whereIn('status', [Order::STATUS_COMPLETED, Order::STATUS_SHIPPING, Order::STATUS_CONFIRMED])
            ->sum('total_amount');

        $monthRevenue = Order::whereIn('status', [Order::STATUS_COMPLETED, Order::STATUS_SHIPPING, Order::STATUS_CONFIRMED])
            ->where('created_at', '>=', $startOfMonth)
            ->sum('total_amount');

        $todayOrders = Order::whereDate('created_at', $today)->count();
        $pendingOrders = Order::where('status', Order::STATUS_PENDING)->count();

        $totalBooks = Book::count();
        $outOfStockBooks = Book::where('quantity', 0)->count();
        $lowStockBooks = Book::with('category')->where('quantity', '>', 0)->where('quantity', '<', 5)->take(5)->get();

        $totalCustomers = User::customers()->count();

        $recentOrders = Order::with('user')->latest()->take(8)->get();

        // Top sellers = total quantity from COMPLETED orders only.
        // withSum builds a correlated subquery (no GROUP BY on the outer query),
        // so it is ONLY_FULL_GROUP_BY-safe and portable across DB engines.
        $topBooks = Book::withSum(['orderItems as total_sold' => function ($query) {
                $query->whereHas('order', function ($order) {
                    $order->where('status', Order::STATUS_COMPLETED);
                });
            }], 'quantity')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $revenueByDay = $this->revenueChartData();
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
            'revenueByDay',
            'statusDistribution'
        ));
    }

    private function revenueChartData(): array
    {
        $labels = [];
        $values = [];
        $start = Carbon::now()->subDays(13);

        $raw = Order::select(DB::raw('DATE(created_at) as d'), DB::raw('SUM(total_amount) as total'))
            ->whereIn('status', [Order::STATUS_COMPLETED, Order::STATUS_SHIPPING, Order::STATUS_CONFIRMED])
            ->where('created_at', '>=', $start)
            ->groupBy('d')
            ->pluck('total', 'd');

        for ($i = 0; $i < 14; $i++) {
            $date = $start->copy()->addDays($i);
            $labels[] = $date->format('d/m');
            $values[] = (float) ($raw[$date->toDateString()] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
