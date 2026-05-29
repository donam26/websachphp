@extends('layouts.admin')

@section('title', 'Thêm sách mới')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0"><i class="bi bi-plus-circle me-2 text-primary"></i>Thêm sách mới</h5>
</div>

<form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i>Thông tin sách</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tên sách <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="VD: Đắc nhân tâm" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tác giả <span class="text-danger">*</span></label>
                        <select name="author_ids[]" class="form-select @error('author_ids') is-invalid @enderror" multiple size="5" required>
                            @foreach($authors as $author)
                                <option value="{{ $author->id }}" {{ collect(old('author_ids'))->contains($author->id) ? 'selected' : '' }}>{{ $author->name }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Giữ Ctrl (⌘ trên Mac) để chọn nhiều tác giả. Chưa có tác giả? <a href="{{ route('admin.authors.index') }}" target="_blank">Thêm tác giả »</a></small>
                        @error('author_ids')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        @error('author_ids.*')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-7">
                            <label class="form-label">ISBN</label>
                            <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
                                   value="{{ old('isbn') }}" placeholder="VD: 978-604-1-12345-6">
                            @error('isbn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Năm xuất bản</label>
                            <input type="number" name="publish_year" class="form-control @error('publish_year') is-invalid @enderror"
                                   value="{{ old('publish_year') }}" min="1000" max="{{ date('Y') + 1 }}" placeholder="VD: 2024">
                            @error('publish_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                        <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Mô tả chi tiết về cuốn sách..." required>{{ old('description') }}</textarea>
                        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-image me-2"></i>Ảnh bìa</div>
                <div class="card-body">
                    <div class="img-preview-wrap" id="imgPreviewWrap">
                        <i class="bi bi-cloud-arrow-up"></i>
                        <p class="text-muted small mb-0">Click hoặc kéo thả ảnh vào đây</p>
                        <small class="text-muted">JPG, PNG, max 2MB</small>
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control mt-2 @error('image') is-invalid @enderror" accept="image/*" required onchange="previewImg(event)">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-tag me-2"></i>Phân loại & Giá</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá bán (VND) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" min="0" step="1000" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá gốc / Giá so sánh (VND)</label>
                        <input type="number" name="compare_price" class="form-control @error('compare_price') is-invalid @enderror"
                               value="{{ old('compare_price') }}" min="0" step="1000" placeholder="Để trống nếu không hiển thị giá gạch ngang">
                        <small class="text-muted">Phải ≥ giá bán. Hệ thống tự tính % giảm.</small>
                        @error('compare_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng kho <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', 0) }}" min="0" required>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary flex-fill">Hủy</a>
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-check2 me-1"></i>Lưu sách</button>
            </div>
        </div>
    </div>
</form>

@push('styles')
<style>
.img-preview-wrap {
    border: 2px dashed var(--border);
    border-radius: 10px;
    padding: 32px 16px;
    text-align: center;
    background: #fafafa;
}
.img-preview-wrap i { font-size: 48px; color: #cbd5e1; display: block; margin-bottom: 8px; }
.img-preview-wrap img { max-height: 200px; border-radius: 6px; }
</style>
@endpush

@push('scripts')
<script>
function previewImg(e) {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
        document.getElementById('imgPreviewWrap').innerHTML = `<img src="${ev.target.result}" class="img-fluid">`;
    };
    reader.readAsDataURL(file);
}
</script>
@endpush
@endsection
