<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Order;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function apply(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($validated['code']));
        $discount = Discount::where('code', $code)->first();

        if (!$discount) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá không tồn tại',
            ]);
        }

        if (!$discount->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Mã giảm giá đã hết hạn hoặc không còn hiệu lực',
            ]);
        }

        $cartItems = auth()->user()->cart()->with('book')->get();
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Giỏ hàng đang trống',
            ]);
        }

        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);

        if ($subtotal < $discount->min_order_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Đơn hàng tối thiểu để dùng mã này là ' . number_format($discount->min_order_amount, 0, ',', '.') . 'đ',
            ]);
        }

        $discountAmount = $discount->calculateDiscount($subtotal);
        $shippingFee = $subtotal >= Order::FREESHIP_THRESHOLD ? 0 : Order::SHIPPING_FEE;
        $total = max(0, $subtotal + $shippingFee - $discountAmount);

        session()->put('applied_discount', [
            'code' => $code,
            'amount' => $discountAmount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã giảm giá thành công',
            'discount' => [
                'code' => $code,
                'amount' => $discountAmount,
                'formatted_amount' => number_format($discountAmount, 0, ',', '.') . 'đ',
                'shipping_fee' => $shippingFee,
                'formatted_shipping' => $shippingFee > 0 ? number_format($shippingFee, 0, ',', '.') . 'đ' : 'Miễn phí',
                'total' => $total,
                'formatted_total' => number_format($total, 0, ',', '.') . 'đ',
            ],
        ]);
    }

    public function remove(Request $request)
    {
        session()->forget('applied_discount');

        $cartItems = auth()->user()->cart()->with('book')->get();
        $subtotal = $cartItems->sum(fn ($item) => $item->subtotal);
        $shippingFee = $subtotal >= Order::FREESHIP_THRESHOLD || $subtotal === 0 ? 0 : Order::SHIPPING_FEE;
        $total = max(0, $subtotal + $shippingFee);

        return response()->json([
            'success' => true,
            'message' => 'Đã bỏ mã giảm giá',
            'subtotal' => $subtotal,
            'shipping_fee' => $shippingFee,
            'total' => $total,
            'formatted_total' => number_format($total, 0, ',', '.') . 'đ',
        ]);
    }
}
