@extends('layouts.admin')

@section('title', 'Quản lý tác giả')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-person-lines-fill me-2 text-primary"></i>Quản lý tác giả</h5>
                <small class="text-muted">Tổng cộng {{ $authors->total() }} tác giả</small>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAuthorModal">
                <i class="bi bi-plus-lg me-1"></i>Thêm tác giả
            </button>
        </div>

        <form action="{{ route('admin.authors.index') }}" method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Tìm theo tên hoặc quốc tịch..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary flex-grow-1"><i class="bi bi-funnel"></i></button>
                    @if(request('search'))
                        <a href="{{ route('admin.authors.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th>Tên tác giả</th>
                        <th>Quốc tịch</th>
                        <th>Tiểu sử</th>
                        <th class="text-center">Số sách</th>
                        <th class="text-end pe-3" width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($authors as $author)
                        <tr>
                            <td class="ps-3 text-muted">#{{ $author->id }}</td>
                            <td class="fw-semibold">{{ $author->name }}</td>
                            <td>{{ $author->nationality ?: '—' }}</td>
                            <td class="text-muted small">{{ Str::limit($author->biography, 70) ?: '—' }}</td>
                            <td class="text-center"><span class="badge badge-soft-primary">{{ $author->books_count ?? 0 }}</span></td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editAuthorModal{{ $author->id }}"><i class="bi bi-pencil"></i></button>
                                    <form action="{{ route('admin.authors.destroy', $author->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá tác giả này?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>

                                <div class="modal fade" id="editAuthorModal{{ $author->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.authors.update', $author->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Chỉnh sửa tác giả</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tên tác giả <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="name" value="{{ $author->name }}" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Quốc tịch</label>
                                                        <input type="text" class="form-control" name="nationality" value="{{ $author->nationality }}" placeholder="VD: Việt Nam">
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Tiểu sử</label>
                                                        <textarea class="form-control" name="biography" rows="3">{{ $author->biography }}</textarea>
                                                    </div>
                                                    <div class="mb-0">
                                                        <label class="form-label">Mô tả ngắn</label>
                                                        <textarea class="form-control" name="description" rows="2" maxlength="500">{{ $author->description }}</textarea>
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
                        <tr><td colspan="6" class="text-center text-muted py-4">Chưa có tác giả nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($authors->hasPages())
        <div class="card-footer bg-white">{{ $authors->links() }}</div>
    @endif
</div>

<div class="modal fade" id="addAuthorModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.authors.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Thêm tác giả mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    <div class="mb-3">
                        <label class="form-label">Tên tác giả <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="name" required placeholder="VD: Nguyễn Nhật Ánh">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quốc tịch</label>
                        <input type="text" class="form-control" name="nationality" placeholder="VD: Việt Nam">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tiểu sử</label>
                        <textarea class="form-control" name="biography" rows="3" placeholder="Tiểu sử tác giả"></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Mô tả ngắn</label>
                        <textarea class="form-control" name="description" rows="2" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm tác giả</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('addAuthorModal')).show();
    });
</script>
@endif
@endsection
