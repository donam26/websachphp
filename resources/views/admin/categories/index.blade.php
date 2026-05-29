@extends('layouts.admin')

@section('title', 'Quản lý danh mục')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-tags me-2 text-primary"></i>Quản lý danh mục</h5>
                <small class="text-muted">Tổng cộng {{ $categories->count() }} danh mục</small>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="bi bi-plus-lg me-1"></i>Thêm danh mục
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-3" width="60">ID</th>
                        <th width="80">Icon</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th class="text-center">Số sách</th>
                        <th class="text-end pe-3" width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td class="ps-3 text-muted">#{{ $category->id }}</td>
                            <td>
                                <div class="category-icon-circle">
                                    <i class="{{ $category->icon ?? 'bi bi-bookmark' }}"></i>
                                </div>
                            </td>
                            <td class="fw-semibold">{{ $category->name }}</td>
                            <td><code class="small">{{ $category->slug }}</code></td>
                            <td class="text-muted small">{{ Str::limit($category->description, 60) }}</td>
                            <td class="text-center"><span class="badge badge-soft-primary">{{ $category->books_count ?? 0 }}</span></td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xóa danh mục này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>

                                <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Chỉnh sửa danh mục</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tên danh mục</label>
                                                        <input type="text" class="form-control" name="name" value="{{ $category->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Mô tả</label>
                                                        <textarea class="form-control" name="description" rows="3">{{ $category->description }}</textarea>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label">Icon (Bootstrap Icons class)</label>
                                                        <input type="text" class="form-control" name="icon" value="{{ $category->icon }}" placeholder="bi bi-book">
                                                    </div>
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
                        <tr><td colspan="7" class="text-center text-muted py-4">Chưa có danh mục nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="VD: Văn học">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Mô tả ngắn về danh mục"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Icon (Bootstrap Icons class)</label>
                        <input type="text" class="form-control" name="icon" placeholder="VD: bi bi-book">
                        <small class="text-muted">Tham khảo icon tại: icons.getbootstrap.com</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm danh mục</button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.category-icon-circle {
    width: 40px; height: 40px;
    background: rgba(79,70,229,.08);
    color: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}
</style>
@endpush
@endsection
