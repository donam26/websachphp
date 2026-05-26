<?php

namespace App\Http\Controllers;

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

        $responseCode = (string) $request->input('vnp_ResponseCode');
        $this->applyPaymentResult($order, $request, $responseCode);

        if ($responseCode === '00') {
            return redirect()->route('orders.show', $order)
                ->with('success', 'Thanh toán thành công! Đơn hàng đã được xác nhận.');
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
     */
    protected function applyPaymentResult(Order $order, Request $request, string $responseCode): void
    {
        $transactionNo = (string) $request->input('vnp_TransactionNo');
        $bankCode      = (string) $request->input('vnp_BankCode');

        if ($responseCode === '00' && $request->input('vnp_TransactionStatus') === '00') {
            DB::transaction(function () use ($order, $transactionNo, $bankCode) {
                if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
                    return;
                }

                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                    'payment_ref'    => $transactionNo,
                    'paid_at'        => now(),
                    'status'         => $order->status === Order::STATUS_PENDING
                        ? Order::STATUS_CONFIRMED
                        : $order->status,
                ]);

                OrderHistory::create([
                    'order_id' => $order->id,
                    'status'   => $order->status,
                    'note'     => 'Thanh toán VNPAY thành công - Mã GD: ' . $transactionNo
                        . ($bankCode ? ' (' . $bankCode . ')' : ''),
                ]);
            });

            return;
        }

        // Chỉ ghi nhận thất bại nếu chưa được thanh toán (tránh ghi đè đơn đã paid).
        if ($order->payment_status !== Order::PAYMENT_STATUS_PAID) {
            $order->update(['payment_status' => Order::PAYMENT_STATUS_FAILED]);

            OrderHistory::create([
                'order_id' => $order->id,
                'status'   => $order->status,
                'note'     => 'Thanh toán VNPAY thất bại - ' . $this->vnpay->getResponseMessage($responseCode),
            ]);
        }
    }
}
