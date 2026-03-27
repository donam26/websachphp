<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($category_id = $request->input('category_id')) {
            $query->where('category_id', $category_id);
        }

        if ($gender = $request->input('gender')) {
            $query->where('gender', $gender);
        }

        $books = $query->latest()->paginate(10);
        $categories = Category::all();
        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,unavailable',
            'sizes' => 'nullable|string',
            'colors' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'gender' => 'required|in:nam,nu,unisex'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/books', $filename);
            $validated['image'] = $filename;
        }

        // Lưu brand vào cả field author để tương thích
        $validated['author'] = $validated['brand'];

        Book::create($validated);

        return redirect()->route('admin.books.index')
                        ->with('success', 'Thêm sản phẩm mới thành công');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,unavailable',
            'sizes' => 'nullable|string',
            'colors' => 'nullable|string',
            'material' => 'nullable|string|max:255',
            'gender' => 'required|in:nam,nu,unisex'
        ]);

        if ($request->hasFile('image')) {
            if ($book->image) {
                Storage::delete('public/books/' . $book->image);
            }

            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/books', $filename);
            $validated['image'] = $filename;
        }

        $validated['author'] = $validated['brand'];

        $book->update($validated);

        return redirect()->route('admin.books.index')
                        ->with('success', 'Cập nhật sản phẩm thành công');
    }

    public function destroy(Book $book)
    {
        if ($book->image) {
            Storage::delete('public/books/' . $book->image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')
                        ->with('success', 'Xóa sản phẩm thành công');
    }
}
