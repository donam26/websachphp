<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->orderBy('name')->take(12)->get();

        $newBooks = Book::with('category')
            ->where('quantity', '>', 0)
            ->latest()
            ->take(10)
            ->get();

        $bestSellers = Book::with('category')
            ->leftJoin('order_items', 'books.id', '=', 'order_items.book_id')
            ->select('books.*')
            ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
            ->groupBy('books.id')
            ->orderByDesc('total_sold')
            ->take(10)
            ->get();

        $discountBooks = Book::with('category')
            ->where('quantity', '>', 0)
            ->inRandomOrder()
            ->take(10)
            ->get();

        return view('home.index', compact('categories', 'newBooks', 'bestSellers', 'discountBooks'));
    }
}
