@extends('layouts.admin')

@section('title', 'Phương thức thanh toán')

@section('content')
<div class="card mb-3">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="mb-1"><i class="bi bi-credit-card-2-front me-2 text-primary"></i>Phương thức thanh toán</h5>
                <small class="text-muted">Tổng cộng {{ $paymentMethods->total() }} phương thức</small>
            </div>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentMethodModal">
                <i class="bi bi-plus-lg me-1"></i>Thêm phương thức
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-3" width="70">Thứ tự</th>
                        <th>Phương thức</th>
                        <th>Mã</th>
                        <th class="text-center">Đơn đã dùng</th>
                        <th class="text-center">Trạng thái</th>
                        <th class="text-end pe-3" width="120">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentMethods as $pm)
                        <tr>
                            <td class="ps-3 text-muted">{{ $pm->sort_order }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi {{ $pm->icon ?: 'bi-wallet2 text-secondary' }} fs-5"></i>
                                    <div>
                                        <div class="fw-semibold">{{ $pm->name }}</div>
                                        @if($pm->description)
                                            <small class="text-muted">{{ $pm->description }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <code>{{ $pm->code }}</code>
                                @if($pm->isSystem())
                                    <span class="badge badge-soft-info ms-1" title="Phương thức hệ thống, gắn với xử lý trong code">hệ thống</span>
                                @endif
                            </td>
                            <td class="text-center"><span class="badge badge-soft-primary">{{ $pm->orders_count }}</span></td>
                            <td class="text-center">
                                @if($pm->is_active)
                                    <span class="badge badge-soft-success">Đang bật</span>
                                @else
                                    <span class="badge badge-soft-secondary">Đã tắt</span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editPaymentMethodModal{{ $pm->id }}"><i class="bi bi-pencil"></i></button>
                                    @if($pm->isSystem())
                                        <button class="btn btn-outline-secondary" disabled title="Phương thức hệ thống không thể xoá"><i class="bi bi-trash"></i></button>
                                    @else
                                        <form action="{{ route('admin.payment-methods.destroy', $pm->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Xoá phương thức này?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                        </form>
                                    @endif
                                </div>

                                <div class="modal fade" id="editPaymentMethodModal{{ $pm->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.payment-methods.update', $pm->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-content text-start">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Chỉnh sửa phương thức</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @include('admin.payment-methods._form', ['paymentMethod' => $pm])
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Chưa có phương thức nào</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($paymentMethods->hasPages())
        <div class="card-footer bg-white">{{ $paymentMethods->links() }}</div>
    @endif
</div>

<div class="modal fade" id="addPaymentMethodModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.payment-methods.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Thêm phương thức thanh toán</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-start">
                    @include('admin.payment-methods._form', ['paymentMethod' => null])
                    <div class="alert alert-light border mt-3 mb-0 small text-muted">
                        <i class="bi bi-info-circle me-1"></i>Phương thức mới (ngoài <code>cod</code>/<code>vnpay</code>) sẽ được xử lý như thanh toán ngoài hệ thống (đơn tạo & chờ thanh toán thủ công).
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm phương thức</button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('addPaymentMethodModal')).show();
    });
</script>
@endif
@endsection
