<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->orders()->with('items');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = trim($request->input('search', ''))) {
            $query->where('code', 'like', "%{$search}%");
        }

        $orders = $query->latest()->paginate(10)->appends($request->query());

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        $order->load(['items.book.authors', 'histories', 'discount']);

        return view('orders.show', compact('order'));
    }

    public function cancel(Request $request, Order $order)
    {
        abort_if($order->user_id !== auth()->id(), 403);

        if (!$order->canBeCancelledByUser()) {
            return back()->with('error', 'Đơn hàng không thể huỷ ở trạng thái hiện tại');
        }

        $request->validate([
            'reason' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($order, $request) {
            foreach ($order->items as $item) {
                if ($item->book) {
                    $item->book->increment('quantity', $item->quantity);
                }
            }

            $order->update([
                'status' => Order::STATUS_CANCELLED,
                'cancelled_at' => now(),
            ]);

            $order->histories()->create([
                'status' => Order::STATUS_CANCELLED,
                'note' => 'Khách hàng huỷ đơn' . ($request->reason ? ': ' . $request->reason : ''),
            ]);
        });

        return redirect()->route('orders.show', $order)
            ->with('success', 'Đã huỷ đơn hàng thành công');
    }
}
