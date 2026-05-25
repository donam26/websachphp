@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng ' . $order->code)

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}" class="text-decoration-none">Đơn hàng</a></li>
        <li class="breadcrumb-item active">{{ $order->code }}</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0">
        <i class="bi bi-receipt me-2 text-primary"></i>Đơn hàng {{ $order->code }}
        <span class="badge badge-soft-{{ $order->status_color }} ms-2">{{ $order->status_label }}</span>
    </h4>
    <div class="d-flex gap-2">
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
        @if($order->payment_method === \App\Models\Order::PAYMENT_VNPAY && in_array($order->payment_status, [\App\Models\Order::PAYMENT_STATUS_PENDING, \App\Models\Order::PAYMENT_STATUS_FAILED]) && $order->status !== \App\Models\Order::STATUS_CANCELLED)
            <form action="{{ route('orders.repay', $order) }}" method="POST">@csrf
                <button class="btn btn-success"><i class="bi bi-credit-card me-1"></i>Thanh toán lại</button>
            </form>
        @endif
        @if($order->canBeCancelledByUser())
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal">
                <i class="bi bi-x-circle me-1"></i>Huỷ đơn
            </button>
        @endif
    </div>
</div>

@php
    $statuses = [
        \App\Models\Order::STATUS_PENDING => ['label' => 'Chờ xác nhận', 'icon' => 'bi-clock'],
        \App\Models\Order::STATUS_CONFIRMED => ['label' => 'Đã xác nhận', 'icon' => 'bi-check-circle'],
        \App\Models\Order::STATUS_SHIPPING => ['label' => 'Đang giao', 'icon' => 'bi-truck'],
        \App\Models\Order::STATUS_COMPLETED => ['label' => 'Hoàn thành', 'icon' => 'bi-check2-all'],
    ];
    $currentIndex = array_search($order->status, array_keys($statuses));
    $isCancelled = $order->status === \App\Models\Order::STATUS_CANCELLED;
@endphp

