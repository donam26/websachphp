@extends('layouts.admin')

@section('title', 'Đơn hàng ' . $order->code)

@section('content')
<div class="d-flex align-items-center mb-3 flex-wrap gap-2">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 me-auto">
        <i class="bi bi-receipt me-2 text-primary"></i>Đơn hàng {{ $order->code }}
    </h5>
    <span class="badge badge-soft-{{ $order->status_color }} fs-6 px-3 py-2">{{ $order->status_label }}</span>
    @php $pmCls = ['paid' => 'success', 'pending' => 'warning', 'failed' => 'danger', 'refunded' => 'info'][$order->payment_status] ?? 'secondary'; @endphp
    <span class="badge badge-soft-{{ $pmCls }} fs-6 px-3 py-2">{{ $order->payment_status_label }}</span>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i>Sản phẩm</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">Sản phẩm</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">SL</th>
                                <th class="text-end pe-3">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item->book?->image_url ?? 'https://placehold.co/80x100/f4f6f8/4f46e5?text=Book' }}"
                                                 style="width:50px;height:66px;object-fit:cover;border-radius:6px;"
                                                 onerror="this.src='https://placehold.co/80x100/f4f6f8/4f46e5?text=Book'">
                                            <div>
                                                <div class="fw-semibold">{{ $item->book_title ?? $item->book?->title ?? 'Sản phẩm' }}</div>
                                                @if($item->book?->author_names)
                                                    <small class="text-muted">{{ $item->book->author_names }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-end">{{ number_format($item->price, 0, ',', '.') }}đ</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end pe-3 fw-bold text-primary">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot style="background:#f9fafb;">
                            <tr><td colspan="3" class="text-end">Tạm tính:</td><td class="text-end pe-3">{{ number_format($order->subtotal, 0, ',', '.') }}đ</td></tr>
                            <tr><td colspan="3" class="text-end">Phí vận chuyển:</td><td class="text-end pe-3">{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.').'đ' : 'Miễn phí' }}</td></tr>
                            @if($order->discount_amount > 0)
                            <tr><td colspan="3" class="text-end">Giảm giá @if($order->discount)<code>{{ $order->discount->code }}</code>@endif:</td><td class="text-end pe-3 text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</td></tr>
                            @endif
                            <tr><td colspan="3" class="text-end"><strong>Tổng cộng:</strong></td><td class="text-end pe-3"><strong class="text-primary fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong></td></tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        @if($order->histories->count() > 0)
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng</div>
            <div class="card-body">
                <div class="history-timeline">
                    @foreach($order->histories->sortByDesc('created_at') as $h)
                        <div class="history-item">
                            <div class="history-dot"></div>
                            <div>
                                <div class="d-flex justify-content-between flex-wrap">
                                    <strong>{{ \App\Models\Order::statusOptions()[$h->status] ?? $h->status }}</strong>
                                    <small class="text-muted">{{ $h->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                @if($h->note)<div class="text-muted small mt-1">{{ $h->note }}</div>@endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-person me-2"></i>Khách hàng</div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $order->user->full_name ?? $order->user->username }}</strong></p>
                <p class="mb-1 small text-muted"><i class="bi bi-envelope me-1"></i>{{ $order->user->email }}</p>
                <p class="mb-0 small text-muted"><i class="bi bi-telephone me-1"></i>{{ $order->user->phone_number ?? 'N/A' }}</p>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-truck me-2"></i>Giao hàng</div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                <p class="mb-1 small"><i class="bi bi-telephone me-1"></i>{{ $order->shipping_phone }}</p>
                <p class="mb-0 small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $order->shipping_address }}</p>
                @if($order->note)
                    <hr class="my-2">
                    <p class="mb-0 small"><strong>Ghi chú khách:</strong> {{ $order->note }}</p>
                @endif
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-credit-card me-2"></i>Thanh toán</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Phương thức:</span>
                    <span class="fw-semibold">{{ $order->payment_method_label }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Trạng thái:</span>
                    <span class="badge badge-soft-{{ $pmCls }}">{{ $order->payment_status_label }}</span>
                </div>
                <div class="d-flex justify-content-between mb-0">
                    <span class="text-muted">Nhân viên xử lý:</span>
                    <span class="fw-semibold">{{ $order->employee->full_name ?? $order->employee->username ?? 'Chưa có' }}</span>
                </div>
                @if($order->payment_ref)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Mã GD:</span>
                        <small>{{ $order->payment_ref }}</small>
                    </div>
                @endif
                @if($order->paid_at)
                    <div class="d-flex justify-content-between mb-0">
                        <span class="text-muted">TT lúc:</span>
                        <small>{{ $order->paid_at->format('d/m/Y H:i') }}</small>
                    </div>
                @endif
            </div>
        </div>

        @if(count($allowedTransitions) > 0)
        <div class="card">
            <div class="card-header"><i class="bi bi-arrow-repeat me-2"></i>Cập nhật trạng thái</div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="form-label small">Trạng thái mới</label>
                        <select name="status" class="form-select" required>
                            <option value="{{ $order->status }}">— Giữ nguyên ({{ $order->status_label }}) —</option>
                            @foreach($allowedTransitions as $next)
                                <option value="{{ $next }}">{{ \App\Models\Order::statusOptions()[$next] }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($order->payment_method === \App\Models\Order::PAYMENT_COD)
                    <div class="mb-3">
                        <label class="form-label small">Trạng thái thanh toán (COD)</label>
                        <select name="payment_status" class="form-select">
                            <option value="">— Giữ nguyên —</option>
                            <option value="pending">Chưa thanh toán</option>
                            <option value="paid">Đã thanh toán</option>
                        </select>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label small">Ghi chú (tuỳ chọn)</label>
                        <textarea name="note" class="form-control" rows="3" maxlength="500" placeholder="Ghi chú cho lịch sử..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-check2 me-1"></i>Cập nhật</button>
                    @if(in_array(\App\Models\Order::STATUS_CANCELLED, $allowedTransitions))
                        <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i>Huỷ đơn sẽ tự động hoàn lại tồn kho.</small>
                    @endif
                </form>
            </div>
        </div>
        @else
        <div class="alert alert-secondary mb-0"><i class="bi bi-info-circle me-1"></i>Đơn ở trạng thái cuối, không thể thay đổi.</div>
        @endif
    </div>
</div>

@push('styles')
<style>
.history-timeline { position: relative; }
.history-item { position: relative; padding-left: 24px; padding-bottom: 14px; border-left: 2px solid var(--border, #e5e7eb); margin-left: 6px; }
.history-item:last-child { border-left-color: transparent; padding-bottom: 0; }
.history-dot { position: absolute; left: -7px; top: 5px; width: 12px; height: 12px; background: var(--primary); border: 2px solid #fff; border-radius: 50%; box-shadow: 0 0 0 2px var(--primary); }
.badge-soft-secondary { background: rgba(107,114,128,.12); color: #4b5563; }
</style>
@endpush
@endsection
