<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderHistory;
use App\Services\VNPayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VNPayController extends Controller
{
    public function __construct(protected VNPayService $vnpay)
    {
    }

    /**
     * Khởi tạo giao dịch và redirect sang cổng VNPAY.
     */
    public function createPayment(Order $order)
    {
        // Chỉ chủ đơn mới được thanh toán.
        if (auth()->id() !== $order->user_id) {
            abort(403);
        }

        if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Đơn hàng này đã được thanh toán.');
        }

        if ($order->status === Order::STATUS_CANCELLED) {
            return redirect()->route('orders.show', $order)
                ->with('error', 'Đơn hàng đã huỷ, không thể thanh toán.');
        }

        $paymentUrl = $this->vnpay->createPaymentUrl($order);

        Log::info('VNPAY create payment', [
            'order_id' => $order->id,
            'code'     => $order->code,
            'amount'   => $order->total_amount,
        ]);

        return redirect()->away($paymentUrl);
    }

    /**
     * Return URL: VNPAY redirect khách quay về sau khi thanh toán.
     * Hiển thị thông báo cho khách. KHÔNG dùng để cập nhật trạng thái cuối cùng
     * (việc đó để IPN làm), nhưng vẫn cập nhật để UX nhanh.
     */
    public function return(Request $request)
    {
        if (!$this->vnpay->validateSignature($request)) {
            Log::warning('VNPAY return: invalid signature', $request->all());
            return redirect()->route('orders.index')
                ->with('error', 'Xác thực thanh toán thất bại.');
        }

        $orderId = $this->vnpay->extractOrderId((string) $request->input('vnp_TxnRef'));
        $order   = $orderId ? Order::find($orderId) : null;

        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Không tìm thấy đơn hàng.');
        }

        // Số tiền phải khớp với đơn hàng (giống IPN), tránh sửa vnp_Amount trên URL.
        $expectedAmount = (int) round(((float) $order->total_amount) * 100);
        if ((int) $request->input('vnp_Amount') !== $expectedAmount) {
            Log::warning('VNPAY return: amount mismatch', [
                'order_id' => $order->id,
                'expected' => $expectedAmount,
                'received' => (int) $request->input('vnp_Amount'),
            ]);

            return redirect()->route('orders.show', $order)
                ->with('error', 'Số tiền thanh toán không khớp với đơn hàng.');
        }

        $responseCode = (string) $request->input('vnp_ResponseCode');
        $result = $this->applyPaymentResult($order, $request, $responseCode);

        // Thông báo dựa trên KẾT QUẢ thực tế đã ghi nhận, không chỉ dựa vào
        // vnp_ResponseCode, để tránh báo "đã xác nhận" trong khi đơn vẫn pending.
        if ($result === 'paid') {
            return redirect()->route('orders.show', $order)
                ->with('success', 'Thanh toán thành công! Đơn hàng đã được xác nhận.');
        }

        if ($result === 'pending') {
            return redirect()->route('orders.show', $order)
                ->with('info', 'Giao dịch đang được xử lý. Trạng thái đơn hàng sẽ được cập nhật khi có kết quả cuối cùng.');
        }

        return redirect()->route('orders.show', $order)
            ->with('error', 'Thanh toán không thành công: ' . $this->vnpay->getResponseMessage($responseCode));
    }

    /**
     * IPN URL: VNPAY gọi server-to-server để báo kết quả giao dịch.
     * BẮT BUỘC trả JSON với RspCode/Message theo tài liệu VNPAY.
     */
    public function ipn(Request $request): JsonResponse
    {
        try {
            if (!$this->vnpay->validateSignature($request)) {
                return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
            }

            $orderId = $this->vnpay->extractOrderId((string) $request->input('vnp_TxnRef'));
            $order   = $orderId ? Order::find($orderId) : null;

            if (!$order) {
                return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
            }

            // Số tiền VNPAY gửi đã x100 -> phải khớp với total_amount * 100.
            $expectedAmount = (int) round(((float) $order->total_amount) * 100);
            $receivedAmount = (int) $request->input('vnp_Amount');
            if ($expectedAmount !== $receivedAmount) {
                return response()->json(['RspCode' => '04', 'Message' => 'Invalid amount']);
            }

            if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
                return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
            }

            $responseCode = (string) $request->input('vnp_ResponseCode');
            $this->applyPaymentResult($order, $request, $responseCode);

            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        } catch (\Throwable $e) {
            Log::error('VNPAY IPN error', ['message' => $e->getMessage()]);
            return response()->json(['RspCode' => '99', 'Message' => 'Unknown error']);
        }
    }

    /**
     * Cập nhật trạng thái thanh toán cho đơn hàng dựa trên kết quả từ VNPAY.
     *
     * Trả về kết quả cuối cùng đã ghi nhận: 'paid' | 'pending' | 'failed'.
     * Return URL và IPN dùng chung hàm này; row được khoá (lockForUpdate) để
     * hai callback về gần như đồng thời không cùng ghi nhận / trừ kho hai lần.
     */
    protected function applyPaymentResult(Order $order, Request $request, string $responseCode): string
    {
        $transactionNo = (string) $request->input('vnp_TransactionNo');
        $bankCode      = (string) $request->input('vnp_BankCode');
        $paidOk        = $responseCode === '00'
            && (string) $request->input('vnp_TransactionStatus') === '00';

        if ($paidOk) {
            return DB::transaction(function () use ($order, $transactionNo, $bankCode) {
                /** @var Order $locked */
                $locked = Order::whereKey($order->id)->lockForUpdate()->first();

                if ($locked->payment_status === Order::PAYMENT_STATUS_PAID) {
                    return 'paid';
                }

                // Đơn VNPAY chưa trừ kho lúc đặt -> trừ kho tại thời điểm thanh toán.
                $this->deductStockForPaidOrder($locked);

                $locked->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                    'payment_ref'    => $transactionNo,
                    'paid_at'        => now(),
                    'status'         => $locked->status === Order::STATUS_PENDING
                        ? Order::STATUS_CONFIRMED
                        : $locked->status,
                ]);

                OrderHistory::create([
                    'order_id' => $locked->id,
                    'status'   => $locked->status,
                    'note'     => 'Thanh toán VNPAY thành công - Mã GD: ' . $transactionNo
                        . ($bankCode ? ' (' . $bankCode . ')' : ''),
                ]);

                return 'paid';
            });
        }

        // ResponseCode '00' nhưng TransactionStatus chưa phải '00' => giao dịch còn
        // đang xử lý/treo. KHÔNG đánh dấu thất bại, giữ nguyên 'pending' để khách
        // có thể thanh toán lại hoặc chờ IPN cập nhật sau.
        if ($responseCode === '00') {
            return 'pending';
        }

        // Mã lỗi rõ ràng từ VNPAY (huỷ, sai OTP, hết hạn...). Ghi nhận thất bại nếu
        // đơn chưa paid. Không đụng tới kho vì đơn VNPAY chưa từng trừ kho.
        return DB::transaction(function () use ($order, $responseCode) {
            /** @var Order $locked */
            $locked = Order::whereKey($order->id)->lockForUpdate()->first();

            if ($locked->payment_status === Order::PAYMENT_STATUS_PAID) {
                return 'paid';
            }

            if ($locked->payment_status !== Order::PAYMENT_STATUS_FAILED) {
                $locked->update(['payment_status' => Order::PAYMENT_STATUS_FAILED]);

                OrderHistory::create([
                    'order_id' => $locked->id,
                    'status'   => $locked->status,
                    'note'     => 'Thanh toán VNPAY thất bại - ' . $this->vnpay->getResponseMessage($responseCode),
                ]);
            }

            return 'failed';
        });
    }

    /**
     * Trừ kho cho đơn VNPAY khi thanh toán thành công (đơn VNPAY không trừ kho lúc
     * đặt). Khoá từng cuốn để tránh race. Nếu tồn kho không còn đủ (đã bán hết
     * trong lúc chờ thanh toán) thì trừ tối đa có thể và ghi cảnh báo để admin xử lý,
     * vì tiền đã được thu qua VNPAY.
     *
     * Gọi bên trong transaction đang khoá đơn của applyPaymentResult().
     */
    protected function deductStockForPaidOrder(Order $order): void
    {
        if ($order->payment_method !== Order::PAYMENT_VNPAY) {
            return; // COD... đã trừ kho lúc đặt.
        }

        $order->loadMissing('items');

        foreach ($order->items as $item) {
            if (!$item->book_id) {
                continue;
            }

            $book = Book::whereKey($item->book_id)->lockForUpdate()->first();
            if (!$book) {
                continue;
            }

            $take = min((int) $book->quantity, (int) $item->quantity);
            if ($take > 0) {
                $book->decrement('quantity', $take);
            }

            if ($take < (int) $item->quantity) {
                Log::warning('VNPAY paid but stock insufficient', [
                    'order_id' => $order->id,
                    'book_id'  => $item->book_id,
                    'ordered'  => (int) $item->quantity,
                    'deducted' => $take,
                ]);

                OrderHistory::create([
                    'order_id' => $order->id,
                    'status'   => $order->status,
                    'note'     => 'CẢNH BÁO: tồn kho không đủ khi xác nhận thanh toán cho "'
                        . $item->book_title . '" (đặt ' . (int) $item->quantity
                        . ', trừ được ' . $take . ') - cần xử lý thủ công.',
                ]);
            }
        }
    }
}
