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

    </div>
</div>
@endsection
