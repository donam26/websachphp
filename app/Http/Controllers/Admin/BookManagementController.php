<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%");
            });
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        if ($stock = $request->input('stock')) {
            if ($stock === 'out') {
                $query->where('quantity', 0);
            } elseif ($stock === 'low') {
                $query->where('quantity', '>', 0)->where('quantity', '<', 5);
            } elseif ($stock === 'in') {
                $query->where('quantity', '>=', 5);
            }
        }

        $books = $query->latest()->paginate(10)->appends($request->query());
        $categories = Category::orderBy('name')->get();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0|gte:price',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,unavailable',
        ], [
            'compare_price.gte' => 'Giá gốc phải lớn hơn hoặc bằng giá bán',
        ]);

        $validated['image'] = $this->storeImage($request->file('image'));

        Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Thêm sách mới thành công');
    }

    public function edit(Book $book)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0|gte:price',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,unavailable',
        ], [
            'compare_price.gte' => 'Giá gốc phải lớn hơn hoặc bằng giá bán',
        ]);

        if ($request->hasFile('image')) {
            if ($book->image) {
                Storage::disk('public')->delete('books/' . $book->image);
            }
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Cập nhật sách thành công');
    }

    public function destroy(Book $book)
    {
        if ($book->orderItems()->exists()) {
            return back()->with('error', 'Không thể xoá sách đã có trong đơn hàng. Đặt trạng thái "Ngừng kinh doanh" thay thế.');
        }

        if ($book->image) {
            Storage::disk('public')->delete('books/' . $book->image);
        }

        $book->delete();

        return back()->with('success', 'Xoá sách thành công');
    }

    private function storeImage($file): string
    {
        $filename = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('books', $filename, 'public');
        return $filename;
    }
}
