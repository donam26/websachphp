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

        $orders = $query->orderBy($sort, $dir)->orderBy('id', 'desc')
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
            'status' => 'required|in:pending,confirmed,shipping,completed,cancelled',
            'payment_status' => 'nullable|in:pending,paid,failed,refunded',
            'note' => 'nullable|string|max:500',
        ]);

        $newStatus = $validated['status'];
        $allowed = self::TRANSITIONS[$order->status] ?? [];

        if ($newStatus !== $order->status && !in_array($newStatus, $allowed)) {
            return back()->with('error', 'Không thể chuyển từ trạng thái "' . $order->status_label . '" sang trạng thái này');
        }

        DB::transaction(function () use ($order, $validated, $newStatus) {
            $shouldRestoreStock = $newStatus === Order::STATUS_CANCELLED && $order->status !== Order::STATUS_CANCELLED;
            $shouldMarkPaid = $newStatus === Order::STATUS_COMPLETED
                && $order->payment_method === Order::PAYMENT_COD
                && $order->payment_status !== Order::PAYMENT_STATUS_PAID;

            $updates = ['status' => $newStatus, 'employee_id' => auth()->id()];

            if (!empty($validated['payment_status'])) {
                $updates['payment_status'] = $validated['payment_status'];
                if ($validated['payment_status'] === Order::PAYMENT_STATUS_PAID && !$order->paid_at) {
                    $updates['paid_at'] = now();
                }
            }

            if ($shouldMarkPaid && empty($updates['payment_status'])) {
                $updates['payment_status'] = Order::PAYMENT_STATUS_PAID;
                $updates['paid_at'] = now();
            }

            if ($shouldRestoreStock) {
                foreach ($order->items as $item) {
                    if ($item->book) {
                        $item->book->increment('quantity', $item->quantity);
                    }
                }
                $updates['cancelled_at'] = now();
                if ($order->payment_status === Order::PAYMENT_STATUS_PAID) {
                    $updates['payment_status'] = Order::PAYMENT_STATUS_REFUNDED;
                }
            }

            $order->update($updates);

            $order->histories()->create([
                'status' => $newStatus,
                'note' => $validated['note'] ?? null,
            ]);
        });

        return back()->with('success', 'Cập nhật trạng thái đơn hàng thành công');
    }
}
