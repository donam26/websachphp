@extends('layouts.admin')

@section('title', 'Chi tiết phiếu nhập ' . $stockImport->code)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <h5 class="mb-0">
        <i class="bi bi-receipt-cutoff me-2 text-primary"></i>Phiếu nhập <strong class="text-primary">{{ $stockImport->code }}</strong>
        <span class="badge badge-soft-{{ $stockImport->status_color }} ms-2">{{ $stockImport->status_label }}</span>
    </h5>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.stock-imports.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-white"><strong>Thông tin phiếu</strong></div>
            <div class="card-body">
                <dl class="row mb-0 small">
                    <dt class="col-5 text-muted">Nhà cung cấp</dt>
                    <dd class="col-7">{{ $stockImport->supplier->name ?? '—' }}</dd>

                    @if($stockImport->supplier && $stockImport->supplier->phone)
                        <dt class="col-5 text-muted">SĐT NCC</dt>
                        <dd class="col-7">{{ $stockImport->supplier->phone }}</dd>
                    @endif

                    <dt class="col-5 text-muted">Ngày nhập</dt>
                    <dd class="col-7">{{ optional($stockImport->imported_at)->format('d/m/Y H:i') ?? $stockImport->created_at->format('d/m/Y H:i') }}</dd>

                    <dt class="col-5 text-muted">Người tạo</dt>
                    <dd class="col-7">{{ $stockImport->user->full_name ?? $stockImport->user->username ?? '—' }}</dd>

                    <dt class="col-5 text-muted">Tổng tiền nhập</dt>
                    <dd class="col-7 fw-bold text-primary">{{ number_format($stockImport->total_amount, 0, ',', '.') }}đ</dd>

                    @if($stockImport->status === \App\Models\StockImport::STATUS_CANCELLED && $stockImport->cancelled_at)
                        <dt class="col-5 text-muted">Hủy lúc</dt>
                        <dd class="col-7 text-danger">{{ $stockImport->cancelled_at->format('d/m/Y H:i') }}</dd>
                    @endif
                </dl>
                @if($stockImport->note)
                    <hr>
                    <div class="small"><span class="text-muted">Ghi chú:</span> {{ $stockImport->note }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-white"><strong>Danh sách sách nhập</strong></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Sách</th>
                                <th class="text-center">Số lượng</th>
                                <th class="text-end">Giá nhập</th>
                                <th class="text-end pe-3">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stockImport->items as $item)
                                <tr>
                                    <td class="ps-3">
                                        @if($item->book)
                                            <a href="{{ route('admin.books.show', $item->book) }}" class="fw-semibold text-decoration-none">{{ $item->book_title }}</a>
                                            <div><small class="text-muted">Tồn hiện tại: {{ $item->book->quantity }}</small></div>
                                        @else
                                            <span class="fw-semibold">{{ $item->book_title }}</span>
                                            <div><small class="text-danger">(Sách đã bị xoá)</small></div>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">{{ number_format($item->import_price, 0, ',', '.') }}đ</td>
                                    <td class="text-end pe-3 fw-semibold">{{ number_format($item->subtotal, 0, ',', '.') }}đ</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-semibold">Tổng cộng ({{ $stockImport->total_quantity }} cuốn)</td>
                                <td class="text-end pe-3 fw-bold text-primary">{{ number_format($stockImport->total_amount, 0, ',', '.') }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
