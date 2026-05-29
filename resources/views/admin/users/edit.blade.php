@extends('layouts.admin')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Chỉnh sửa: {{ $user->full_name ?? $user->username }}</h5>
</div>

<form action="{{ route('admin.users.update', $user) }}" method="POST">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i>Thông tin tài khoản</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                   name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                                   name="full_name" value="{{ old('full_name', $user->full_name) }}" required>
                            @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="phone_number"
                                   value="{{ old('phone_number', $user->phone_number) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header"><i class="bi bi-shield-lock me-2"></i>Mật khẩu</div>
                <div class="card-body">
                    <label class="form-label">Đặt mật khẩu mới (tùy chọn)</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" placeholder="Để trống nếu không thay đổi">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-person-badge me-2"></i>Phân quyền</div>
                <div class="card-body">
                    <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                    <select name="role" class="form-select mb-3" required>
                        <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>Khách hàng</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin / Nhân viên</option>
                    </select>
                    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                    <select name="status" class="form-select" required>
                        @foreach(\App\Models\User::statusOptions() as $val => $label)
                            <option value="{{ $val }}" {{ old('status', $user->status ?? 'active') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary flex-fill">Hủy</a>
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-check2 me-1"></i>Cập nhật</button>
            </div>
        </div>
    </div>
</form>
@endsection
