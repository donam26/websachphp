@extends('layouts.admin')

@section('title', 'Thêm bất động sản mới')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Thêm bất động sản mới</h1>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

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
                                   value="{{ old('title') }}" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="sold" {{ old('status') == 'sold' ? 'selected' : '' }}>Đã bán</option>
                                <option value="rented" {{ old('status') == 'rented' ? 'selected' : '' }}>Đã cho thuê</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tùy chọn</label>
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_hot" 
                                       name="is_hot" 
                                       value="1" 
                                       {{ old('is_hot') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_hot">
                                    Tin nổi bật
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="show_in_web" 
                                       name="show_in_web" 
                                       value="1" 
                                       {{ old('show_in_web', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_in_web">
                                    Hiển thị trên web
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Tên BĐS</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}">
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
                                   value="{{ old('type') }}">
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
                                   value="{{ old('formality') }}">
                            @error('formality')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="content" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" 
                                      name="content" 
                                      rows="4">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Hình ảnh và tài liệu -->
                <div class="mb-4">
                    <h5 class="card-title text-primary">Hình ảnh và tài liệu</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="images" class="form-label">Hình ảnh</label>
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

                        <div class="col-md-6 mb-3">
                            <label for="files" class="form-label">Tài liệu đính kèm</label>
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
                                   value="{{ old('province_id') }}">
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
                                   value="{{ old('district_id') }}">
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
                                   value="{{ old('ward_id') }}">
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
                                   value="{{ old('street') }}">
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
                                   value="{{ old('house_number') }}">
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
                                   value="{{ old('price') }}" 
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
                                   value="{{ old('currency', 'VNĐ') }}">
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
                                   value="{{ old('width') }}" 
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
                                   value="{{ old('length') }}" 
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
                                   value="{{ old('acreage') }}" 
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
                                   value="{{ old('host_name') }}">
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
                                   value="{{ old('host_phone1') }}">
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
                                   value="{{ old('host_phone2') }}">
                            @error('host_phone2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
