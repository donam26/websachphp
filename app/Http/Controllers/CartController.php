<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\CartItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cart()->with(['book.category', 'book.authors'])->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);
        $total = $subtotal;

        $paymentMethods = PaymentMethod::active()->orderBy('id')->get();

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'total',
            'paymentMethods'
        ));
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        try {
            $book = DB::transaction(function () use ($validated) {
                $book = Book::lockForUpdate()->findOrFail($validated['book_id']);

                if ($book->status !== 'available' || $book->quantity <= 0) {
                    throw new \RuntimeException('Sản phẩm hiện không có sẵn');
                }

                $existing = auth()->user()->cart()
                    ->where('book_id', $book->id)
                    ->lockForUpdate()
                    ->first();

                $desiredQuantity = ($existing?->quantity ?? 0) + (int) $validated['quantity'];

                if ($desiredQuantity > $book->quantity) {
                    throw new \RuntimeException("Sản phẩm chỉ còn {$book->quantity} cuốn trong kho");
                }

                if ($existing) {
                    $existing->update(['quantity' => $desiredQuantity]);
                } else {
                    auth()->user()->cart()->create([
                        'book_id' => $book->id,
                        'quantity' => $validated['quantity'],
                    ]);
                }

                return $book;
            });
        } catch (\RuntimeException $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }
            return back()->with('error', $e->getMessage());
        }

        $message = 'Đã thêm "' . $book->title . '" vào giỏ hàng';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cartCount' => auth()->user()->cart()->sum('quantity'),
            ]);
        }

        if ($request->boolean('buy_now')) {
            return redirect()->route('cart.index');
        }

        return back()->with('success', $message);
    }

    public function update(Request $request, CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== auth()->id(), 403);

        $action = $request->input('action');
        $quantity = (int) $request->input('quantity', $cartItem->quantity);

        if ($action === 'inc') {
            $quantity = $cartItem->quantity + 1;
        } elseif ($action === 'dec') {
            $quantity = $cartItem->quantity - 1;
        }

        $quantity = max(1, min(99, $quantity));

        $book = $cartItem->book;
        if (!$book) {
            $cartItem->delete();
            return back()->with('error', 'Sản phẩm không tồn tại, đã xoá khỏi giỏ');
        }

        if ($book->quantity < $quantity) {
            return back()->with('error', "Sản phẩm chỉ còn {$book->quantity} cuốn trong kho");
        }

        $cartItem->update(['quantity' => $quantity]);

        return back()->with('success', 'Đã cập nhật giỏ hàng');
    }

    public function remove(CartItem $cartItem)
    {
        abort_if($cartItem->user_id !== auth()->id(), 403);

        $cartItem->delete();

        return back()->with('success', 'Đã xoá sản phẩm khỏi giỏ hàng');
    }

    public function clear()
    {
        auth()->user()->cart()->delete();

        return back()->with('success', 'Đã xoá toàn bộ giỏ hàng');
    }
}
