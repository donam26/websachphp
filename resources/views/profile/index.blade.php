@extends('layouts.app')

@section('title', 'Thông tin cá nhân')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item active">Tài khoản của tôi</li>
    </ol>
</nav>

<div class="row g-3">
    <aside class="col-lg-3">
        <div class="card">
            <div class="card-body text-center pb-2">
                <div class="user-avatar-lg">{{ strtoupper(substr($user->full_name ?? $user->username, 0, 1)) }}</div>
                <h6 class="mt-3 mb-0">{{ $user->full_name ?? $user->username }}</h6>
                <small class="text-muted">{{ $user->email }}</small>
            </div>
            <hr class="my-2">
            <div class="account-menu">
                <a href="{{ route('profile') }}" class="account-link active">
                    <i class="bi bi-person"></i>Thông tin tài khoản
                </a>
                <a href="{{ route('orders.index') }}" class="account-link">
                    <i class="bi bi-receipt"></i>Đơn hàng của tôi
                </a>
                <a href="{{ route('cart.index') }}" class="account-link">
                    <i class="bi bi-cart3"></i>Giỏ hàng
                </a>
                @if($user->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="account-link">
                    <i class="bi bi-speedometer2"></i>Trang quản trị
                </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="m-0">@csrf
                    <button type="submit" class="account-link text-danger w-100 text-start border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right"></i>Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <div class="col-lg-9">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="mb-1"><i class="bi bi-person-circle me-2 text-primary"></i>Thông tin cá nhân</h5>
                <p class="text-muted small mb-4">Cập nhật thông tin tài khoản và mật khẩu của bạn</p>

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Tên đăng nhập</label>
                            <input type="text" class="form-control" value="{{ $user->username }}" disabled>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                   name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                            @error('full_name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                                   name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                            @error('phone_number')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label small fw-semibold">Địa chỉ <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('address') is-invalid @enderror"
                                      name="address" rows="2" required>{{ old('address', $user->address) }}</textarea>
                            @error('address')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3"><i class="bi bi-shield-lock me-2 text-primary"></i>Đổi mật khẩu</h6>
                    <p class="text-muted small mb-3">Để trống nếu không muốn đổi mật khẩu</p>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Mật khẩu hiện tại</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                   name="current_password" placeholder="••••••••">
                            @error('current_password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Mật khẩu mới</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" placeholder="Tối thiểu 8 ký tự">
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Xác nhận mật khẩu mới</label>
                            <input type="password" class="form-control" name="password_confirmation" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check2 me-1"></i>Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if($recentOrders->count() > 0)
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span><i class="bi bi-receipt me-2 text-primary"></i>Đơn hàng gần đây</span>
                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0 align-middle">
                        <thead style="background:#f9fafb;">
                            <tr>
                                <th class="ps-3">Mã đơn</th>
                                <th>Ngày đặt</th>
                                <th class="text-end">Tổng tiền</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-3"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                                <tr>
                                    <td class="ps-3"><strong class="text-primary">{{ $order->code }}</strong></td>
                                    <td><small>{{ $order->created_at->format('d/m/Y H:i') }}</small></td>
                                    <td class="text-end fw-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}đ</td>
                                    <td><span class="badge badge-soft-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                                    <td class="text-end pe-3">
                                        <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-outline-secondary">Chi tiết</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.user-avatar-lg {
    width: 80px; height: 80px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    font-weight: 700;
    margin: 12px auto 0;
}
.account-menu { padding: 8px 0 12px; }
.account-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 18px;
    color: var(--text-dark);
    font-size: 14px;
    font-weight: 500;
    border-left: 3px solid transparent;
    transition: .2s;
}
.account-link:hover { background: var(--bg-soft); color: var(--primary); }
.account-link.active { background: rgba(201,33,39,.08); color: var(--primary); border-left-color: var(--primary); }
.account-link i { font-size: 17px; width: 20px; }
.badge-soft-success { background: rgba(16,185,129,.12); color: #047857; }
.badge-soft-danger { background: rgba(239,68,68,.12); color: #b91c1c; }
.badge-soft-warning { background: rgba(245,158,11,.12); color: #b45309; }
.badge-soft-info { background: rgba(59,130,246,.12); color: #1d4ed8; }
.badge-soft-primary { background: rgba(201,33,39,.12); color: var(--primary); }
</style>
@endpush
@endsection
