<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();
        $cartItems = $user->cart()->with('book')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Giỏ hàng của bạn đang trống');
        }

        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => ['required', 'string', 'regex:/^[0-9]{9,11}$/'],
            'shipping_address' => 'required|string|max:500',
            'note' => 'nullable|string|max:500',
            'payment_method' => ['required', Rule::exists('payment_methods', 'code')->where('is_active', true)],
        ], [
            'shipping_phone.regex' => 'Số điện thoại không hợp lệ (9-11 chữ số)',
        ]);

        try {
            $order = DB::transaction(function () use ($user, $cartItems, $validated) {
                $subtotal = 0;
                $itemsPayload = [];

                foreach ($cartItems as $item) {
                    $book = Book::lockForUpdate()->find($item->book_id);

                    if (!$book) {
                        throw new \RuntimeException("Sản phẩm trong giỏ không còn tồn tại");
                    }

                    if ($book->status !== 'available' || $book->quantity < $item->quantity) {
                        throw new \RuntimeException("Sản phẩm \"{$book->title}\" không đủ tồn kho");
                    }

                    $subtotal += (float) $book->price * $item->quantity;

                    $itemsPayload[] = [
                        'book' => $book,
                        'quantity' => $item->quantity,
                        'price' => (float) $book->price,
                    ];
                }

                $totalAmount = $subtotal;

                $order = Order::create([
                    'user_id' => $user->id,
                    'subtotal' => $subtotal,
                    'total_amount' => $totalAmount,
                    'shipping_name' => $validated['shipping_name'],
                    'shipping_phone' => $validated['shipping_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'note' => $validated['note'] ?? null,
                    'status' => Order::STATUS_PENDING,
                    'payment_method' => $validated['payment_method'],
                    'payment_method_id' => PaymentMethod::where('code', $validated['payment_method'])->value('id'),
                    'payment_status' => Order::PAYMENT_STATUS_PENDING,
                ]);

                foreach ($itemsPayload as $row) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'book_id' => $row['book']->id,
                        'book_title' => $row['book']->title,
                        'quantity' => $row['quantity'],
                        'price' => $row['price'],
                    ]);

                    $row['book']->decrement('quantity', $row['quantity']);
                }

                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => Order::STATUS_PENDING,
                    'note' => 'Khách đặt hàng (' . strtoupper($validated['payment_method']) . ')',
                ]);

                $user->cart()->delete();

                return $order;
            });
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            Log::error('Lỗi đặt hàng', ['message' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Đã có lỗi xảy ra, vui lòng thử lại');
        }

        if ($order->payment_method === Order::PAYMENT_VNPAY) {
            return app(VNPayController::class)->createPayment($order);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Đặt hàng thành công! Mã đơn hàng: ' . $order->code);
    }
}
