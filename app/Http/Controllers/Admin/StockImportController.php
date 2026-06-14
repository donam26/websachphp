<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\StockImport;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockImportController extends Controller
{
    public function index(Request $request)
    {
        $query = StockImport::with('supplier')->withCount('items');

        if ($search = trim($request->input('search', ''))) {
            $query->where('code', 'like', "%{$search}%");
        }

        if ($supplierId = $request->input('supplier_id')) {
            $query->where('supplier_id', $supplierId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('imported_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('imported_at', '<=', $to);
        }

        $imports = $query->latest('imported_at')->latest('id')
            ->paginate(15)->appends($request->query());

        $suppliers = Supplier::orderBy('name')->get();

        return view('admin.stock-imports.index', compact('imports', 'suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::active()->orderBy('name')->get();
        $books = Book::orderBy('title')->get(['id', 'title', 'quantity', 'price']);

        return view('admin.stock-imports.create', compact('suppliers', 'books'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'imported_at' => 'nullable|date',
            'note' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.book_id' => 'required|exists:books,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.import_price' => 'required|numeric|min:0',
        ], [
            'items.required' => 'Vui lòng thêm ít nhất một sách vào phiếu nhập',
            'items.min' => 'Vui lòng thêm ít nhất một sách vào phiếu nhập',
        ]);

        DB::transaction(function () use ($validated) {
            $import = StockImport::create([
                'supplier_id' => $validated['supplier_id'] ?? null,
                'user_id' => auth()->id(),
                'note' => $validated['note'] ?? null,
                'status' => StockImport::STATUS_COMPLETED,
                'imported_at' => $validated['imported_at'] ?? now(),
                'total_amount' => 0,
            ]);

            $total = 0;
            foreach ($validated['items'] as $row) {
                $book = Book::lockForUpdate()->find($row['book_id']);
                if (!$book) {
                    continue;
                }

                $quantity = (int) $row['quantity'];
                $price = (float) $row['import_price'];

                $import->items()->create([
                    'book_id' => $book->id,
                    'book_title' => $book->title,
                    'quantity' => $quantity,
                    'import_price' => $price,
                ]);

                $book->increment('quantity', $quantity);
                $total += $quantity * $price;
            }

            $import->update(['total_amount' => $total]);
        });

        return redirect()->route('admin.stock-imports.index')
            ->with('success', 'Tạo phiếu nhập hàng thành công, đã cập nhật tồn kho');
    }

    public function show(StockImport $stockImport)
    {
        $stockImport->load(['supplier', 'user', 'items.book']);

        return view('admin.stock-imports.show', compact('stockImport'));
    }
}
