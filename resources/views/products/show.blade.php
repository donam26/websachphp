@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết bất động sản</h1>
        <a href="{{ route('home') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Thông tin cơ bản -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin cơ bản</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h4>{{ $product->title }}</h4>
                            <p class="text-muted mb-0">{{ $product->name }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Loại BĐS:</strong>
                            <p>{{ $product->type }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Hình thức:</strong>
                            <p>{{ $product->formality }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Giá:</strong>
                            <p>{{ number_format($product->price) }} {{ $product->currency ?? 'VNĐ' }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <strong>Diện tích:</strong>
                            <p>{{ $product->acreage }} m²</p>
                        </div>

                        <div class="col-md-12 mb-3">
                            <strong>Địa chỉ:</strong>
                            <p>
                                {{ implode(', ', array_filter([
                                    $product->house_number,
                                    $product->street,
                                    $product->ward_name,
                                    $product->district_name,
                                    $product->province_name
                                ])) }}
                            </p>
                        </div>

                        <div class="col-md-12 mb-3">
                            <strong>Mô tả:</strong>
                            <div class="mt-2">
                                {!! nl2br(e($product->content)) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hình ảnh -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Hình ảnh</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($product->images as $image)
                            <div class="col-md-4 mb-3">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image->path) }}" 
                                         alt="Ảnh sản phẩm"
                                         class="img-fluid rounded">
                                    @if($image->is_primary)
                                        <span class="badge bg-primary position-absolute top-0 end-0 mt-2 me-2">
                                            Ảnh chính
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-muted">Chưa có ảnh</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Tài liệu đính kèm -->
            @if($product->files->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tài liệu đính kèm</h6>
                    </div>
                    <div class="card-body">
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
                                    @foreach($product->files as $file)
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Thông tin liên hệ -->
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin liên hệ</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Tên chủ nhà:</strong>
                        <p>{{ $product->host_name }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Số điện thoại 1:</strong>
                        <p>{{ $product->host_phone1 }}</p>
                    </div>

                    @if($product->host_phone2)
                        <div class="mb-3">
                            <strong>Số điện thoại 2:</strong>
                            <p>{{ $product->host_phone2 }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Thông tin khác -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khác</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Chiều rộng:</strong>
                        <p>{{ $product->width }} m</p>
                    </div>

                    <div class="mb-3">
                        <strong>Chiều dài:</strong>
                        <p>{{ $product->length }} m</p>
                    </div>

                    @if($product->is_hot)
                        <div class="alert alert-warning">
                            <i class="bi bi-star-fill me-1"></i>Tin nổi bật
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 