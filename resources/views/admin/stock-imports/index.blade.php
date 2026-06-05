@extends('layouts.admin')

@section('title', 'Phiếu nhập hàng')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Phiếu nhập hàng</h5>
                <small class="text-muted">Tổng cộng {{ $imports->total() }} phiếu nhập</small>
            </div>
            <a href="{{ route('admin.stock-imports.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Tạo phiếu nhập
            </a>
        </div>

        <form action="{{ route('admin.stock-imports.index') }}" method="GET" class="mt-3">
            <div class="row g-2">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Mã phiếu..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="supplier_id" class="form-select">
                        <option value="">Tất cả nhà cung cấp</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Trạng thái</option>
                        @foreach(\App\Models\StockImport::statusOptions() as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}" title="Từ ngày">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}" title="Đến ngày">
                </div>
                <div class="col-md-12 d-flex gap-2 justify-content-end">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-funnel me-1"></i>Lọc</button>
                    @if(request()->hasAny(['search', 'supplier_id', 'status', 'from', 'to']))
                        <a href="{{ route('admin.stock-imports.index') }}" class="btn btn-outline-secondary"><i class="bi bi-x-lg"></i></a>
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
                        <th class="ps-3">Mã phiếu</th>
                        <th>Nhà cung cấp</th>
                        <th class="text-center">Số mặt hàng</th>
                        <th class="text-end">Tổng tiền nhập</th>
                        <th>Ngày nhập</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($imports as $import)
                        <tr>
                            <td class="ps-3"><strong class="text-primary">{{ $import->code }}</strong></td>
                            <td>{{ $import->supplier->name ?? '— Không xác định —' }}</td>
                            <td class="text-center"><span class="badge badge-soft-primary">{{ $import->items_count }}</span></td>
                            <td class="text-end fw-bold">{{ number_format($import->total_amount, 0, ',', '.') }}đ</td>
                            <td>
                                <div class="small">{{ optional($import->imported_at)->format('d/m/Y') ?? $import->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ optional($import->imported_at)->format('H:i') ?? $import->created_at->format('H:i') }}</small>
                            </td>
                            <td class="text-center"><span class="badge badge-soft-{{ $import->status_color }}">{{ $import->status_label }}</span></td>
                            <td class="text-end pe-3">
                                <a href="{{ route('admin.stock-imports.show', $import) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye me-1"></i>Chi tiết
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Chưa có phiếu nhập nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($imports->hasPages())
        <div class="card-footer bg-white">{{ $imports->links() }}</div>
    @endif
</div>
@endsection
