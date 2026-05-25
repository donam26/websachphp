@extends('layouts.app')

@section('title', 'Đơn hàng của tôi')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item active">Đơn hàng của tôi</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h4 class="mb-0"><i class="bi bi-receipt me-2 text-primary"></i>Đơn hàng của tôi</h4>
    <a href="{{ route('books.index') }}" class="btn btn-outline-primary">
        <i class="bi bi-cart-plus me-1"></i>Mua sắm thêm
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm theo mã đơn (BS...)">
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    @foreach(\App\Models\Order::statusOptions() as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary flex-grow-1"><i class="bi bi-search me-1"></i>Lọc</button>
                @if(request('search') || request('status'))
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                @endif
            </div>
        </form>
    </div>
</div>

@if($orders->count() > 0)
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead style="background:#f9fafb;">
                        <tr>
                            <th class="ps-3">Mã đơn</th>
                            <th>Ngày đặt</th>
                            <th>Sản phẩm</th>
                            <th class="text-end">Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-3">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td class="ps-3">
                                    <strong class="text-primary">{{ $order->code }}</strong>
                                </td>
                                <td>
                                    <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="small">
                                        @foreach($order->items->take(2) as $item)
                                            <div>{{ Str::limit($item->book_title ?? $item->book?->title ?? 'Sản phẩm', 30) }} <span class="text-muted">x{{ $item->quantity }}</span></div>
                                        @endforeach
                                        @if($order->items->count() > 2)
                                            <small class="text-muted">+ {{ $order->items->count() - 2 }} sản phẩm khác</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-end"><strong class="text-primary">{{ number_format($order->total_amount, 0, ',', '.') }}đ</strong></td>
                                <td>
                                    <span class="badge badge-soft-{{ $order->status_color }}">{{ $order->status_label }}</span>
                                    @if($order->payment_status === \App\Models\Order::PAYMENT_STATUS_PAID)
                                        <div><small class="text-success"><i class="bi bi-check2-circle"></i> Đã thanh toán</small></div>
                                    @elseif($order->payment_status === \App\Models\Order::PAYMENT_STATUS_FAILED)
                                        <div><small class="text-danger"><i class="bi bi-x-circle"></i> Thanh toán lỗi</small></div>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-3">
        {{ $orders->links() }}
    </div>
@else
    <div class="card">
        <div class="card-body empty-state">
            <i class="bi bi-bag-x"></i>
            <h5>Không có đơn hàng nào</h5>
            <p>{{ request('search') || request('status') ? 'Không tìm thấy đơn phù hợp.' : 'Hãy khám phá những cuốn sách hay và đặt mua ngay!' }}</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary mt-2">
                <i class="bi bi-cart-plus me-1"></i>Mua sắm ngay
            </a>
        </div>
    </div>
@endif

@push('styles')
<style>
.badge { font-weight: 500; padding: 5px 10px; border-radius: 6px; font-size: 11.5px; }
.badge-soft-success { background: rgba(16,185,129,.12); color: #047857; }
.badge-soft-danger { background: rgba(239,68,68,.12); color: #b91c1c; }
.badge-soft-warning { background: rgba(245,158,11,.12); color: #b45309; }
.badge-soft-info { background: rgba(59,130,246,.12); color: #1d4ed8; }
.badge-soft-primary { background: rgba(201,33,39,.12); color: var(--primary); }
.badge-soft-secondary { background: rgba(107,114,128,.12); color: #4b5563; }
</style>
@endpush
@endsection
