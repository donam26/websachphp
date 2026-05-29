<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderItem;
use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $users = User::customers()->get();
        if ($users->isEmpty()) {
            return;
        }

        $books = Book::all();
        if ($books->isEmpty()) {
            return;
        }

        $paymentMethods = PaymentMethod::pluck('id', 'code');
        $employees = User::where('role', User::ROLE_ADMIN)->pluck('id');

        $statuses = [
            Order::STATUS_PENDING,
            Order::STATUS_CONFIRMED,
            Order::STATUS_SHIPPING,
            Order::STATUS_COMPLETED,
            Order::STATUS_CANCELLED,
        ];

        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $status = $statuses[array_rand($statuses)];
            $paymentMethod = (rand(0, 1) === 0) ? Order::PAYMENT_COD : Order::PAYMENT_VNPAY;
            $orderBooks = $books->random(rand(1, 4));

            $subtotal = 0;
            $items = [];
            foreach ($orderBooks as $book) {
                $qty = rand(1, 3);
                $subtotal += $book->price * $qty;
                $items[] = [
                    'book' => $book,
                    'quantity' => $qty,
                ];
            }

            $shippingFee = $subtotal >= Order::FREESHIP_THRESHOLD ? 0 : Order::SHIPPING_FEE;
            $totalAmount = $subtotal + $shippingFee;

            $paymentStatus = Order::PAYMENT_STATUS_PENDING;
            $paidAt = null;
            $cancelledAt = null;

            if ($status === Order::STATUS_COMPLETED) {
                $paymentStatus = Order::PAYMENT_STATUS_PAID;
                $paidAt = now()->subDays(rand(1, 30));
            } elseif ($status === Order::STATUS_CANCELLED) {
                $cancelledAt = now()->subDays(rand(1, 30));
                if ($paymentMethod === Order::PAYMENT_VNPAY) {
                    $paymentStatus = Order::PAYMENT_STATUS_REFUNDED;
                }
            } elseif ($status === Order::STATUS_SHIPPING && $paymentMethod === Order::PAYMENT_VNPAY) {
                $paymentStatus = Order::PAYMENT_STATUS_PAID;
                $paidAt = now()->subDays(rand(1, 10));
            }

            $order = Order::create([
                'user_id' => $user->id,
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => 0,
                'total_amount' => $totalAmount,
                'shipping_name' => $user->full_name,
                'shipping_phone' => $user->phone_number,
                'shipping_address' => $user->address,
                'note' => null,
                'status' => $status,
                'payment_method' => $paymentMethod,
                'payment_method_id' => $paymentMethods[$paymentMethod] ?? null,
                'employee_id' => $status === Order::STATUS_PENDING ? null : ($employees->isNotEmpty() ? $employees->random() : null),
                'payment_status' => $paymentStatus,
                'paid_at' => $paidAt,
                'cancelled_at' => $cancelledAt,
            ]);

            foreach ($items as $row) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'book_id' => $row['book']->id,
                    'book_title' => $row['book']->title,
                    'quantity' => $row['quantity'],
                    'price' => $row['book']->price,
                ]);
            }

            OrderHistory::create([
                'order_id' => $order->id,
                'status' => $status,
                'note' => 'Khởi tạo dữ liệu mẫu',
            ]);
        }
    }
}
