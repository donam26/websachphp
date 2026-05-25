<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class VNPayController extends Controller
{
    public function createPayment(Order $order)
    {
        $vnpUrl = config('vnpay.url');
        $vnpReturnUrl = config('vnpay.return_url') ?: URL::to('/vnpay/return');
        $vnpTmnCode = config('vnpay.tmn_code');
        $vnpHashSecret = config('vnpay.hash_secret');
        $vnpLocale = config('vnpay.locale', 'vn');

        $vnpTxnRef = $order->id . '_' . now()->format('YmdHis');
        $vnpOrderInfo = 'Thanh toan don hang ' . $order->code;
        $vnpAmount = (int) round($order->total_amount * 100);

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnpTmnCode,
            'vnp_Amount' => $vnpAmount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => request()->ip(),
            'vnp_Locale' => $vnpLocale,
            'vnp_OrderInfo' => $vnpOrderInfo,
            'vnp_OrderType' => 'other',
            'vnp_ReturnUrl' => $vnpReturnUrl,
            'vnp_TxnRef' => $vnpTxnRef,
        ];

        ksort($inputData);

        $hashData = '';
        $query = '';
        foreach ($inputData as $key => $value) {
            if ($hashData !== '') {
                $hashData .= '&';
                $query .= '&';
            }
            $hashData .= urlencode($key) . '=' . urlencode($value);
            $query .= urlencode($key) . '=' . urlencode($value);
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnpHashSecret);
        $finalUrl = $vnpUrl . '?' . $query . '&vnp_SecureHash=' . $secureHash;

        Log::info('VNPAY create payment', ['order_id' => $order->id, 'txn_ref' => $vnpTxnRef]);

        return redirect()->away($finalUrl);
    }

    public function return(Request $request)
    {
        $vnpHashSecret = config('vnpay.hash_secret');

        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (str_starts_with($key, 'vnp_')) {
                $inputData[$key] = $value;
            }
        }

        $receivedHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);
        ksort($inputData);

        $hashData = '';
        foreach ($inputData as $key => $value) {
            if ($hashData !== '') {
                $hashData .= '&';
            }
            $hashData .= urlencode($key) . '=' . urlencode($value);
        }
        $calculatedHash = hash_hmac('sha512', $hashData, $vnpHashSecret);

        $txnRef = $request->input('vnp_TxnRef', '');
        $orderId = (int) explode('_', $txnRef)[0];

        if (!$orderId || !hash_equals($calculatedHash, $receivedHash)) {
            Log::warning('VNPAY invalid signature', ['txn_ref' => $txnRef]);
            return redirect()->route('cart.index')
                ->with('error', 'Xác thực thanh toán thất bại');
        }

        $order = Order::find($orderId);
        if (!$order) {
            return redirect()->route('orders.index')
                ->with('error', 'Không tìm thấy đơn hàng');
        }

        $responseCode = $request->input('vnp_ResponseCode');
        $transactionNo = $request->input('vnp_TransactionNo');

        if ($responseCode === '00') {
            DB::transaction(function () use ($order, $transactionNo) {
                if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
                    return;
                }

                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                    'payment_ref' => $transactionNo,
                    'paid_at' => now(),
                    'status' => $order->status === Order::STATUS_PENDING ? Order::STATUS_CONFIRMED : $order->status,
                ]);

                OrderHistory::create([
                    'order_id' => $order->id,
                    'status' => $order->status,
                    'note' => 'Thanh toán VNPAY thành công - Mã GD: ' . $transactionNo,
                ]);
            });

            return redirect()->route('orders.show', $order)
                ->with('success', 'Thanh toán thành công! Đơn hàng đã được xác nhận.');
        }

        $order->update([
            'payment_status' => Order::PAYMENT_STATUS_FAILED,
        ]);

        OrderHistory::create([
            'order_id' => $order->id,
            'status' => $order->status,
            'note' => 'Thanh toán VNPAY thất bại - Mã lỗi: ' . $responseCode,
        ]);

        return redirect()->route('orders.show', $order)
            ->with('error', 'Thanh toán không thành công. Bạn có thể thanh toán lại từ trang chi tiết đơn hàng.');
    }
}
