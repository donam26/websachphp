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

        $topBooks = Book::leftJoin('order_items', 'books.id', '=', 'order_items.book_id')
            ->leftJoin('orders', function ($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', '=', Order::STATUS_COMPLETED);
            })
            ->select('books.*')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->groupBy('books.id')
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
