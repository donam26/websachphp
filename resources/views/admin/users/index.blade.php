@extends('layouts.admin')

@section('title', 'Quản lý người dùng')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-people me-2 text-primary"></i>Quản lý người dùng</h5>
                <small class="text-muted">Tổng cộng {{ $users->total() }} người dùng</small>
            </div>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUser">
                <i class="bi bi-plus-lg me-1"></i>Thêm người dùng
            </button>
        </div>

        <form action="{{ route('admin.users.index') }}" method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên, email, username..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select name="role" class="form-select">
                        <option value="">Tất cả vai trò</option>
                        <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                        <option value="user" {{ request('role')=='user'?'selected':'' }}>Khách hàng</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-funnel me-1"></i>Lọc</button>
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
                        <th class="ps-3" width="60">ID</th>
                        <th>Người dùng</th>
                        <th>Liên hệ</th>
                        <th>Vai trò</th>
                        <th>Ngày tạo</th>
                        <th class="text-end pe-3" width="160">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-3 text-muted">#{{ $user->id }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="user-avatar-sm">{{ strtoupper(substr($user->full_name ?? $user->username, 0, 1)) }}</div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->full_name ?? $user->username }}</div>
                                        <small class="text-muted">@{{ $user->username }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small"><i class="bi bi-envelope me-1 text-muted"></i>{{ $user->email }}</div>
                                <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $user->phone_number ?? '—' }}</small>
                            </td>
                            <td>
                                @if($user->role === 'admin')
                                    <span class="badge badge-soft-primary"><i class="bi bi-shield-fill me-1"></i>Admin</span>
                                @else
                                    <span class="badge badge-soft-info">Khách hàng</span>
                                @endif
                            </td>
                            <td><small class="text-muted">{{ $user->created_at->format('d/m/Y') }}</small></td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-secondary"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    @if($user->role !== 'admin')
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa người dùng này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Chưa có người dùng nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
        <div class="card-footer bg-white">{{ $users->appends(request()->query())->links() }}</div>
    @endif
</div>

{{-- Add user modal --}}
<div class="modal fade" id="addUser" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-person-plus me-2"></i>Thêm người dùng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Tên đăng nhập</label><input type="text" name="username" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Họ tên</label><input type="text" name="full_name" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label">Số điện thoại</label><input type="text" name="phone_number" class="form-control"></div>
                        <div class="col-12"><label class="form-label">Địa chỉ</label><input type="text" name="address" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label">Mật khẩu</label><input type="password" name="password" class="form-control" required></div>
                        <div class="col-md-6">
                            <label class="form-label">Vai trò</label>
                            <select name="role" class="form-select">
                                <option value="user">Khách hàng</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm người dùng</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.user-avatar-sm {
    width: 36px; height: 36px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
}
</style>
@endpush
@endsection
