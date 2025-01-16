@extends('layouts.admin')

@section('title', 'Quản lý bất động sản')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Khách hàng</h1>
        <a href="{{ route('admin.customer.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Thêm Khách hàng
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('admin.customer.index') }}" method="GET" id="filter-form">
                <div class="row">
                    <!-- Tìm kiếm cơ bản -->
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Tìm kiếm</label>
                        <input type="text"
                               class="form-control"
                               id="search"
                               name="search"
                               placeholder="Nhập tên khách hàng ,..."
                               value="{{ request('search') }}">
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
                        <label for="width_from" class="form-label">Chiều ngang từ </label>
                        <input type="number"
                               class="form-control"
                               id="width_from"
                               name="width_from"
                               value="{{ request('width_from') }}"
                               min="0"
                               step="0.1">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="width_to" class="form-label">Chiều ngang đến</label>
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
                                        {{ request('province_id') == $city->id ? 'selected' : '' }}>
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
                                        {{ request('district_id') == $district->id ? 'selected' : '' }}>
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
                                        {{ request('ward_id') == $ward->id ? 'selected' : '' }}>
                                    {{ $ward->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-between">

                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Tìm kiếm
                    </button>

                    <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <b> {{ $countcustomer }}</b>
            <div class="table-responsive">
                <div style="min-width: 1200px">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="min-width: 300">Ảnh</th>
                                <th style="min-width: 150px">Tên</th>
                                <th style="min-width: 100px">Ngang</th>
                                <th style="min-width: 100px">Dài</th>
                                <th style="min-width: 100px">Diện tích</th>
                                <th style="min-width: 250px">Giá thuê</th>
                                <th style="min-width: 150px">Quận/huyện</th>
                                <th style="min-width: 150px">Phường/xã</th>
                                <th style="min-width: 200px">Số nhà</th>
                                <th style="min-width: 200px">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer as $customer)
                            <tr>
                                <td></td>
                                <td>{{ $customer->host_name }}</td>
                                <td>{{ $customer->width }}</td>
                                <td>{{ $customer->length }}</td>
                                <td>{{ $customer->formatted_area }}</td>
                                <td>{{ number_format($customer->price)}} VND</td>
                                <td>{{ $customer->district_name  }}</td>
                                <td>{{ $customer->ward_name}}</td>
                                <td>{{ $customer->house_number }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.customer.show', $customer) }}" target="_blank"
                                           class="btn btn-sm btn-info"
                                           title="Chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.customer.edit', $customer) }}" target="_blank"
                                           class="btn btn-sm btn-primary"
                                           title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.customer.destroy', $customer) }}"
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


                {{-- {{ $customer->links() }} --}}
            </div>
        </div>
    </div>
</div>
{{--
@push('scripts')
<script>
    // Auto submit form when select fields change
    document.querySelectorAll('#filter-form select').forEach(select => {
        select.addEventListener('change', () => {
            document.getElementById('filter-form').submit();
        });
    });
</script>
@endpush --}}

@endsection

