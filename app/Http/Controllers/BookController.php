<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                $query->leftJoin('order_items', 'books.id', '=', 'order_items.book_id')
                    ->select('books.*')
                    ->selectRaw('COALESCE(SUM(order_items.quantity), 0) as total_sold')
                    ->groupBy('books.id')
                    ->orderByDesc('total_sold');
                break;
            case 'discount':
                $query->where('quantity', '>', 0)->inRandomOrder();
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        // Apply review aggregates AFTER the switch so the best_seller branch's
        // select('books.*') doesn't clobber the withCount/withAvg subselects.
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
