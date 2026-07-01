<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VNPayPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Ký payload giống VNPayService::validateSignature (ksort + urlencode + hmac sha512).
     */
    private function sign(array $params): array
    {
        ksort($params);

        $hashData = '';
        $first = true;
        foreach ($params as $key => $value) {
            if (!$first) {
                $hashData .= '&';
            }
            $hashData .= urlencode($key) . '=' . urlencode((string) $value);
            $first = false;
        }

        $params['vnp_SecureHash'] = hash_hmac('sha512', $hashData, config('vnpay.hash_secret'));

        return $params;
    }

    private function makeVnpayOrder(User $user, int $bookQty, int $orderQty, float $unitPrice = 100000): array
    {
        $book = Book::factory()->create(['quantity' => $bookQty, 'price' => $unitPrice, 'status' => 'available']);

        $order = Order::create([
            'user_id' => $user->id,
            'subtotal' => $unitPrice * $orderQty,
            'total_amount' => $unitPrice * $orderQty,
            'shipping_name' => 'Nguyen Van A',
            'shipping_phone' => '0900000000',
            'shipping_address' => '123 Test',
            'status' => Order::STATUS_PENDING,
            'payment_method' => Order::PAYMENT_VNPAY,
            'payment_status' => Order::PAYMENT_STATUS_PENDING,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'book_id' => $book->id,
            'book_title' => $book->title,
            'quantity' => $orderQty,
            'price' => $unitPrice,
        ]);

        return [$order, $book];
    }

    private function callbackParams(Order $order, string $responseCode, string $txnStatus): array
    {
        return $this->sign([
            'vnp_TmnCode' => config('vnpay.tmn_code'),
            'vnp_Amount' => (int) round(((float) $order->total_amount) * 100),
            'vnp_TxnRef' => $order->id . '20260101000000',
            'vnp_TransactionNo' => '99999999',
            'vnp_BankCode' => 'NCB',
            'vnp_ResponseCode' => $responseCode,
            'vnp_TransactionStatus' => $txnStatus,
            'vnp_PayDate' => '20260101000500',
        ]);
    }

    /** @test */
    public function return_success_marks_paid_confirmed_and_deducts_stock(): void
    {
        $user = User::factory()->create();
        [$order, $book] = $this->makeVnpayOrder($user, bookQty: 10, orderQty: 3);

        $params = $this->callbackParams($order, '00', '00');
        $response = $this->get('/vnpay/return?' . http_build_query($params));

        $response->assertRedirect(route('orders.show', $order));
        $response->assertSessionHas('success');

        $order->refresh();
        $this->assertSame(Order::PAYMENT_STATUS_PAID, $order->payment_status);
        $this->assertSame(Order::STATUS_CONFIRMED, $order->status);
        $this->assertNotNull($order->paid_at);
        $this->assertSame('99999999', $order->payment_ref);
        $this->assertSame(7, $book->fresh()->quantity, 'Kho phải bị trừ khi thanh toán thành công');
    }

    /** @test */
    public function return_failed_keeps_pending_and_does_not_touch_stock(): void
    {
        $user = User::factory()->create();
        [$order, $book] = $this->makeVnpayOrder($user, bookQty: 10, orderQty: 3);

        $params = $this->callbackParams($order, '24', '02'); // khách huỷ
        $response = $this->get('/vnpay/return?' . http_build_query($params));

        $response->assertSessionHas('error');

        $order->refresh();
        $this->assertSame(Order::PAYMENT_STATUS_FAILED, $order->payment_status);
        $this->assertSame(Order::STATUS_PENDING, $order->status, 'Đơn thất bại phải giữ pending');
        $this->assertSame(10, $book->fresh()->quantity, 'Đơn VNPAY thất bại không được trừ kho');
    }

    /** @test */
    public function return_response00_but_txnstatus_not_00_stays_pending_without_success_message(): void
    {
        $user = User::factory()->create();
        [$order, $book] = $this->makeVnpayOrder($user, bookQty: 10, orderQty: 3);

        $params = $this->callbackParams($order, '00', '01'); // đang xử lý
        $response = $this->get('/vnpay/return?' . http_build_query($params));

        $response->assertSessionMissing('success');
        $response->assertSessionHas('info');

        $order->refresh();
        $this->assertSame(Order::PAYMENT_STATUS_PENDING, $order->payment_status);
        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertSame(10, $book->fresh()->quantity);
    }

    /** @test */
    public function return_with_wrong_amount_is_rejected(): void
    {
        $user = User::factory()->create();
        [$order, $book] = $this->makeVnpayOrder($user, bookQty: 10, orderQty: 3);

        $params = $this->callbackParams($order, '00', '00');
        $params['vnp_Amount'] = 1; // sửa số tiền -> chữ ký vẫn phải được ký lại mới hợp lệ
        $params = $this->sign(collect($params)->except('vnp_SecureHash')->all());

        $response = $this->get('/vnpay/return?' . http_build_query($params));

        $response->assertSessionHas('error');
        $order->refresh();
        $this->assertSame(Order::PAYMENT_STATUS_PENDING, $order->payment_status);
        $this->assertSame(10, $book->fresh()->quantity);
    }

    /** @test */
    public function ipn_success_then_duplicate_does_not_double_deduct(): void
    {
        $user = User::factory()->create();
        [$order, $book] = $this->makeVnpayOrder($user, bookQty: 10, orderQty: 3);

        $params = $this->callbackParams($order, '00', '00');

        $first = $this->get('/vnpay/ipn?' . http_build_query($params));
        $first->assertJson(['RspCode' => '00']);

        $order->refresh();
        $this->assertSame(Order::PAYMENT_STATUS_PAID, $order->payment_status);
        $this->assertSame(7, $book->fresh()->quantity);

        // IPN lặp lại (VNPAY có thể gọi nhiều lần) -> báo đã xử lý, không trừ kho lần nữa.
        $second = $this->get('/vnpay/ipn?' . http_build_query($params));
        $second->assertJson(['RspCode' => '02']);
        $this->assertSame(7, $book->fresh()->quantity, 'IPN lặp không được trừ kho lần hai');
    }

    /** @test */
    public function cancelling_unpaid_vnpay_order_does_not_restore_stock(): void
    {
        $user = User::factory()->create();
        // Đơn VNPAY chưa thanh toán -> kho CHƯA bị trừ (giữ nguyên 10).
        [$order, $book] = $this->makeVnpayOrder($user, bookQty: 10, orderQty: 3);

        $response = $this->actingAs($user)->post(route('orders.cancel', $order), ['reason' => 'đổi ý']);

        $response->assertSessionHas('success');
        $order->refresh();
        $this->assertSame(Order::STATUS_CANCELLED, $order->status);
        $this->assertSame(10, $book->fresh()->quantity, 'Không hoàn kho cho đơn chưa từng trừ kho');
    }

    /** @test */
    public function cod_checkout_deducts_stock_but_vnpay_checkout_does_not(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->create(['quantity' => 10, 'price' => 100000, 'status' => 'available']);

        $shipping = [
            'shipping_name' => 'Nguyen Van A',
            'shipping_phone' => '0900000000',
            'shipping_address' => '123 Test',
        ];

        // COD -> trừ kho ngay.
        $user->cart()->create(['book_id' => $book->id, 'quantity' => 2]);
        $this->actingAs($user)->post(route('orders.checkout'), $shipping + ['payment_method' => 'cod']);
        $this->assertSame(8, $book->fresh()->quantity, 'COD phải trừ kho lúc đặt');

        // VNPAY -> KHÔNG trừ kho lúc đặt.
        $user->cart()->create(['book_id' => $book->id, 'quantity' => 2]);
        $this->actingAs($user)->post(route('orders.checkout'), $shipping + ['payment_method' => 'vnpay']);
        $this->assertSame(8, $book->fresh()->quantity, 'VNPAY không được trừ kho lúc đặt');

        $this->assertDatabaseHas('orders', ['payment_method' => 'vnpay', 'payment_status' => 'pending']);
    }
}
