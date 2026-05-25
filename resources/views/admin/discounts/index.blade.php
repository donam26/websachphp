@extends('layouts.admin')

@section('title', 'Mã giảm giá')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-ticket-perforated me-2 text-primary"></i>Mã giảm giá</h5>
                <small class="text-muted">Tổng cộng {{ $discounts->total() }} mã</small>
            </div>
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Thêm mã mới
            </a>
        </div>

        <form method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-7">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo mã hoặc tên..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">Tất cả</option>
                        <option value="active" {{ request('status')=='active'?'selected':'' }}>Đang hoạt động</option>
                        <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Tạm tắt</option>
                        <option value="expired" {{ request('status')=='expired'?'selected':'' }}>Đã hết hạn</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-outline-primary flex-grow-1"><i class="bi bi-funnel"></i></button>
                    @if(request()->hasAny(['search','status']))
                        <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-3">Mã</th>
                        <th>Tên</th>
                        <th>Loại</th>
                        <th class="text-end">Giá trị</th>
                        <th class="text-center">Đã dùng / Giới hạn</th>
                        <th>Hiệu lực</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3" width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($discounts as $discount)
                        <tr>
                            <td class="ps-3"><code class="text-primary fw-bold">{{ $discount->code }}</code></td>
                            <td class="fw-semibold">{{ $discount->name }}</td>
                            <td>
                                @if($discount->type === 'fixed')
                                    <span class="badge badge-soft-info">Cố định</span>
                                @else
                                    <span class="badge badge-soft-warning">Phần trăm</span>
                                @endif
                            </td>
                            <td class="text-end fw-semibold text-primary">
                                @if($discount->type === 'fixed')
                                    {{ number_format($discount->value, 0, ',', '.') }}đ
                                @else
                                    {{ rtrim(rtrim(number_format($discount->value, 2), '0'), '.') }}%
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $discount->used_count ?? 0 }} / {{ $discount->usage_limit ?? '∞' }}
                            </td>
                            <td class="small text-muted">
                                {{ $discount->start_date ? $discount->start_date->format('d/m/y') : '—' }}
                                <i class="bi bi-arrow-right mx-1"></i>
                                {{ $discount->end_date ? $discount->end_date->format('d/m/y') : '—' }}
                            </td>
                            <td class="text-center">
                                @if(!$discount->is_active)
                                    <span class="badge badge-soft-secondary">Tạm tắt</span>
                                @elseif($discount->end_date && $discount->end_date->isPast())
                                    <span class="badge badge-soft-danger">Hết hạn</span>
                                @elseif($discount->usage_limit && $discount->used_count >= $discount->usage_limit)
                                    <span class="badge badge-soft-warning">Hết lượt</span>
                                @else
                                    <span class="badge badge-soft-success">Đang chạy</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá mã này?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">Chưa có mã giảm giá nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($discounts->hasPages())
        <div class="card-footer bg-white">{{ $discounts->links() }}</div>
    @endif
</div>

@push('styles')
<style>
.badge-soft-secondary { background: rgba(107,114,128,.12); color: #4b5563; }
</style>
@endpush
@endsection
