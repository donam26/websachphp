@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
@php
    $sortLink = function ($column, $label) use ($sort, $dir) {
        $newDir = ($sort === $column && $dir === 'asc') ? 'desc' : 'asc';
        $icon = $sort === $column ? ($dir === 'asc' ? 'bi-sort-up' : 'bi-sort-down') : 'bi-arrow-down-up';
        $params = array_merge(request()->query(), ['sort' => $column, 'dir' => $newDir]);
        $cls = $sort === $column ? 'text-primary' : 'text-muted';
        return '<a href="' . route('admin.orders.index', $params) . '" class="text-decoration-none text-reset d-inline-flex align-items-center gap-1">' . e($label) . ' <i class="bi ' . $icon . ' small ' . $cls . '"></i></a>';
    };
@endphp
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-receipt me-2 text-primary"></i>Quản lý đơn hàng</h5>
                <small class="text-muted">Tổng cộng {{ $orders->total() }} đơn hàng</small>
            </div>
        </div>

        <form action="{{ route('admin.orders.index') }}" method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Mã đơn / SĐT / Khách hàng" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Trạng thái</option>
                        @foreach(\App\Models\Order::statusOptions() as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="payment_status" class="form-select">
                        <option value="">Thanh toán</option>
                        <option value="pending" {{ request('payment_status')=='pending'?'selected':'' }}>Chưa thanh toán</option>
                        <option value="paid" {{ request('payment_status')=='paid'?'selected':'' }}>Đã thanh toán</option>
                        <option value="failed" {{ request('payment_status')=='failed'?'selected':'' }}>Thất bại</option>
                        <option value="refunded" {{ request('payment_status')=='refunded'?'selected':'' }}>Hoàn tiền</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}" title="Từ ngày">
                </div>
                <div class="col-md-2">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}" title="Đến ngày">
                </div>
                @if(request('sort'))<input type="hidden" name="sort" value="{{ request('sort') }}">@endif
                @if(request('dir'))<input type="hidden" name="dir" value="{{ request('dir') }}">@endif
                <div class="col-12 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-funnel me-1"></i>Lọc</button>
                    @if(request()->hasAny(['search', 'status', 'payment_status', 'from', 'to', 'sort']))
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-3">Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>{!! $sortLink('total_amount', 'Tổng tiền') !!}</th>
                        <th>Thanh toán</th>
                        <th>{!! $sortLink('status', 'Trạng thái') !!}</th>
                        <th>{!! $sortLink('created_at', 'Ngày đặt') !!}</th>
                        <th class="text-end pe-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $order->code }}</strong></td>
                            <td>
                                <div class="fw-semibold">{{ $order->user->full_name ?? $order->user->username }}</div>
                                <small class="text-muted">{{ $order->shipping_phone }}</small>
                            </td>
                            <td class="fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                            <td>
                                <small class="d-block">{{ $order->payment_method_label }}</small>
                                @php
                                    $pmCls = ['paid' => 'success', 'pending' => 'warning', 'failed' => 'danger', 'refunded' => 'info'][$order->payment_status] ?? 'secondary';
                                @endphp
                                <span class="badge badge-soft-{{ $pmCls }}">{{ $order->payment_status_label }}</span>
                            </td>
                            <td><span class="badge badge-soft-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                            <td>
                                <div class="small">{{ $order->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                            </td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Không có đơn hàng nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($orders->hasPages())
        <div class="card-footer bg-white">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