<div class="card mb-3">
    <div class="card-body">
        @if($isCancelled)
            <div class="alert alert-danger mb-0">
                <i class="bi bi-x-circle me-2"></i><strong>Đơn hàng đã huỷ</strong>
                @if($order->cancelled_at) lúc {{ $order->cancelled_at->format('d/m/Y H:i') }} @endif
            </div>
        @else
            <div class="order-timeline">
                @foreach($statuses as $key => $status)
                    <div class="timeline-step {{ $currentIndex >= $loop->index ? 'active' : '' }} {{ $currentIndex == $loop->index ? 'current' : '' }}">
                        <div class="step-icon"><i class="bi {{ $status['icon'] }}"></i></div>
                        <div class="step-label">{{ $status['label'] }}</div>
                    </div>
                    @if(!$loop->last)
                        <div class="timeline-line {{ $currentIndex > $loop->index ? 'active' : '' }}"></div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-box-seam me-2"></i>Sản phẩm đã đặt</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead style="background:#f9fafb;">
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
                                            <img src="{{ $item->book?->image_url ?? 'https://placehold.co/80x100/f4f6f8/c92127?text=Book' }}"
                                                 style="width:60px;height:80px;object-fit:cover;border-radius:6px;"
                                                 onerror="this.src='https://placehold.co/80x100/f4f6f8/c92127?text=Book'">
                                            <div>
                                                <div class="fw-semibold">{{ $item->book_title ?? $item->book?->title ?? 'Sản phẩm' }}</div>
                                                @if($item->book?->author)
                                                    <small class="text-muted">{{ $item->book->author }}</small>
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
                    </table>
                </div>
            </div>
        </div>

        @if($order->histories->count())
            <div class="card">
                <div class="card-header"><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng</div>
                <div class="card-body">
                    <ul class="history-list">
                        @foreach($order->histories->sortByDesc('created_at') as $h)
                            <li>
                                <div class="d-flex justify-content-between flex-wrap">
                                    <span class="badge badge-soft-secondary">{{ \App\Models\Order::statusOptions()[$h->status] ?? $h->status }}</span>
                                    <small class="text-muted">{{ $h->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                @if($h->note)
                                    <div class="small text-muted mt-1">{{ $h->note }}</div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-truck me-2"></i>Thông tin giao hàng</div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $order->shipping_name }}</strong></p>
                <p class="mb-1 small"><i class="bi bi-telephone me-1"></i>{{ $order->shipping_phone }}</p>
                <p class="mb-0 small text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $order->shipping_address }}</p>
                @if($order->note)
                    <hr class="my-2">
                    <p class="mb-0 small"><strong>Ghi chú:</strong> {{ $order->note }}</p>
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
                    @php
                        $pmColor = ['paid' => 'success', 'pending' => 'warning', 'failed' => 'danger', 'refunded' => 'info'][$order->payment_status] ?? 'secondary';
                    @endphp
                    <span class="badge badge-soft-{{ $pmColor }}">{{ $order->payment_status_label }}</span>
                </div>
                @if($order->paid_at)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Thanh toán lúc:</span>
                        <small>{{ $order->paid_at->format('d/m/Y H:i') }}</small>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Tạm tính:</span>
                    <span>{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Phí ship:</span>
                    <span>{{ $order->shipping_fee > 0 ? number_format($order->shipping_fee, 0, ',', '.') . 'đ' : 'Miễn phí' }}</span>
                </div>
                @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Giảm giá @if($order->discount)({{ $order->discount->code }})@endif:</span>
                        <span class="text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                    </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between">
                    <strong>Tổng cộng:</strong>
                    <strong class="text-primary fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong>
                </div>
            </div>
        </div>
    </div>
</div>

@if($order->canBeCancelledByUser())
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('orders.cancel', $order) }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Huỷ đơn hàng {{ $order->code }}?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Tồn kho sản phẩm sẽ được hoàn lại sau khi huỷ.</p>
                <label class="form-label small text-muted">Lý do huỷ (không bắt buộc)</label>
                <textarea name="reason" class="form-control" rows="2" maxlength="255" placeholder="Bạn có thể bỏ qua trường này"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="submit" class="btn btn-danger"><i class="bi bi-x-circle me-1"></i>Xác nhận huỷ</button>
            </div>
        </form>
    </div>
</div>
@endif

@push('styles')
<style>
.order-timeline { display: flex; align-items: center; padding: 10px 0; }
.timeline-step { text-align: center; flex-shrink: 0; }
.timeline-step .step-icon { width: 48px; height: 48px; background: #e5e7eb; color: #9ca3af; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 6px; font-size: 20px; transition: .3s; }
.timeline-step.active .step-icon { background: rgba(28,166,114,.15); color: #1ca672; }
.timeline-step.current .step-icon { background: var(--primary); color: #fff; box-shadow: 0 0 0 4px rgba(201,33,39,.2); }
.timeline-step .step-label { font-size: 12.5px; color: #6b7280; font-weight: 500; }
.timeline-step.active .step-label { color: var(--text-dark); }
.timeline-line { flex: 1; height: 3px; background: #e5e7eb; margin: 0 8px 24px; border-radius: 999px; }
.timeline-line.active { background: #1ca672; }
.card-header { padding: 12px 16px; font-weight: 600; background: #fff; border-bottom: 1px solid var(--border-light); }
.history-list { list-style: none; padding-left: 0; margin: 0; }
.history-list li { padding: 10px 0; border-bottom: 1px dashed #e5e7eb; }
.history-list li:last-child { border-bottom: 0; }
.badge-soft-success { background: rgba(16,185,129,.12); color: #047857; }
.badge-soft-danger { background: rgba(239,68,68,.12); color: #b91c1c; }
.badge-soft-warning { background: rgba(245,158,11,.12); color: #b45309; }
.badge-soft-info { background: rgba(59,130,246,.12); color: #1d4ed8; }
.badge-soft-primary { background: rgba(201,33,39,.12); color: var(--primary); }
.badge-soft-secondary { background: rgba(107,114,128,.12); color: #4b5563; }
</style>
@endpush
@endsection
