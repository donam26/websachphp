<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::ordered()->withCount('orders')->paginate(15);

        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => [
                'required', 'string', 'max:50',
                'regex:/^[a-z0-9_\-]+$/',
                Rule::unique('payment_methods', 'code'),
            ],
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ], [
            'code.regex' => 'Mã chỉ gồm chữ thường, số, gạch ngang hoặc gạch dưới.',
        ]);

        PaymentMethod::create([
            'code' => $data['code'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Đã thêm phương thức thanh toán.');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'sort_order' => 'nullable|integer|min:0|max:9999',
        ]);

        // Code là khoá tham chiếu trong đơn hàng -> không cho đổi sau khi tạo.
        $paymentMethod->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'icon' => $data['icon'] ?? null,
            'is_active' => $request->boolean('is_active'),
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Đã cập nhật phương thức thanh toán.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->isSystem()) {
            return redirect()->route('admin.payment-methods.index')
                ->with('error', 'Không thể xoá phương thức hệ thống (' . $paymentMethod->code . '). Bạn có thể tắt kích hoạt thay vì xoá.');
        }

        if ($paymentMethod->orders()->exists()) {
            return redirect()->route('admin.payment-methods.index')
                ->with('error', 'Đang có đơn hàng dùng phương thức này nên không thể xoá. Hãy tắt kích hoạt.');
        }

        $paymentMethod->delete();

        return redirect()->route('admin.payment-methods.index')
            ->with('success', 'Đã xoá phương thức thanh toán.');
    }
}
