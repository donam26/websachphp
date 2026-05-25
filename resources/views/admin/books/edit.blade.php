@extends('layouts.admin')

@section('title', 'Sửa sách')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.books.index') }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Chỉnh sửa sách: {{ $book->title }}</h5>
</div>

<form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header"><i class="bi bi-info-circle me-2"></i>Thông tin sách</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Tên sách <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $book->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tác giả <span class="text-danger">*</span></label>
                        <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                               value="{{ old('author', $book->author) }}" required>
                        @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                        <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror" required>{{ old('description', $book->description) }}</textarea>
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
                        <img src="{{ $book->image_url }}"
                             class="img-fluid" onerror="this.src='https://placehold.co/300x400/f4f6f8/c92127?text=Book'">
                    </div>
                    <input type="file" name="image" id="imageInput" class="form-control mt-2 @error('image') is-invalid @enderror" accept="image/*" onchange="previewImg(event)">
                    <small class="text-muted">Để trống nếu không thay đổi</small>
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><i class="bi bi-tag me-2"></i>Phân loại & Giá</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Danh mục <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá bán (VND) <span class="text-danger">*</span></label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price', $book->price) }}" min="0" step="1000" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giá gốc / Giá so sánh (VND)</label>
                        <input type="number" name="compare_price" class="form-control @error('compare_price') is-invalid @enderror"
                               value="{{ old('compare_price', $book->compare_price) }}" min="0" step="1000" placeholder="Để trống nếu không hiển thị giá gạch ngang">
                        <small class="text-muted">Phải ≥ giá bán. Hệ thống tự tính % giảm.</small>
                        @error('compare_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số lượng kho <span class="text-danger">*</span></label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity', $book->quantity) }}" min="0" required>
                        @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="available" {{ old('status', $book->status) === 'available' ? 'selected' : '' }}>Còn hàng</option>
                            <option value="unavailable" {{ old('status', $book->status) === 'unavailable' ? 'selected' : '' }}>Hết hàng</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.books.index') }}" class="btn btn-outline-secondary flex-fill">Hủy</a>
                <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-check2 me-1"></i>Cập nhật</button>
            </div>
        </div>
    </div>
</form>

@push('styles')
<style>
.img-preview-wrap {
    border: 2px dashed var(--border);
    border-radius: 10px;
    padding: 16px;
    text-align: center;
    background: #fafafa;
}
.img-preview-wrap img { max-height: 240px; border-radius: 6px; }
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
