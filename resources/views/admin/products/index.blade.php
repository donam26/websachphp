@extends('layouts.admin')

@section('title', 'Quản lý bất động sản')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý bất động sản</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-warning" >
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

                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="" selected >Tất cả</option>
                            @foreach($status as $status)
                                <option value="{{ $status->status }}"
                                        {{ request('status') == $status->status ?  : '' }}>
                                        @if($status->status === 'active')
                                        <span class="badge bg-success">đang mở</span>
                                    @else
                                        <span class="badge bg-danger">đóng</span>
                                    @endif
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
                        <input type="text"
                               class="form-control"
                               id="province_id"
                               name="province_id"
                               value="{{ request('province_id') }}"
                               min="0"
                               step="0.1">
                        
                        <!-- <select name="province_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($city as $city)
                                <option value="{{ $city->id }}"
                                        {{ request('province_id') == $city->id ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                            @endforeach
                        </select> -->
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="district_id" class="form-label">Quận/huyện</label>
                        <input type="text"
                               class="form-control"
                               id="district_id"
                               name="district_id"
                               value="{{ request('district_id') }}"
                               min="0"
                               step="0.1">
                        <!-- <select name="district_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($district as $district)
                                <option value="{{ $district->id }}"
                                        {{ request('district_id') == $district->id ? 'selected' : '' }}>
                                    {{ $district->name }}
                                </option>
                            @endforeach
                        </select> -->
                    </div>

                    <div class="col-md-2 mb-3">
                        <label for="ward_id" class="form-label">Phường/xã</label>
                        <!-- <select name="ward_id" class="form-select">
                            <option value="">Tất cả</option>
                            @foreach($ward as $ward)
                                <option value="{{ $ward->id }}"
                                        {{ request('ward_id') == $ward->id ? 'selected' : '' }}>
                                    {{ $ward->name }}
                                </option>
                            @endforeach
                        </select> -->
                        <input type="text"
                               class="form-control"
                               id="ward_id"
                               name="ward_id"
                               value="{{ request('ward_id') }}"
                               min="0"
                               step="0.1">
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
                <div class="col-md-4 mb-3">
                    <label class="form-label">Loại bất động sản</label>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_hot"
                                 value="1"
                                      >
                                <label class="form-check-label" for="feature_elevator">
                                    <b>BĐS hot <i class="bi bi-star-fill" style ="color: #E0C843"></i> </b>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="type_input"
                                 value="vp"
                                      >
                                <label class="form-check-label" for="feature_elevator">
                                    <b>sản phẩm văn phòng <i class="bi bi-building" style ="color:rgb(0, 38, 255)"></i> </b>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="d-flex justify-content-between">

                    <button type="submit" class="btn btn-warning" style="min-width: 300 ; background: #E9C780 ">
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
                                <th style="min-width: 300 ; background: #E9C780 " >Ảnh</th>
                                <th style="min-width: 150px ; background: #E9C780;">Loại</th>
                                <th style="min-width: 200px ; background: #E9C780; ">Tiêu đề</th>
                                <th style="min-width: 120px ; background: #E9C780;">Trạng thái</th>

                                {{-- <th style="min-width: 300px ">Mô tả</th> --}}
                                <th style="min-width: 100px ; background: #E9C780; ">Ngang</th>
                                <th style="min-width: 100px ; background: #E9C780;">Dài</th>

                                <th style="min-width: 100px ; background: #E9C780;">Diện tích</th>
                                <th style="min-width: 250px ; background: #E9C780; ">Giá thuê</th>
                                <th style="min-width: 200px ; background: #E9C780; ">Kết cấu</th>
                                <th style="min-width: 200px ; background: #E9C780; ">Ngày tạo</th>    
                                <th style="min-width: 200px ; background: #E9C780; ">Ngày điều chỉnh</th> 
                                {{-- <th style="min-width: 100px">Số điện thoại</th> --}}

                                <th style="min-width: 150px ; background: #E9C780; ">Quận/huyện</th>
                                <th style="min-width: 150px ; background: #E9C780; ">Phường/xã</th>
                                {{-- <th style="min-width: 150px">Đường</th> --}}
                                <th style="min-width: 200px ; background: #E9C780; ">Số nhà</th>
                                 
                                <th style="min-width: 120px ; background: #E9C780; ">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                            <tr>
                                <td>
                                    @if(isset($product->images) && $product->images->count() > 0)
                                        <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                                             alt="Ảnh sản phẩm"
                                             style="max-width: 100px; height: auto;">
                                    @else
                                        <img src="{{ asset('images/no-image.jpg') }}"
                                             alt="Không có ảnh"
                                             style="max-width: 100px; height: auto;">
                                    @endif
                                </td>
                                <td>{{ $product->type }}</td>
                                <td>
                                    <div class="fw-bold">
                                        <a href="{{ route('admin.products.show', $product->code) }}"
                                           target="_blank"
                                           class="link-black link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                           title="Chi tiết"
                                           style="text-decoration: none; color: black">
                                            {{ $product->title }}
                                        </a>
                                    </div>
                                </td>
                                <td>
                                    @if($product->status === 'active')
                                        <span class="badge bg-success">đang mở</span>
                                    @else
                                        <span class="badge bg-danger">đóng</span>
                                    @endif
                                </td>

                                {{-- <td>{{ Str::limit($product->content, 100) }}</td> --}}
                                <td>{{ $product->width }}</td>
                                <td>{{ $product->length }}</td>

                                <td>{{ $product->acreage }}</td>
                                <td>{{ number_format($product->price)}} VND</td>
                                <td> {{ $product->texture }}</td>
                                <td>{{ $product->created_at }}</td>
                                <td>{{ $product->updated_at }}</td>
                                <td>{{ $product->district_name  }}</td>
                                <td>{{ $product->ward_name}}</td>
                                {{-- <td></td> --}}
                                <td>{{ $product->house_number }}</td>
                               


                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.products.show', $product->code) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-info"
                                           title="Chi tiết">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->code) }}"
                                           target="_blank"
                                           class="btn btn-sm btn-primary"
                                           title="Sửa">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($user->IsDelete === 1)
                                        <form action="{{ route('admin.products.destroy', $product->code) }}"
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
                                        @else
                                        <span class="badge bg-danger"></span>
                                        @endif
                                        
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

