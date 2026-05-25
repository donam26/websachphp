@extends('layouts.admin')

@section('title', 'Sửa mã giảm giá')

@section('content')
<div class="d-flex align-items-center mb-3">
    <a href="{{ route('admin.discounts.index') }}" class="btn btn-sm btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i></a>
    <h5 class="mb-0"><i class="bi bi-pencil-square me-2 text-primary"></i>Sửa mã: {{ $discount->code }}</h5>
</div>

<form action="{{ route('admin.discounts.update', $discount->id) }}" method="POST">
    @csrf @method('PUT')
    @include('admin.discounts._form', ['discount' => $discount])
</form>
@endsection
