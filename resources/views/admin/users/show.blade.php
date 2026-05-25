@extends('layouts.admin')

@section('title', 'Chi tiết người dùng')

@section('content')
<div class="d-flex align-items-center mb-3 flex-wrap gap-2">
    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary me-2"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0 me-auto"><i class="bi bi-person-circle me-2 text-primary"></i>Chi tiết người dùng</h5>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary"><i class="bi bi-pencil me-1"></i>Chỉnh sửa</a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="user-avatar-lg mx-auto">{{ strtoupper(substr($user->full_name ?? $user->username, 0, 1)) }}</div>
                <h5 class="mt-3 mb-1">{{ $user->full_name ?? $user->username }}</h5>
                <div class="text-muted small">@{{ $user->username }}</div>
                @if($user->role === 'admin')
                    <span class="badge badge-soft-primary mt-2"><i class="bi bi-shield-fill me-1"></i>Admin</span>
                @else
                    <span class="badge badge-soft-info mt-2">Khách hàng</span>
                @endif

                <hr>

                <div class="text-start">
                    <div class="d-flex justify-content-between py-1"><span class="text-muted">Email:</span><span class="fw-semibold">{{ $user->email }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-muted">SĐT:</span><span class="fw-semibold">{{ $user->phone_number ?? '—' }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-muted">Tổng đơn:</span><span class="fw-semibold text-primary">{{ $user->orders_count }}</span></div>
                    <div class="d-flex justify-content-between py-1"><span class="text-muted">Ngày tạo:</span><span class="fw-semibold">{{ $user->created_at->format('d/m/Y') }}</span></div>
                </div>

                <hr>
                <div class="text-start">
                    <strong class="small d-block mb-1">Địa chỉ:</strong>
                    <p class="mb-0 small text-muted">{{ $user->address ?: 'Chưa cập nhật' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-receipt me-2"></i>Đơn hàng gần đây</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead>
                            <tr>
                                <th class="ps-3">Mã đơn</th>
                                <th>Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-3">Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="ps-3"><a href="{{ route('admin.orders.show', $order) }}" class="text-primary fw-semibold text-decoration-none">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</a></td>
                                    <td class="fw-bold">{{ number_format($order->total_amount) }}đ</td>
                                    <td>
                                        @switch($order->status)
                                            @case('pending')<span class="badge badge-soft-warning">Chờ xác nhận</span>@break
                                            @case('confirmed')<span class="badge badge-soft-info">Đã xác nhận</span>@break
                                            @case('shipping')<span class="badge badge-soft-primary">Đang giao</span>@break
                                            @case('completed')<span class="badge badge-soft-success">Hoàn thành</span>@break
                                            @case('cancelled')<span class="badge badge-soft-danger">Đã hủy</span>@break
                                        @endswitch
                                    </td>
                                    <td class="text-end pe-3 small text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-4">Người dùng này chưa có đơn hàng</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.user-avatar-lg {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 32px; font-weight: 700;
}
</style>
@endpush
@endsection
