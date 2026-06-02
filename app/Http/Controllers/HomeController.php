<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->orderBy('name')->take(12)->get();

        $newBooks = Book::with(['category', 'authors'])->withAvg('reviews', 'rating')->withCount('reviews')
            ->where('quantity', '>', 0)
            ->latest()
            ->take(10)
            ->get();

        // Best sellers ranked by quantity sold in COMPLETED orders.
        // All aggregates (avg rating, review count, total sold) are correlated
        // subqueries — no JOIN, no GROUP BY — so the query is ONLY_FULL_GROUP_BY-safe.
        $bestSellers = Book::with(['category', 'authors'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->withSum(['orderItems as total_sold' => function ($query) {
                $query->whereHas('order', function ($order) {
                    $order->where('status', Order::STATUS_COMPLETED);
                });
            }], 'quantity')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        return view('home.index', compact('categories', 'newBooks', 'bestSellers'));
    }
}
