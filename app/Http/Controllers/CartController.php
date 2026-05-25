<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cart()->with('book.category')->get();

        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);

        $discountAmount = session('applied_discount.amount', 0);
        $discountCode = session('applied_discount.code');

        $shippingFee = $subtotal >= Order::FREESHIP_THRESHOLD || $subtotal === 0 ? 0 : Order::SHIPPING_FEE;
        $total = max(0, $subtotal + $shippingFee - $discountAmount);

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'discountAmount',
            'discountCode',
            'shippingFee',
            'total'
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
            return back()->with('error', $e->getMessage());
        }

        if ($request->boolean('buy_now')) {
            return redirect()->route('cart.index');
        }

        return back()->with('success', 'Đã thêm "' . $book->title . '" vào giỏ hàng');
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
        session()->forget('applied_discount');

        return back()->with('success', 'Đã xoá toàn bộ giỏ hàng');
    }
}
