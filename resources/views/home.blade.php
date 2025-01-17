@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Danh sách bất động sản</h1>
    </div>

    <!-- Search Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tìm kiếm</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('home') }}" method="GET">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Từ khóa</label>
                        <input type="text" 
                               class="form-control" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Tìm theo tiêu đề, tên...">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">Loại BĐS</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Tất cả</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="formality" class="form-label">Hình thức</label>
                        <select class="form-select" id="formality" name="formality">
                            <option value="">Tất cả</option>
                            @foreach($formalities as $formality)
                                <option value="{{ $formality }}" {{ request('formality') == $formality ? 'selected' : '' }}>
                                    {{ $formality }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="price_from" class="form-label">Giá từ</label>
                        <input type="number" 
                               class="form-control" 
                               id="price_from" 
                               name="price_from" 
                               value="{{ request('price_from') }}" 
                               placeholder="Giá thấp nhất">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="price_to" class="form-label">Đến giá</label>
                        <input type="number" 
                               class="form-control" 
                               id="price_to" 
                               name="price_to" 
                               value="{{ request('price_to') }}" 
                               placeholder="Giá cao nhất">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Tìm kiếm
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-counterclockwise me-1"></i>Đặt lại
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Loại</th>
                            <th>Hình thức</th>
                            <th>Giá</th>
                            <th>Diện tích</th>
                            <th>Địa chỉ</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    @if($product->images->where('is_primary', true)->first())
                                        <img src="{{ asset('storage/' . $product->images->where('is_primary', true)->first()->path) }}" 
                                             alt="Ảnh sản phẩm"
                                             class="img-thumbnail"
                                             style="max-width: 100px;">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $product->title }}</td>
                                <td>{{ $product->type }}</td>
                                <td>{{ $product->formality }}</td>
                                <td>{{ number_format($product->price) }} {{ $product->currency ?? 'VNĐ' }}</td>
                                <td>{{ $product->acreage }} m²</td>
                                <td>
                                    {{ implode(', ', array_filter([
                                        $product->house_number,
                                        $product->street,
                                        $product->ward_name,
                                        $product->district_name,
                                        $product->province_name
                                    ])) }}
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $product->code) }}" 
                                       class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Không có bất động sản nào</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-end">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 