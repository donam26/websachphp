@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nổi bật</h1>
        <div>
            <button class="btn btn-warning" onclick="window.print()">
                <i class="bi bi-printer"></i> In báo cáo
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Tổng số người dùng Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning h-100">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tổng số người dùng</div>
                            <div class="h5 mb-0 font-weight-bold">{{ $totalUsers ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <!-- Sách bán chạy -->
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger" style =" font-size: 25px"><b>Top 20 Sản phẩm mới cập nhật</b></h6>
                   
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style ="background: #E9C780 ; font-size: 20px">MÔ TẢ</th>
                                    <th style ="min-width: 200px ;background: #E9C780 ; font-size: 20px">GIÁ</th>
                                    <th style ="min-width: 200px ;background: #E9C780 ; font-size: 20px">NGÀY TẠO</th>
                                    <th style ="min-width: 200px ;background: #E9C780 ; font-size: 20px">NGÀY CẬP NHẬT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Topproducts as $products)
                                    <tr>
                                        <td> <div class="fw-bold">
                                        <a href="{{ route('admin.products.show', $products->code) }}"
                                           target="_blank"
                                           class="link-black link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                           title="Chi tiết"
                                           style="text-decoration: none; color:black">
                                            {{ $products->title }}
                                        </a>
                                    </div></td>
                                        <td>{{ number_format($products->price)}} VNĐ</td>
                                        <td>{{ $products->created_at }}</td>
                                        <td>{{ $products->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-danger" style =" font-size: 25px"><b>Top 20 Sản phẩm giá trị cao</b></h6>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-warning">
                        Xem tất cả
                    </a>
        
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th style ="background: #E9C780 ; font-size: 20px">MÔ TẢ</th>
                                    <th style ="min-width: 200px ;background: #E9C780 ; font-size: 20px">GIÁ</th>
                                    <th style ="min-width: 200px ;background: #E9C780 ; font-size: 20px">NGÀY TẠO</th>
                                    <th style ="min-width: 200px ;background: #E9C780 ; font-size: 20px">NGÀY CẬP NHẬT</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Topprice as $Topprice)
                                    <tr>
                                        <td> <div class="fw-bold">
                                        <a href="{{ route('admin.products.show', $products->code) }}"
                                           target="_blank"
                                           class="link-black link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover"
                                           title="Chi tiết"
                                           style="text-decoration: none; color:black">
                                            {{ $Topprice->title }}
                                        </a>
                                    </div> </b> </td>
                                        <td>{{ number_format($Topprice->price)}} VNĐ</td>
                                        <td>{{ $Topprice->created_at }}</td>
                                        <td>{{ $Topprice->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
