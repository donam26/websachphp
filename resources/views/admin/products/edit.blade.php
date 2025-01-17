@extends('layouts.admin')

@section('title', 'Sửa bất động sản')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Sửa bất động sản</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->code) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Thông tin cơ bản -->
                <div class="mb-4">
                    <h5 class="card-title text-primary">Thông tin cơ bản</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text"
                                class="form-control @error('title') is-invalid @enderror"
                                id="title"
                                name="title"
                                value="{{ old('title', $product->title) }}"
                                required>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger"></span></label>
                            <select class="form-select"
                                id="status"
                                name="status"
                                required>
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Đang mở</option>
                                <option value="close" {{ old('status', $product->status) == 'close' ? 'selected' : '' }}>Đóng</option>

                            </select>

                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tùy chọn</label>
                            <div class="form-check">
                                <input type="checkbox"
                                    class="form-check-input"
                                    id="is_hot"
                                    name="is_hot"
                                    value="1"
                                    {{ old('is_hot', $product->is_hot) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_hot">
                                    Bất động sản hot
                                </label>
                            </div>
                            {{-- <div class="form-check">
                                <input type="checkbox"
                                    class="form-check-input"
                                    id="show_in_web"
                                    name="show_in_web"
                                    value="1"
                                    {{ old('show_in_web', $product->show_in_web) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_web">
                                    Hiển thị trên web
                                </label>
                            </div> --}}
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tên BĐS</label>
                            <input type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name', $product->name) }}">
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="type" class="form-label">Loại BĐS</label>
                            <input type="text"
                                class="form-control @error('type') is-invalid @enderror"
                                id="type"
                                name="type"
                                value="{{ old('type', $product->type) }}">
                            @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="formality" class="form-label">Hình thức</label>
                            <input type="text"
                                class="form-control @error('formality') is-invalid @enderror"
                                id="formality"
                                name="formality"
                                value="{{ old('formality', $product->formality) }}">
                            @error('formality')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="content" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                id="content"
                                name="content"
                                rows="4">{{ old('content', $product->content) }}</textarea>
                            @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="texture" class="form-label">Kết cấu</label>
                            <textarea class="form-control @error('texture') is-invalid @enderror"
                                id="texture"
                                name="texture"
                                rows="4">{{ old('texture', $product->texture) }}</textarea>
                            @error('texture')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh và tài liệu -->
                <div class="mb-4">
                    <h5 class="card-title text-primary">Hình ảnh và tài liệu</h5>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Ảnh hiện tại</label>
                            <div class="row">
                                @forelse($product->images as $image)
                                <div class="col-md-2 mb-2">
                                    <div class="position-relative">
                                        <img src="{{ asset('storage/' . $image->path) }}"
                                            alt="Ảnh sản phẩm"
                                            class="img-fluid rounded">
                                        @if($image->is_primary)
                                        <span class="badge bg-primary mt-1">Ảnh chính</span>
                                        @endif
                                        <a href="javascript:void(0)"
                                           onclick="if(confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
                                               document.getElementById('delete-image-' + {{ $image->id }}).submit();
                                           }"
                                           class="btn btn-danger btn-sm mt-1">
                                            <i class="bi bi-trash"></i> Xóa
                                        </a>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <p class="text-muted">Chưa có ảnh</p>
                                </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="images" class="form-label">Thêm ảnh mới</label>
                            <input type="file"
                                class="form-control @error('images.*') is-invalid @enderror"
                                id="images"
                                name="images[]"
                                multiple
                                accept="image/*">
                            <small class="text-muted">Có thể chọn nhiều ảnh. Định dạng: JPG, PNG, GIF</small>
                            @error('images.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">File hiện tại</label>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Tên file</th>
                                            <th>Loại</th>
                                            <th>Kích thước</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($product->files as $file)
                                        <tr>
                                            <td>{{ $file->name }}</td>
                                            <td>{{ $file->type }}</td>
                                            <td>{{ number_format($file->size / 1024, 2) }} KB</td>
                                            <td>
                                                <a href="{{ asset('storage/' . $file->path) }}"
                                                    class="btn btn-sm btn-primary"
                                                    target="_blank">
                                                    <i class="bi bi-download"></i> Tải xuống
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Chưa có file đính kèm</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="files" class="form-label">Thêm file mới</label>
                            <input type="file"
                                class="form-control @error('files.*') is-invalid @enderror"
                                id="files"
                                name="files[]"
                                multiple>
                            <small class="text-muted">Có thể chọn nhiều file. Định dạng: PDF, DOC, DOCX, XLS, XLSX</small>
                            @error('files.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Thông tin địa chỉ -->
                <div class="mb-4">
                    <h5 class="card-title text-primary">Địa chỉ</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="province_id" class="form-label">Tỉnh/Thành phố</label>
                            <input type="text"
                                class="form-control @error('province_id') is-invalid @enderror"
                                id="province_id"
                                name="province_id"
                                value="{{ old('province_id', $product->province_id) }}">
                            @error('province_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="district_id" class="form-label">Quận/Huyện</label>
                            <input type="text"
                                class="form-control @error('district_id') is-invalid @enderror"
                                id="district_id"
                                name="district_id"
                                value="{{ old('district_id', $product->district_id) }}">
                            @error('district_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="ward_id" class="form-label">Phường/Xã</label>
                            <input type="text"
                                class="form-control @error('ward_id') is-invalid @enderror"
                                id="ward_id"
                                name="ward_id"
                                value="{{ old('ward_id', $product->ward_id) }}">
                            @error('ward_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="street" class="form-label">Đường/Phố</label>
                            <input type="text"
                                class="form-control @error('street') is-invalid @enderror"
                                id="street"
                                name="street"
                                value="{{ old('street', $product->street) }}">
                            @error('street')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="house_number" class="form-label">Số nhà</label>
                            <input type="text"
                                class="form-control @error('house_number') is-invalid @enderror"
                                id="house_number"
                                name="house_number"
                                value="{{ old('house_number', $product->house_number) }}">
                            @error('house_number')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Thông tin giá và diện tích -->
                <div class="mb-4">
                    <h5 class="card-title text-primary">Giá và diện tích</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="price" class="form-label">Giá</label>
                            <input type="number"
                                class="form-control @error('price') is-invalid @enderror"
                                id="price"
                                name="price"
                                value="{{ old('price', $product->price) }}"
                                step="0.01">
                            @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="currency" class="form-label">Đơn vị tiền tệ</label>
                            <input type="text"
                                class="form-control @error('currency') is-invalid @enderror"
                                id="currency"
                                name="currency"
                                value="{{ old('currency', $product->currency ?? 'VNĐ') }}">
                            @error('currency')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="width" class="form-label">Chiều rộng (m)</label>
                            <input type="number"
                                class="form-control @error('width') is-invalid @enderror"
                                id="width"
                                name="width"
                                value="{{ old('width', $product->width) }}"
                                step="0.01">
                            @error('width')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="length" class="form-label">Chiều dài (m)</label>
                            <input type="number"
                                class="form-control @error('length') is-invalid @enderror"
                                id="length"
                                name="length"
                                value="{{ old('length', $product->length) }}"
                                step="0.01">
                            @error('length')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="acreage" class="form-label">Diện tích (m²)</label>
                            <input type="number"
                                class="form-control @error('acreage') is-invalid @enderror"
                                id="acreage"
                                name="acreage"
                                value="{{ old('acreage', $product->acreage) }}"
                                step="0.01">
                            @error('acreage')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Thông tin chủ nhà -->
                <div class="mb-4">
                    <h5 class="card-title text-primary">Thông tin chủ nhà</h5>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="host_name" class="form-label">Tên chủ nhà</label>
                            <input type="text"
                                class="form-control @error('host_name') is-invalid @enderror"
                                id="host_name"
                                name="host_name"
                                value="{{ old('host_name', $product->host_name) }}">
                            @error('host_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="host_phone1" class="form-label">Số điện thoại 1</label>
                            <input type="text"
                                class="form-control @error('host_phone1') is-invalid @enderror"
                                id="host_phone1"
                                name="host_phone1"
                                value="{{ old('host_phone1', $product->host_phone1) }}">
                            @error('host_phone1')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="host_phone2" class="form-label">Số điện thoại 2</label>
                            <input type="text"
                                class="form-control @error('host_phone2') is-invalid @enderror"
                                id="host_phone2"
                                name="host_phone2"
                                value="{{ old('host_phone2', $product->host_phone2) }}">
                            @error('host_phone2')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Nút submit -->
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach($product->images as $image)
<form id="delete-image-{{ $image->id }}"
      action="{{ route('admin.products.deleteImage', $image->id) }}"
      method="POST"
      style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endforeach
@endsection
