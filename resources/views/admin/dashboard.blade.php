@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nổi bật</h1>
        <div>
            <button class="btn btn-primary" onclick="window.print()">
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
                                    <th style ="background: #FFCC33 ; font-size: 20px">Mô tả</th>
                                    <th style ="min-width: 200px ;background: #FFCC33 ; font-size: 20px">Giá</th>
                                    <th style ="min-width: 200px ;background: #FFCC33 ; font-size: 20px">Ngày tạo</th>
                                    <th style ="min-width: 200px ;background: #FFCC33 ; font-size: 20px">Ngày cập nhật</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Topproducts as $products)
                                    <tr>
                                        <td> <b>{{ $products->title }} </b></td>
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
                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary">
                        Xem tất cả
                    </a>
        
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr >
                                    <th style ="background: #FFCC33 ; font-size: 20px">Mô tả</th>
                                    <th style ="min-width: 200px ;background: #FFCC33 ; font-size: 20px">Giá</th>
                                    <th style ="min-width: 200px ;background: #FFCC33 ; font-size: 20px">Ngày tạo</th>
                                    <th style ="min-width: 200px ;background: #FFCC33 ; font-size: 20px">Ngày cập nhật</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($Topprice as $Topprice)
                                    <tr>
                                        <td> <b>{{ $products->title }} </b> </td>
                                        <td>{{ number_format($products->price)}} VNĐ</td>
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
