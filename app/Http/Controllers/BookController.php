<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        // Tìm kiếm theo tên hoặc thương hiệu
        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        // Lọc theo danh mục
        if ($category = $request->input('category')) {
            $query->whereHas('category', function($q) use ($category) {
                $q->where('slug', $category);
            });
        }

        // Lọc theo giới tính
        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        // Lọc theo size
        if ($size = $request->input('size')) {
            $query->where('sizes', 'like', "%{$size}%");
        }

        // Lọc theo khoảng giá
        if ($request->input('price_from')) {
            $query->where('price', '>=', $request->input('price_from'));
        }
        if ($request->input('price_to')) {
            $query->where('price', '<=', $request->input('price_to'));
        }

        // Sắp xếp
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
            default:
                $query->latest();
                break;
        }

        $books = $query->with('category')->paginate(12);
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        // Lấy sản phẩm liên quan cùng danh mục
        $relatedBooks = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $books = Book::where('category_id', $category->id)->paginate(12);
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }
}
