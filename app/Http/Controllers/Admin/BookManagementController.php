<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\Order;
use App\Models\StockImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with(['category', 'authors']);

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('authors', function ($qa) use ($search) {
                      $qa->where('name', 'like', "%{$search}%");
                  });
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

    public function show(Book $book)
    {
        $book->load(['category', 'authors']);

        $completedItems = $book->orderItems()
            ->whereHas('order', fn ($q) => $q->where('status', Order::STATUS_COMPLETED));

        $soldQuantity = (int) (clone $completedItems)->sum('quantity');
        $revenue = (float) (clone $completedItems)->sum(DB::raw('price * quantity'));

        $recentReviews = $book->reviews()->with('user')->take(5)->get();

        $importItems = $book->stockImportItems()
            ->with('stockImport.supplier')
            ->whereHas('stockImport', fn ($q) => $q->where('status', StockImport::STATUS_COMPLETED))
            ->latest()
            ->take(10)
            ->get();

        $totalImported = (int) $book->stockImportItems()
            ->whereHas('stockImport', fn ($q) => $q->where('status', StockImport::STATUS_COMPLETED))
            ->sum('quantity');

        return view('admin.books.show', compact(
            'book',
            'soldQuantity',
            'revenue',
            'recentReviews',
            'importItems',
            'totalImported'
        ));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        return view('admin.books.create', compact('categories', 'authors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_ids' => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
            'isbn' => 'nullable|string|max:30|unique:books,isbn',
            'publish_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,unavailable',
        ], [
            'author_ids.required' => 'Vui lòng chọn ít nhất một tác giả',
        ]);

        $authorIds = $validated['author_ids'];
        unset($validated['author_ids']);

        $validated['image'] = $this->storeImage($request->file('image'));
        $validated['author'] = $this->authorMirror($authorIds);

        $book = Book::create($validated);
        $book->authors()->sync($authorIds);

        return redirect()->route('admin.books.index')
            ->with('success', 'Thêm sách mới thành công');
    }

    public function edit(Book $book)
    {
        $book->load('authors');
        $categories = Category::orderBy('name')->get();
        $authors = Author::orderBy('name')->get();
        return view('admin.books.edit', compact('book', 'categories', 'authors'));
    }

    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author_ids' => 'required|array|min:1',
            'author_ids.*' => 'exists:authors,id',
            'isbn' => 'nullable|string|max:30|unique:books,isbn,' . $book->id,
            'publish_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'status' => 'required|in:available,unavailable',
        ], [
            'author_ids.required' => 'Vui lòng chọn ít nhất một tác giả',
        ]);

        $authorIds = $validated['author_ids'];
        unset($validated['author_ids']);

        if ($request->hasFile('image')) {
            if ($book->image) {
                Storage::disk('public')->delete('books/' . $book->image);
            }
            $validated['image'] = $this->storeImage($request->file('image'));
        }

        $validated['author'] = $this->authorMirror($authorIds);

        $book->update($validated);
        $book->authors()->sync($authorIds);

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

    /**
     * Build the denormalised books.author mirror string from selected authors.
     */
    private function authorMirror(array $authorIds): string
    {
        return Author::whereIn('id', $authorIds)->orderBy('name')->pluck('name')->implode(', ');
    }

    private function storeImage($file): string
    {
        $filename = Str::random(20) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('books', $filename, 'public');
        return $filename;
    }
}
