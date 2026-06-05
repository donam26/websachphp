@extends('layouts.admin')

@section('title', 'Quản lý nhà cung cấp')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-truck me-2 text-primary"></i>Quản lý nhà cung cấp</h5>
                <small class="text-muted">Tổng cộng {{ $suppliers->total() }} nhà cung cấp</small>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                <i class="bi bi-plus-lg me-1"></i>Thêm nhà cung cấp
            </button>
        </div>

        <form action="{{ route('admin.suppliers.index') }}" method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên, SĐT, email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Đang hợp tác</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Ngừng hợp tác</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1"><i class="bi bi-funnel"></i></button>
                    @if(request()->hasAny(['search', 'status']))
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th class="ps-3" width="60">ID</th>
                        <th>Nhà cung cấp</th>
                        <th>Liên hệ</th>
                        <th>Địa chỉ</th>
                        <th class="text-center">Số phiếu nhập</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3" width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                        <tr>
                            <td class="ps-3 text-muted">#{{ $supplier->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $supplier->name }}</div>
                                @if($supplier->note)
                                    <small class="text-muted">{{ Str::limit($supplier->note, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($supplier->phone)<div class="small"><i class="bi bi-telephone me-1 text-muted"></i>{{ $supplier->phone }}</div>@endif
                                @if($supplier->email)<div class="small"><i class="bi bi-envelope me-1 text-muted"></i>{{ $supplier->email }}</div>@endif
                                @if(!$supplier->phone && !$supplier->email)<span class="text-muted">—</span>@endif
                            </td>
                            <td class="text-muted small">{{ $supplier->address ?: '—' }}</td>
                            <td class="text-center"><span class="badge badge-soft-primary">{{ $supplier->stock_imports_count ?? 0 }}</span></td>
                            <td class="text-center">
                                @if($supplier->is_active)
                                    <span class="badge badge-soft-success">Đang hợp tác</span>
                                @else
                                    <span class="badge badge-soft-secondary">Ngừng hợp tác</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editSupplierModal{{ $supplier->id }}"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá nhà cung cấp này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>

                                <div class="modal fade" id="editSupplierModal{{ $supplier->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.suppliers.update', $supplier->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Chỉnh sửa nhà cung cấp</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @include('admin.suppliers._form', ['supplier' => $supplier])
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Chưa có nhà cung cấp nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($suppliers->hasPages())
        <div class="card-footer bg-white">{{ $suppliers->links() }}</div>
    @endif
</div>

<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.suppliers.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Thêm nhà cung cấp mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    @include('admin.suppliers._form', ['supplier' => null])
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm nhà cung cấp</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('addSupplierModal')).show();
    });
</script>
@endif
@endsection
