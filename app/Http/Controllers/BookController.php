<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query()->with(['category', 'authors']);
        $currentCategory = null;

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('authors', function ($qa) use ($search) {
                      $qa->where('name', 'like', "%{$search}%");
                  })
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($categorySlug = $request->input('category')) {
            $currentCategory = Category::where('slug', $categorySlug)->first();
            if ($currentCategory) {
                $query->where('category_id', $currentCategory->id);
            }
        }

        // Price filters
        if ($request->filled('price_from')) {
            $query->where('price', '>=', (int) $request->input('price_from'));
        }
        if ($request->filled('price_to')) {
            $query->where('price', '<=', (int) $request->input('price_to'));
        }

        // Sorting
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'best_seller':
                // Correlated subquery (no JOIN/GROUP BY) → ONLY_FULL_GROUP_BY-safe.
                $query->withSum(['orderItems as total_sold' => function ($q) {
                        $q->whereHas('order', function ($order) {
                            $order->where('status', Order::STATUS_COMPLETED);
                        });
                    }], 'quantity')
                    ->orderByDesc('total_sold');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Review aggregates for the rating display (correlated subqueries).
        $query->withAvg('reviews', 'rating')->withCount('reviews');

        $books = $query->paginate(12)->appends($request->query());
        $categories = Category::orderBy('name')->get();

        return view('books.index', compact('books', 'categories', 'currentCategory'));
    }

    public function show(Book $book)
    {
        $book->load(['category', 'authors', 'reviews.user']);

        $userReview = auth()->check()
            ? $book->reviews->firstWhere('user_id', auth()->id())
            : null;

        $relatedBooks = Book::with(['category', 'authors'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(5)
            ->get();

        return view('books.show', compact('book', 'relatedBooks', 'userReview'));
    }

    public function category($slug)
    {
        return $this->index(request()->merge(['category' => $slug]));
    }
}
