@extends('layouts.admin')

@section('title', 'Thêm mã giảm giá')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.discounts.index') }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0"><i class="bi bi-ticket-perforated me-2 text-primary"></i>Thêm mã giảm giá</h5>
</div>

<form action="{{ route('admin.discounts.store') }}" method="POST">
    @csrf
    @include('admin.discounts._form', ['discount' => null])
</form>
@endsection
