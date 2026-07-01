<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private const TRANSITIONS = [
        Order::STATUS_PENDING => [Order::STATUS_CONFIRMED, Order::STATUS_CANCELLED],
        Order::STATUS_CONFIRMED => [Order::STATUS_SHIPPING, Order::STATUS_CANCELLED],
        Order::STATUS_SHIPPING => [Order::STATUS_COMPLETED, Order::STATUS_CANCELLED],
        Order::STATUS_COMPLETED => [],
        Order::STATUS_CANCELLED => [],
    ];

    public function index(Request $request)
    {
        $query = Order::with('user');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($payment = $request->input('payment_status')) {
            $query->where('payment_status', $payment);
        }

        if ($method = $request->input('payment_method')) {
            $query->where('payment_method', $method);
        }

        if ($search = trim($request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('shipping_phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($qq) use ($search) {
                      $qq->where('username', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%")
                         ->orWhere('full_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($from = $request->input('from')) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to = $request->input('to')) {
            $query->whereDate('created_at', '<=', $to);
        }

        $sortable = ['created_at', 'status', 'total_amount'];
        $sort = in_array($request->input('sort'), $sortable, true) ? $request->input('sort') : 'created_at';
        $dir = $request->input('dir') === 'asc' ? 'asc' : 'desc';

        if ($sort === 'status') {
            // Sắp theo đúng luồng xử lý đơn (Chờ xác nhận → ... → Đã huỷ),
            // không phải theo bảng chữ cái của giá trị chuỗi.
            $statusOrder = array_keys(Order::statusOptions());
            $placeholders = implode(',', array_fill(0, count($statusOrder), '?'));
            $query->orderByRaw("FIELD(status, {$placeholders}) {$dir}", $statusOrder);
        } else {
            $query->orderBy($sort, $dir);
        }

        $orders = $query->orderBy('id', 'desc')
            ->paginate(15)->appends($request->query());

        return view('admin.orders.index', compact('orders', 'sort', 'dir'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'employee', 'items.book.authors', 'histories']);
        $allowedTransitions = self::TRANSITIONS[$order->status] ?? [];

        return view('admin.orders.show', compact('order', 'allowedTransitions'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:pending,confirmed,shipping,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'note' => 'nullable|string|max:500',
        ]);

        $allowed = self::TRANSITIONS[$order->status] ?? [];
        $newStatus = $validated['status'] ?? null;
        $statusChanged = $newStatus !== null && $newStatus !== $order->status;
        $paymentChange = $validated['payment_status'] ?? null;
        $note = isset($validated['note']) && $validated['note'] !== '' ? $validated['note'] : null;

        if ($statusChanged && !in_array($newStatus, $allowed, true)) {
            return back()->with('error', 'Không thể chuyển từ trạng thái "' . $order->status_label . '" sang trạng thái này');
        }

        // Không có thay đổi thực sự -> không ghi lịch sử rỗng, không ghi đè nhân viên.
        if (!$statusChanged && empty($paymentChange) && $note === null) {
            return back()->with('error', 'Bạn chưa chọn thay đổi nào để cập nhật.');
        }

        DB::transaction(function () use ($order, $newStatus, $statusChanged, $paymentChange, $note) {
            $updates = [];

            // 1) Nhân viên chỉnh tay trạng thái thanh toán (đơn COD).
            if (!empty($paymentChange)) {
                $updates['payment_status'] = $paymentChange;
                if ($paymentChange === Order::PAYMENT_STATUS_PAID && !$order->paid_at) {
                    $updates['paid_at'] = now();
                }
            }

            // 2) Chuyển trạng thái đơn + các hệ quả kèm theo.
            if ($statusChanged) {
                $updates['status'] = $newStatus;
                $updates['employee_id'] = auth()->id();

                // COD hoàn tất -> tự động đánh dấu đã thu tiền (nếu NV chưa chỉnh tay).
                if ($newStatus === Order::STATUS_COMPLETED
                    && $order->payment_method === Order::PAYMENT_COD
                    && $order->payment_status !== Order::PAYMENT_STATUS_PAID
                    && empty($updates['payment_status'])) {
                    $updates['payment_status'] = Order::PAYMENT_STATUS_PAID;
                    $updates['paid_at'] = now();
                }

                // Huỷ đơn: đặt thời điểm huỷ, hoàn tiền nếu đã thu, hoàn kho nếu đã trừ.
                if ($newStatus === Order::STATUS_CANCELLED) {
                    $updates['cancelled_at'] = now();
                    if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
                        $updates['payment_status'] = Order::PAYMENT_STATUS_REFUNDED;
                    }
                    // Chỉ hoàn kho khi đơn đã thực sự trừ kho (đơn VNPAY chưa thanh
                    // toán chưa từng trừ kho -> không hoàn).
                    if ($order->stockWasDeducted()) {
                        foreach ($order->items as $item) {
                            if ($item->book) {
                                $item->book->increment('quantity', $item->quantity);
                            }
                        }
                    }
                }
            }

            if (!empty($updates)) {
                $order->update($updates);
            }

            $order->histories()->create([
                'status' => $newStatus ?? $order->status,
                'note' => $note,
            ]);
        });

        return back()->with('success', 'Cập nhật đơn hàng thành công');
    }
}
