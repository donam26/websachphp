@extends('layouts.admin')

@section('title', 'Chi tiết bất động sản')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết bất động sản</h1>
        <div>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Sửa
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-1"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin cơ bản</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Tiêu đề:</strong></td>
                            <td>{{ $product->title }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tên BĐS:</strong></td>
                            <td>{{ $product->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Loại BĐS:</strong></td>
                            <td>{{ $product->type }}</td>
                        </tr>
                        <tr>
                            <td><strong>Hình thức:</strong></td>
                            <td>{{ $product->formality }}</td>
                        </tr>
                        <tr>
                            <td><strong>Trạng thái:</strong></td>
                            <td>{!! $product->status_badge !!}</td>
                        </tr>
                        <tr>
                            <td><strong>Tin nổi bật:</strong></td>
                            <td>
                                @if($product->is_hot)
                                    <span class="badge bg-warning">Có</span>
                                @else
                                    <span class="badge bg-secondary">Không</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin địa chỉ -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Địa chỉ</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Địa chỉ đầy đủ:</strong></td>
                            <td>{{ $product->full_address }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tỉnh/Thành phố:</strong></td>
                            <td>{{ $product->province_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Quận/Huyện:</strong></td>
                            <td>{{ $product->district_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phường/Xã:</strong></td>
                            <td>{{ $product->ward_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Đường/Phố:</strong></td>
                            <td>{{ $product->street_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Số nhà:</strong></td>
                            <td>{{ $product->house_number }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin giá và diện tích -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Giá và diện tích</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Giá:</strong></td>
                            <td>{{ $product->formatted_price }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diện tích:</strong></td>
                            <td>{{ $product->formatted_area }}</td>
                        </tr>
                        <tr>
                            <td><strong>Chiều rộng:</strong></td>
                            <td>{{ $product->width ? number_format($product->width, 2) . ' m' : 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Chiều dài:</strong></td>
                            <td>{{ $product->length ? number_format($product->length, 2) . ' m' : 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin chi tiết -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin chi tiết</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Số tầng:</strong></td>
                            <td>{{ $product->floor_number ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Số phòng:</strong></td>
                            <td>{{ $product->room_number_total ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Hướng:</strong></td>
                            <td>{{ $product->direction ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tính năng:</strong></td>
                            <td>
                                @if($product->elevator)
                                    <span class="badge bg-info me-1">Thang máy</span>
                                @endif
                                @if($product->basement)
                                    <span class="badge bg-info me-1">Tầng hầm</span>
                                @endif
                                @if($product->terrace)
                                    <span class="badge bg-info me-1">Sân thượng</span>
                                @endif
                                @if($product->has_corner)
                                    <span class="badge bg-info me-1">Nhà góc</span>
                                @endif
                                @if(!$product->elevator && !$product->basement && !$product->terrace && !$product->has_corner)
                                    Không có
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Thông tin chủ nhà -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin chủ nhà</h5>
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>Tên chủ nhà:</strong></td>
                            <td>{{ $product->host_name ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Số điện thoại 1:</strong></td>
                            <td>{{ $product->host_phone1 ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Số điện thoại 2:</strong></td>
                            <td>{{ $product->host_phone2 ?? 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Mô tả -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Mô tả</h5>
                    <div class="content">
                        {!! nl2br(e($product->content)) ?? 'Chưa có mô tả' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 