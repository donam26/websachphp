@extends('layouts.admin')

@section('title', 'Quan ly danh muc')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1 style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--ad-primary);margin:0;">
        Quan ly danh muc
    </h1>
    <button type="button" class="ad-btn ad-btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
        <i class="bi bi-plus-lg"></i> Them danh muc
    </button>
</div>

<div class="ad-card">
    <div style="overflow-x:auto;">
        <table class="ad-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ten danh muc</th>
                    <th>Slug</th>
                    <th>Mo ta</th>
                    <th>Icon</th>
                    <th>So san pham</th>
                    <th>Thao tac</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td style="color:var(--ad-muted);">#{{ $category->id }}</td>
                    <td style="font-weight:600;">{{ $category->name }}</td>
                    <td><code style="background:var(--ad-surface);padding:3px 8px;border-radius:6px;font-size:0.8rem;">{{ $category->slug }}</code></td>
                    <td style="font-size:0.85rem;color:var(--ad-muted);">{{ Str::limit($category->description, 40) }}</td>
                    <td>
                        @if($category->icon)
                            <span style="width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;background:var(--ad-surface);border-radius:8px;">
                                <i class="{{ $category->icon }}"></i>
                            </span>
                        @else -
                        @endif
                    </td>
                    <td>
                        <span class="ad-badge ad-badge-info">{{ $category->books_count }} san pham</span>
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            <button type="button" class="ad-btn ad-btn-outline ad-btn-icon ad-btn-sm"
                                    data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="ad-btn ad-btn-danger ad-btn-icon ad-btn-sm"
                                    onclick="confirmDelete({{ $category->id }})">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>

                        <form id="delete-form-{{ $category->id }}"
                              action="{{ route('admin.categories.destroy', $category->id) }}"
                              method="POST" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">Chinh sua danh muc</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Ten danh muc</label>
                                                <input type="text" class="form-control" name="name"
                                                       value="{{ old('name', $category->name) }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Mo ta</label>
                                                <textarea class="form-control" name="description" rows="3">{{ old('description', $category->description) }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Icon (class)</label>
                                                <input type="text" class="form-control" name="icon"
                                                       value="{{ old('icon', $category->icon) }}">
                                                <small style="color:var(--ad-muted);">VD: bi bi-handbag</small>
                                            </div>
                                        </div>
                                        <div class="modal-footer" style="border-top:1px solid var(--ad-border);">
                                            <button type="button" class="ad-btn ad-btn-outline" data-bs-dismiss="modal">Dong</button>
                                            <button type="submit" class="ad-btn ad-btn-primary">Luu thay doi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:var(--ad-muted);">
                        <i class="bi bi-folder-x" style="font-size:2rem;display:block;margin-bottom:0.5rem;"></i>
                        Chua co danh muc nao
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Them danh muc moi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Ten danh muc</label>
                        <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mo ta</label>
                        <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Icon (class)</label>
                        <input type="text" class="form-control" name="icon" value="{{ old('icon') }}">
                        <small style="color:var(--ad-muted);">VD: bi bi-handbag</small>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--ad-border);">
                    <button type="button" class="ad-btn ad-btn-outline" data-bs-dismiss="modal">Dong</button>
                    <button type="submit" class="ad-btn ad-btn-primary">Them danh muc</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Ban co chac chan muon xoa danh muc nay?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush
