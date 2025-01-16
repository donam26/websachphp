@extends('layouts.admin')

@section('title', 'Chỉnh sửa bất động sản')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chỉnh sửa bất động sản</h1>
        <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.customer.update', $customer) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Thông tin cơ bản -->
                    <div class="col-md-12 mb-4">
                        <h5 class="card-title text-primary">Thông tin cơ bản</h5>
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="host_name" class="form-label">Tên khách hàng<span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('host_name') is-invalid @enderror"
                                       id="host_name"
                                       name="host_name"
                                       value="{{ old('name', $customer->host_name) }}"
                                       required>
                                @error('host_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="type" class="form-label">Loại BĐS</label>
                                <select class="form-select @error('type') is-invalid @enderror"
                                        id="type"
                                        name="type">
                                    <option value="">Chọn loại BĐS</option>
                                    <option value="Căn hộ" {{ old('type', $customer->type) == 'Căn hộ' ? 'selected' : '' }}>Căn hộ</option>
                                    <option value="Nhà riêng" {{ old('type', $customer->type) == 'Nhà riêng' ? 'selected' : '' }}>Nhà riêng</option>
                                    <option value="Biệt thự" {{ old('type', $customer->type) == 'Biệt thự' ? 'selected' : '' }}>Biệt thự</option>
                                    <option value="Nhà phố" {{ old('type', $customer->type) == 'Nhà phố' ? 'selected' : '' }}>Nhà phố</option>
                                    <option value="Đất nền" {{ old('type', $customer->type) == 'Đất nền' ? 'selected' : '' }}>Đất nền</option>
                                    <option value="Văn phòng" {{ old('type', $customer->type) == 'Văn phòng' ? 'selected' : '' }}>Văn phòng</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="formality" class="form-label">Hình thức</label>
                                <select class="form-select @error('formality') is-invalid @enderror"
                                        id="formality"
                                        name="formality">
                                    <option value="">Chọn hình thức</option>
                                    <option value="Bán" {{ old('formality', $customer->formality) == 'Bán' ? 'selected' : '' }}>Bán</option>
                                    <option value="Cho thuê" {{ old('formality', $customer->formality) == 'Cho thuê' ? 'selected' : '' }}>Cho thuê</option>
                                </select>
                                @error('formality')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                        </div>
                    </div>

                    <!-- Thông tin địa chỉ -->
                    <div class="col-md-12 mb-4">
                        <h5 class="card-title text-primary">Địa chỉ</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="province_id" class="form-label">Tỉnh/Thành phố</label>
                                <select class="form-select @error('province_id') is-invalid @enderror"
                                        id="province_id"
                                        name="province_id">
                                    <option value="">Chọn tỉnh/thành phố</option>
                                </select>
                                <input type="hidden" name="province_name" id="province_name" value="{{ old('province_name', $customer->province_name) }}">
                                @error('province_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="district_id" class="form-label">Quận/Huyện</label>
                                <select class="form-select @error('district_id') is-invalid @enderror"
                                        id="district_id"
                                        name="district_name">
                                    <option value="">Chọn quận/huyện</option>
                                </select>
                                <input type="hidden" name="district_name" id="district_name"
                                value="{{ old('district_name', $customer->district_name) }}">
                                @error('district_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ward_id" class="form-label">Phường/Xã</label>
                                <select class="form-select @error('ward_id') is-invalid @enderror"
                                        id="ward_id"
                                        name="ward_id">
                                    <option value="">Chọn phường/xã</option>
                                </select>
                                <input type="hidden" name="ward_name" id="ward_name" value="{{ old('ward_name', $customer->ward_name) }}">
                                @error('ward_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="street" class="form-label">Đường/Phố</label>
                                <input type="text"
                                       class="form-control @error('street') is-invalid @enderror"
                                       id="street"
                                       name="street"
                                       value="{{ old('street', $customer->street) }}">
                                @error('street')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="house_number" class="form-label">Số nhà</label>
                                <input type="text"
                                       class="form-control @error('house_number') is-invalid @enderror"
                                       id="house_number"
                                       name="house_number"
                                       value="{{ old('house_number', $customer->house_number) }}">
                                @error('house_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin giá và diện tích -->
                    <div class="col-md-12 mb-4">
                        <h5 class="card-title text-primary">Giá và diện tích</h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="price" class="form-label">Giá</label>
                                <div class="input-group">
                                    <input type="number"
                                           class="form-control @error('price') is-invalid @enderror"
                                           id="price"
                                           name="price"
                                           value="{{ old('price', $customer->price) }}"
                                           min="0"
                                           step="1000000">
                                    <select class="form-select @error('currency') is-invalid @enderror"
                                            name="currency">
                                        <option value="VNĐ" {{ old('currency', $customer->currency) == 'VNĐ' ? 'selected' : '' }}>VNĐ</option>
                                        <option value="USD" {{ old('currency', $customer->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                    </select>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="acreage" class="form-label">Diện tích (m²)</label>
                                <input type="number"
                                       class="form-control @error('acreage') is-invalid @enderror"
                                       id="acreage"
                                       name="acreage"
                                       value="{{ old('acreage', $customer->acreage) }}"
                                       min="0"
                                       step="0.1">
                                @error('acreage')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="width" class="form-label">Chiều rộng (m)</label>
                                <input type="number"
                                       class="form-control @error('width') is-invalid @enderror"
                                       id="width"
                                       name="width"
                                       value="{{ old('width', $customer->width) }}"
                                       min="0"
                                       step="0.1">
                                @error('width')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="length" class="form-label">Chiều dài (m)</label>
                                <input type="number"
                                       class="form-control @error('length') is-invalid @enderror"
                                       id="length"
                                       name="length"
                                       value="{{ old('length', $customer->length) }}"
                                       min="0"
                                       step="0.1">
                                @error('length')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Thông tin chi tiết -->


                    <!-- Thông tin chủ nhà -->
                    <div class="col-md-12 mb-4">
                        <h5 class="card-title text-primary">Thông tin liên hệ </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="host_name" class="form-label">Tên chủ nhà</label>
                                <input type="text"
                                       class="form-control @error('host_name') is-invalid @enderror"
                                       id="host_name"
                                       name="host_name"
                                       value="{{ old('host_name', $customer->host_name) }}">
                                @error('host_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="host_phone1" class="form-label">Số điện thoại 1</label>
                                <input type="text"
                                       class="form-control @error('host_phone1') is-invalid @enderror"
                                       id="host_phone1"
                                       name="host_phone1"
                                       value="{{ old('host_phone1', $customer->host_phone1) }}">
                                @error('host_phone1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="host_phone2" class="form-label">Số điện thoại 2</label>
                                <input type="text"
                                       class="form-control @error('host_phone2') is-invalid @enderror"
                                       id="host_phone2"
                                       name="host_phone2"
                                       value="{{ old('host_phone2', $customer->host_phone2) }}">
                                @error('host_phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Trạng thái -->

                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i>Lưu thay đổi
                    </button>
                    <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">
                        <i class="bi bi-x-circle me-1"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Load tỉnh/thành phố khi trang được load
        loadProvinces();

        // Sự kiện khi chọn tỉnh/thành phố
        $('#province_id').change(function() {
            const provinceId = $(this).val();
            const provinceName = $(this).find('option:selected').text();
            $('#province_name').val(provinceName);

            if (provinceId) {
                loadDistricts(provinceId);
                $('#ward_id').html('<option value="">Chọn phường/xã</option>');
                $('#district_name').val('');
                $('#ward_name').val('');
            } else {
                $('#district_id').html('<option value="">Chọn quận/huyện</option>');
                $('#ward_id').html('<option value="">Chọn phường/xã</option>');
                $('#province_name').val('');
                $('#district_name').val('');
                $('#ward_name').val('');
            }
        });

        // Sự kiện khi chọn quận/huyện
        $('#district_id').change(function() {
            const districtId = $(this).val();
            const districtName = $(this).find('option:selected').text();
            $('#district_name').val(districtName);

            if (districtId) {
                loadWards(districtId);
                $('#ward_name').val('');
            } else {
                $('#ward_id').html('<option value="">Chọn phường/xã</option>');
                $('#district_name').val('');
                $('#ward_name').val('');
            }
        });

        // Sự kiện khi chọn phường/xã
        $('#ward_id').change(function() {
            const wardName = $(this).find('option:selected').text();
            $('#ward_name').val(wardName);
        });

        // Hàm load tỉnh/thành phố
        function loadProvinces() {
            $.ajax({
                url: 'https://provinces.open-api.vn/api/p/',
                type: 'GET',
                success: function(data) {
                    let html = '<option value="">Chọn tỉnh/thành phố</option>';
                    data.forEach(function(province) {
                        const selected = province.code == '{{ old('province_id', $customer->province_id) }}' ? 'selected' : '';
                        html += `<option value="${province.code}" ${selected}>${province.name}</option>`;
                    });
                    $('#province_id').html(html);

                    // Nếu có province_id, load districts
                    const provinceId = $('#province_id').val();
                    if (provinceId) {
                        loadDistricts(provinceId);
                    }
                },
                error: function() {
                    alert('Không thể tải danh sách tỉnh/thành phố');
                }
            });
        }

        // Hàm load quận/huyện
        function loadDistricts(provinceId) {
            $.ajax({
                url: `https://provinces.open-api.vn/api/p/${provinceId}?depth=2`,
                type: 'GET',
                success: function(data) {
                    let html = '<option value="">Chọn quận/huyện</option>';
                    data.districts.forEach(function(district) {
                        const selected = district.code == '{{ old('district_id', $customer->district_id) }}' ? 'selected' : '';
                        html += `<option value="${district.code}" ${selected}>${district.name}</option>`;
                    });
                    $('#district_id').html(html);

                    // Nếu có district_id, load wards
                    const districtId = $('#district_id').val();
                    if (districtId) {
                        loadWards(districtId);
                    }
                },
                error: function() {
                    alert('Không thể tải danh sách quận/huyện');
                }
            });
        }

        // Hàm load phường/xã
        function loadWards(districtId) {
            $.ajax({
                url: `https://provinces.open-api.vn/api/d/${districtId}?depth=2`,
                type: 'GET',
                success: function(data) {
                    let html = '<option value="">Chọn phường/xã</option>';
                    data.wards.forEach(function(ward) {
                        const selected = ward.code == '{{ old('ward_id', $customer->ward_id) }}' ? 'selected' : '';
                        html += `<option value="${ward.code}" ${selected}>${ward.name}</option>`;
                    });
                    $('#ward_id').html(html);
                },
                error: function() {
                    alert('Không thể tải danh sách phường/xã');
                }
            });
        }
    });
</script>
@endpush
