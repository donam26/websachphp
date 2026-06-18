<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Service tích hợp VNPAY (sandbox / production).
 *
 * Tham chiếu tài liệu: https://sandbox.vnpayment.vn/apis/docs/thanh-toan-pay/pay.html
 */
class VNPayService
{
    protected string $tmnCode;
    protected string $hashSecret;
    protected string $url;
    protected string $locale;

    public function __construct()
    {
        $this->tmnCode    = (string) config('vnpay.tmn_code');
        $this->hashSecret = (string) config('vnpay.hash_secret');
        $this->url        = (string) config('vnpay.url');
        $this->locale     = (string) config('vnpay.locale', 'vn');
    }

    /**
     * Xác định Return URL.
     *
     * Ưu tiên cấu hình công khai (production). Với giá trị localhost (thường thiếu
     * cổng, gây redirect sai sau khi thanh toán) thì dùng URL thực tế của request
     * hiện tại để VNPAY quay về đúng host:port người dùng đang truy cập.
     */
    protected function resolveReturnUrl(): string
    {
        $configured = (string) config('vnpay.return_url');

        if ($configured !== '' && !Str::contains($configured, ['localhost', '127.0.0.1'])) {
            return $configured;
        }

        return url('/vnpay/return');
    }

    /**
     * Sinh URL thanh toán VNPAY cho đơn hàng.
     */
    public function createPaymentUrl(Order $order, ?string $bankCode = null, ?string $ipAddr = null): string
    {
        // Mã tham chiếu giao dịch: order_id + timestamp để có thể thanh toán lại đơn hàng cũ.
        // VNPAY yêu cầu vnp_TxnRef <= 100 ký tự, chỉ chứa chữ/số.
        $vnpTxnRef = $order->id . str_replace(['-', ':', ' '], '', now()->format('YmdHis'));

        // VNPAY yêu cầu vnp_OrderInfo không dấu, không ký tự đặc biệt.
        $orderInfo = $this->sanitizeOrderInfo('Thanh toan don hang ' . ($order->code ?: $order->id));

        $inputData = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => $this->tmnCode,
            'vnp_Amount'     => (int) round(((float) $order->total_amount) * 100),
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => $ipAddr ?: request()->ip(),
            'vnp_Locale'     => $this->locale,
            'vnp_OrderInfo'  => $orderInfo,
            'vnp_OrderType'  => 'other',
            'vnp_ReturnUrl'  => $this->resolveReturnUrl(),
            'vnp_TxnRef'     => $vnpTxnRef,
            // Hết hạn thanh toán sau 15 phút (theo chuẩn VNPAY).
            'vnp_ExpireDate' => now()->addMinutes(15)->format('YmdHis'),
        ];

        if (!empty($bankCode)) {
            $inputData['vnp_BankCode'] = $bankCode;
        }

        ksort($inputData);

