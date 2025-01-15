@extends('layouts.admin')

@section('title', 'Quản lý bất động sản')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý bất động sản</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm thuê
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET" id="filter-form">
                <div class="row">
                    <!-- Tìm kiếm cơ bản -->
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text"
                               class="form-control"
                               id="search"
                               name="search"
                               placeholder="Nhập tiêu đề hoặc tên BĐS..."
                               value="{{ request('search') }}">
                    </div>

                    <!-- Loại BĐS -->
                    <div class="col-md-4 mb-3">
                        <label for="type" class="form-label">Loại hình</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Tất cả loại</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Hình thức -->
                    <div class="col-md-4 mb-3">
                        <label for="formality" class="form-label">Hình thức</label>
                        <select class="form-select" id="formality" name="formality">
                            <option value="">Tất cả hình thức</option>
                            @foreach($formalities as $formality)
                                <option value="{{ $formality }}" {{ request('formality') == $formality ? 'selected' : '' }}>
                                    {{ $formality }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Khoảng giá -->
                    <div class="col-md-3 mb-3">
                        <label for="price_from" class="form-label">Giá từ</label>
                        <input type="number"
                               class="form-control"
                               id="price_from"
                               name="price_from"
                               value="{{ request('price_from') }}"
                               min="0"
                               step="1000">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="price_to" class="form-label">Giá đến</label>
                        <input type="number"
                               class="form-control"
                               id="price_to"
                               name="price_to"
                               value="{{ request('price_to') }}"
                               min="0"
                               step="10000">
                    </div>

                    <!-- Khoảng diện tích -->
                    <div class="col-md-3 mb-3">
                        <label for="area_from" class="form-label">Diện tích từ (m²)</label>
                        <input type="number"
                               class="form-control"
                               id="area_from"
                               name="area_from"
                               value="{{ request('area_from') }}"
                               min="0"
                               step="0.1">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="area_to" class="form-label">Diện tích đến (m²)</label>
                        <input type="number"
                               class="form-control"
                               id="area_to"
                               name="area_to"
                               value="{{ request('area_to') }}"
                               min="0"
                               step="0.1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="height_from" class="form-label">Chiều dài từ </label>
                        <input type="number"
                               class="form-control"
                               id="height_from"
                               name="height_from"
                               value="{{ request('height_from') }}"
                               min="0"
                               step="0.1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="height_to" class="form-label">Chiều dài đến</label>
                        <input type="number"
                               class="form-control"
                               id="height_to"
                               name="height_to"
                               value="{{ request('height_to') }}"
                               min="0"
                               step="0.1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="width_from" class="form-label">Chiều rộng từ </label>
                        <input type="number"
                               class="form-control"
                               id="width_from"
                               name="width_from"
                               value="{{ request('width_from') }}"
                               min="0"
                               step="0.1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="width_to" class="form-label">Chiều rộng đến</label>
                        <input type="number"
                               class="form-control"
                               id="width_to"
                               name="width_to"
                               value="{{ request('width_to') }}"
                               min="0"
                               step="0.1">
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="province_id" class="form-label">Tỉnh/Thành phố</label>
                        <select name="province_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($city as $city)
                                <option value="{{ $city->id }}"
                                        {{ request('name') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="district_id" class="form-label">Quận/huyện</label>
                        <select name="district_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($district as $district)
                                <option value="{{ $district->id }}"
                                        {{ request('name') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="ward_id" class="form-label">Phường/xã</label>
                        <select name="ward_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($ward as $ward)
                                <option value="{{ $ward->id }}"
                                        {{ request('id') == $ward->id ? 'selected' : '' }}>
                                    {{ $ward->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="houseid" class="form-label">Số nhà có chứa</label>
                        <input type="Text"
                               class="form-control"
                               id="houseid"
                               name="houseid"
                               value="{{ request('houseid') }}"
                               min="0"
                               step="0.1">
                    </div>

                </div>

                <div class="d-flex justify-content-between">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Tìm kiếm
                    </button>

                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <b> {{ $countproduct }}</b>
            <div class="table-responsive">
                <div style="min-width: 1200px">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="min-width: 300">Ảnh</th>
                                <th style="min-width: 150px">Loại</th>
                                <th style="min-width: 200px">Tiêu đề</th>
                                <th style="min-width: 300px">Mô tả</th>
                                <th style="min-width: 100px">Sđt</th>
                                {{-- <th style="min-width: 100px">Số điện thoại</th> --}}
                                <th style="min-width: 100px">dài</th>
                                <th style="min-width: 150px">Rộng</th>
                                <th style="min-width: 200px">Giá</th>
                                <th style="min-width: 150px">Quận/huyện</th>
                                <th style="min-width: 150px">Phường/xã</th>
                                {{-- <th style="min-width: 150px">Đường</th> --}}
                                <th style="min-width: 200px">Số nhà</th>
                                <th style="min-width: 150px">Diện tích</th>
                                <th style="min-width: 120px">Trạng thái</th>
                                <th style="min-width: 120px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td></td>
                                <td>{{ $product->type }}</td>
                                <td>
                                    <div class="fw-bold">{{ $product->title }}</div>
                                    <small class="text-muted">{{ $product->name }}</small>
                                </td>
                                <td>{{ Str::limit($product->content, 100) }}</td>
                                <td>{{ Str::limit($product->host_phone1, 100) }}</td>
                                <td>{{ $product->length }}</td>
                                <td>{{ $product->width }}</td>

                                <td>{{ number_format($product->price)}} VND</td>
                                <td>{{ $product->district_name  }}</td>
                                <td>{{ $product->ward_name}}</td>
                                {{-- <td></td> --}}
                                <td>{{ $product->house_number }}</td>
                                <td>{{ $product->formatted_area }}</td>
                                <td>
                                    @if($product->close_deal_type === 'active')
                                        <span class="badge bg-success">đang mở</span>
                                    @else
                                        <span class="badge bg-danger">đóng</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                           class="btn btn-sm btn-info"
                                           title="Chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="btn btn-sm btn-primary"
                                           title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa bất động sản này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger"
                                                    title="Xóa">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Không có dữ liệu</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-end mt-3">
            </div>


                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto submit form when select fields change
    document.querySelectorAll('#filter-form select').forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });
</script>
@endpush

@endsection
