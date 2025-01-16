@extends('layouts.admin')

@section('title', 'Chi tiết bất động sản')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết bất động sản</h1>
        <div>
            <a href="{{ route('admin.customer.edit', $customer) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>Sửa
            </a>
            <a href="{{ route('admin.customer.index') }}" class="btn btn-secondary">
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
                            <td width="30%"><strong>Tên khách hàng:</strong></td>
                            <td>{{ $customer->host_name }}</td>
                        </tr>

                        <tr>
                            <td><strong>Loại BĐS:</strong></td>
                            <td>{{ $customer->type }}</td>
                        </tr>
                        <tr>
                            <td><strong>Hình thức:</strong></td>
                            <td>{{ $customer->formality }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tin nổi bật:</strong></td>
                            <td>
                                @if($customer->is_hot)
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
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title text-primary mb-4">Thông tin liên hệ/h5>
                    <table class="table table-borderless">

                        <tr>
                            <td><strong>Số điện thoại 1:</strong></td>
                            <td>{{ $customer->host_phone1 ?? 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Số điện thoại 2:</strong></td>
                            <td>{{ $customer->host_phone2 ?? 'Chưa cập nhật' }}</td>
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
                            <td>{{ $customer->full_address }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tỉnh/Thành phố:</strong></td>
                            <td>{{ $customer->province_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Quận/Huyện:</strong></td>
                            <td>{{ $customer->district_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Phường/Xã:</strong></td>
                            <td>{{ $customer->ward_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Đường/Phố:</strong></td>
                            <td>{{ $customer->street_id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Số nhà:</strong></td>
                            <td>{{ $customer->house_number }}</td>
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
                            <td>{{ $customer->formatted_price }}</td>
                        </tr>
                        <tr>
                            <td><strong>Diện tích:</strong></td>
                            <td>{{ $customer->formatted_area }}</td>
                        </tr>
                        <tr>
                            <td><strong>Chiều rộng:</strong></td>
                            <td>{{ $customer->width ? number_format($customer->width, 2) . ' m' : 'Chưa cập nhật' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Chiều dài:</strong></td>
                            <td>{{ $customer->length ? number_format($customer->length, 2) . ' m' : 'Chưa cập nhật' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <h3> Bất động sản liên quan </h3>

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
                                    <th style="min-width: 200px">Kết cấu</th>
                                    {{-- <th style="min-width: 300px">Mô tả</th> --}}
                                    <th style="min-width: 100px">Ngang</th>
                                    <th style="min-width: 100px">Dài</th>

                                    <th style="min-width: 100px">Diện tích</th>
                                    <th style="min-width: 250px">Giá thuê</th>
                                    <th style="min-width: 120px">Trạng thái</th>
                                    {{-- <th style="min-width: 100px">Số điện thoại</th> --}}

                                    <th style="min-width: 150px">Quận/huyện</th>
                                    <th style="min-width: 150px">Phường/xã</th>
                                    {{-- <th style="min-width: 150px">Đường</th> --}}
                                    <th style="min-width: 200px">Số nhà</th>
                                    <th style="min-width: 120px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                <tr>
                                    <td></td>
                                    <td>{{ $product->type }}</td>
                                    <td>

                                        <div class="fw-bold">  <a href="{{ route('admin.products.show', $product) }}"
                                             target="_blank"
                                            class="link-black link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                            title="Chi tiết"
                                            style ="text-decoration: none; color:black"
                                            >
                                            {{ $product->title }}
                                         </a>
                                         </div>
                                        {{-- <small class="text-muted">{{ $product->name }}</small> --}}
                                    </td>
                                    <td> </td>
                                    {{-- <td>{{ Str::limit($product->content, 100) }}</td> --}}
                                    <td>{{ $product->width }}</td>
                                    <td>{{ $product->length }}</td>

                                    <td>{{ $product->formatted_area }}</td>
                                    <td>{{ number_format($product->price)}} VND</td>
                                    <td>
                                        @if($product->close_deal_type === 'active')
                                            <span class="badge bg-success">đang mở</span>
                                        @else
                                            <span class="badge bg-danger">đóng</span>
                                        @endif
                                    </td>
                                    <td>{{ $product->district_name  }}</td>
                                    <td>{{ $product->ward_name}}</td>
                                    {{-- <td></td> --}}
                                    <td>{{ $product->house_number }}</td>


                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.products.show', $product ->id) }}" target="_blank"
                                               class="btn btn-sm btn-info"
                                               title="Chi tiết">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product ->id) }}" target="_blank"
                                               class="btn btn-sm btn-primary"
                                               title="Sửa">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            {{-- <form action="{{ route('admin.products.destroy', $product ->id) }}"
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
                                            </form> --}}
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
</div>
@endsection