        [$query, $hashData] = $this->buildQuery($inputData);
        $secureHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return $this->url . '?' . $query . '&vnp_SecureHash=' . $secureHash;
    }

    /**
     * Xác thực chữ ký từ VNPAY (cho cả Return URL và IPN).
     */
    public function validateSignature(Request $request): bool
    {
        $inputData = [];
        foreach ($request->all() as $key => $value) {
            if (is_string($key) && str_starts_with($key, 'vnp_')) {
                $inputData[$key] = $value;
            }
        }

        $receivedHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);

        if ($receivedHash === '') {
            return false;
        }

        ksort($inputData);
        [, $hashData] = $this->buildQuery($inputData);
        $calculatedHash = hash_hmac('sha512', $hashData, $this->hashSecret);

        return hash_equals($calculatedHash, $receivedHash);
    }

    /**
     * Trích xuất id đơn hàng gốc từ vnp_TxnRef.
     * Format: "{orderId}{YmdHis}" -> 14 ký tự cuối là timestamp, phần đầu là id.
     */
    public function extractOrderId(string $txnRef): ?int
    {
        if ($txnRef === '' || strlen($txnRef) <= 14) {
            return null;
        }

        // 14 ký tự cuối là timestamp YmdHis -> bỏ đi để lấy id.
        $idPart = substr($txnRef, 0, -14);

        return ctype_digit($idPart) ? (int) $idPart : null;
    }

    /**
     * Loại bỏ dấu tiếng Việt và ký tự đặc biệt cho vnp_OrderInfo.
     */
    protected function sanitizeOrderInfo(string $value): string
    {
        $map = [
            'à','á','ạ','ả','ã','â','ầ','ấ','ậ','ẩ','ẫ','ă','ằ','ắ','ặ','ẳ','ẵ',
            'è','é','ẹ','ẻ','ẽ','ê','ề','ế','ệ','ể','ễ',
            'ì','í','ị','ỉ','ĩ',
            'ò','ó','ọ','ỏ','õ','ô','ồ','ố','ộ','ổ','ỗ','ơ','ờ','ớ','ợ','ở','ỡ',
            'ù','ú','ụ','ủ','ũ','ư','ừ','ứ','ự','ử','ữ',
            'ỳ','ý','ỵ','ỷ','ỹ',
            'đ',
            'À','Á','Ạ','Ả','Ã','Â','Ầ','Ấ','Ậ','Ẩ','Ẫ','Ă','Ằ','Ắ','Ặ','Ẳ','Ẵ',
            'È','É','Ẹ','Ẻ','Ẽ','Ê','Ề','Ế','Ệ','Ể','Ễ',
            'Ì','Í','Ị','Ỉ','Ĩ',
            'Ò','Ó','Ọ','Ỏ','Õ','Ô','Ồ','Ố','Ộ','Ổ','Ỗ','Ơ','Ờ','Ớ','Ợ','Ở','Ỡ',
            'Ù','Ú','Ụ','Ủ','Ũ','Ư','Ừ','Ứ','Ự','Ử','Ữ',
            'Ỳ','Ý','Ỵ','Ỷ','Ỹ',
            'Đ',
        ];
        $replace = [
            'a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a','a',
            'e','e','e','e','e','e','e','e','e','e','e',
            'i','i','i','i','i',
            'o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o','o',
            'u','u','u','u','u','u','u','u','u','u','u',
            'y','y','y','y','y',
            'd',
            'A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A','A',
            'E','E','E','E','E','E','E','E','E','E','E',
            'I','I','I','I','I',
            'O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O','O',
            'U','U','U','U','U','U','U','U','U','U','U',
            'Y','Y','Y','Y','Y',
            'D',
        ];

        $value = str_replace($map, $replace, $value);
        $value = preg_replace('/[^A-Za-z0-9 ]/', '', $value) ?? '';

        return trim($value);
    }

    /**
     * Diễn giải mã response VNPAY trả về.
     */
    public function getResponseMessage(?string $code): string
    {
        return [
            '00' => 'Giao dịch thành công',
            '07' => 'Giao dịch nghi ngờ gian lận',
            '09' => 'Thẻ/Tài khoản chưa đăng ký InternetBanking',
            '10' => 'Xác thực thông tin thẻ/tài khoản sai quá 3 lần',
            '11' => 'Hết hạn chờ thanh toán',
            '12' => 'Thẻ/Tài khoản bị khoá',
            '13' => 'Sai mật khẩu OTP',
            '24' => 'Khách hàng huỷ giao dịch',
            '51' => 'Tài khoản không đủ số dư',
            '65' => 'Vượt quá hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng đang bảo trì',
            '79' => 'Sai mật khẩu thanh toán quá số lần quy định',
            '99' => 'Lỗi không xác định',
        ][$code] ?? 'Giao dịch không thành công';
    }

    /**
     * Build query string + hash data theo chuẩn VNPAY (urlencode key/value, nối bằng &).
     *
     * @return array{0:string,1:string} [queryString, hashData]
     */
    protected function buildQuery(array $data): array
    {
        $hashData = '';
        $query    = '';
        $first    = true;

        foreach ($data as $key => $value) {
            if (!$first) {
                $hashData .= '&';
                $query    .= '&';
            }
            $hashData .= urlencode($key) . '=' . urlencode((string) $value);
            $query    .= urlencode($key) . '=' . urlencode((string) $value);
            $first = false;
        }

        return [$query, $hashData];
    }
}
