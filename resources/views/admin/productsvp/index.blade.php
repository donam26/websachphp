@extends('layouts.admin')

@section('title', 'Quản lý bất động sản')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý bất động sản</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Thêm sản phẩm văn phòng
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
                        <label for="type" class="form-label">Loại BĐS</label>
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
                               step="1000000">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="price_to" class="form-label">Giá đến</label>
                        <input type="number"
                               class="form-control"
                               id="price_to"
                               name="price_to"
                               value="{{ request('price_to') }}"
                               min="0"
                               step="1000000">
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

                    <!-- Địa điểm -->
                    <div class="col-md-4 mb-3">
                        <label for="province_id" class="form-label">Tỉnh/Thành phố</label>
                        <select class="form-select" id="province_id" name="province_id">
                            <option value="">Chọn tỉnh/thành phố</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province }}" {{ request('province_id') == $province ? 'selected' : '' }}>
                                    {{ $province }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Trạng thái -->
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Đang hoạt động</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Ngừng hoạt động</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                            <option value="sold" {{ request('status') == 'sold' ? 'selected' : '' }}>Đã bán</option>
                            <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Đã cho thuê</option>
                        </select>
                    </div>

                    <!-- Tính năng -->
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tính năng</label>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="elevator" id="feature_elevator"
                                           {{ in_array('elevator', (array)request('features')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_elevator">
                                        Thang máy
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="basement" id="feature_basement"
                                           {{ in_array('basement', (array)request('features')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_basement">
                                        Tầng hầm
                                    </label>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="terrace" id="feature_terrace"
                                           {{ in_array('terrace', (array)request('features')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_terrace">
                                        Sân thượng
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="features[]" value="corner" id="feature_corner"
                                           {{ in_array('corner', (array)request('features')) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="feature_corner">
                                        Nhà góc
                                    </label>
                                </div>
                            </div>
                        </div>
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
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Loại</th>
                            <th>Tiêu đề</th>
                            <th>Mô tả</th>
                            <th>Địa chỉ</th>
                            <th>Giá</th>
                            <th>Diện tích</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $product->type }}</td>
                            <td>
                                <div class="fw-bold">{{ $product->title }}</div>
                                <small class="text-muted">{{ $product->name }}</small>
                            </td>
                            <td>{{ $product->content }}</td>
                            <td>{{ $product->full_address }}</td>
                            <td>{{ $product->formatted_price }}</td>
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
                            <td colspan="7" class="text-center">Không có dữ liệu</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
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
