@extends('layouts.admin')

@section('title', 'Tạo phiếu nhập hàng')

@section('content')
<form action="{{ route('admin.stock-imports.store') }}" method="POST" id="importForm">
    @csrf
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-box-arrow-in-down me-2 text-primary"></i>Tạo phiếu nhập hàng</h5>
        <a href="{{ route('admin.stock-imports.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Quay lại</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>Vui lòng kiểm tra lại thông tin:
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header bg-white"><strong>Thông tin phiếu</strong></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Nhà cung cấp</label>
                        <select name="supplier_id" class="form-select">
                            <option value="">-- Chọn nhà cung cấp --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                        @if($suppliers->isEmpty())
                            <small class="text-muted">Chưa có NCC nào. <a href="{{ route('admin.suppliers.index') }}">Thêm nhà cung cấp</a></small>
                        @endif
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ngày nhập</label>
                        <input type="date" name="imported_at" class="form-control" value="{{ old('imported_at', now()->format('Y-m-d')) }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="note" class="form-control" rows="3" maxlength="1000" placeholder="Ghi chú cho phiếu nhập">{{ old('note') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <strong>Danh sách sách nhập</strong>
                    <button type="button" class="btn btn-sm btn-primary" id="addRowBtn"><i class="bi bi-plus-lg me-1"></i>Thêm dòng</button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="min-width:220px">Sách</th>
                                    <th width="110" class="text-center">Số lượng</th>
                                    <th width="150" class="text-end">Giá nhập (đ)</th>
                                    <th width="140" class="text-end">Thành tiền</th>
                                    <th width="50"></th>
                                </tr>
                            </thead>
                            <tbody id="itemsBody"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-semibold">Tổng cộng</td>
                                    <td class="text-end fw-bold text-primary" id="grandTotal">0đ</td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white text-end">
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg me-1"></i>Lưu phiếu nhập &amp; cập nhật tồn kho</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
    const BOOKS = @json($books);
    const OLD_ITEMS = @json(old('items', []));
    let rowIndex = 0;

    const itemsBody = document.getElementById('itemsBody');

    function buildBookOptions(selectedId) {
        let html = '<option value="">-- Chọn sách --</option>';
        BOOKS.forEach(function (b) {
            const sel = String(b.id) === String(selectedId) ? 'selected' : '';
            html += `<option value="${b.id}" data-price="${b.price}" ${sel}>${b.title} (Tồn: ${b.quantity})</option>`;
        });
        return html;
    }

    function addRow(data) {
        data = data || {};
        const idx = rowIndex++;
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td>
                <select name="items[${idx}][book_id]" class="form-select form-select-sm book-select" required>
                    ${buildBookOptions(data.book_id)}
                </select>
            </td>
            <td><input type="number" name="items[${idx}][quantity]" class="form-control form-control-sm text-center qty-input" min="1" value="${data.quantity || 1}" required></td>
            <td><input type="number" name="items[${idx}][import_price]" class="form-control form-control-sm text-end price-input" min="0" step="1000" value="${data.import_price || 0}" required></td>
            <td class="text-end subtotal-cell">0đ</td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-x-lg"></i></button></td>
        `;
        itemsBody.appendChild(tr);
        recalcRow(tr);
    }

    function formatVnd(n) {
        return Math.round(n).toLocaleString('vi-VN') + 'đ';
    }

    function recalcRow(tr) {
        const qty = parseFloat(tr.querySelector('.qty-input').value) || 0;
        const price = parseFloat(tr.querySelector('.price-input').value) || 0;
        tr.querySelector('.subtotal-cell').textContent = formatVnd(qty * price);
        recalcTotal();
    }

    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(function (tr) {
            const qty = parseFloat(tr.querySelector('.qty-input').value) || 0;
            const price = parseFloat(tr.querySelector('.price-input').value) || 0;
            total += qty * price;
        });
        document.getElementById('grandTotal').textContent = formatVnd(total);
    }

    itemsBody.addEventListener('input', function (e) {
        if (e.target.classList.contains('qty-input') || e.target.classList.contains('price-input')) {
            recalcRow(e.target.closest('tr'));
        }
    });

    itemsBody.addEventListener('change', function (e) {
        if (e.target.classList.contains('book-select')) {
            const opt = e.target.selectedOptions[0];
            const tr = e.target.closest('tr');
            const priceInput = tr.querySelector('.price-input');
            // Gợi ý giá nhập theo giá bán nếu chưa nhập
            if (opt && opt.dataset.price && (!parseFloat(priceInput.value))) {
                priceInput.value = opt.dataset.price;
                recalcRow(tr);
            }
        }
    });

    itemsBody.addEventListener('click', function (e) {
        if (e.target.closest('.remove-row')) {
            e.target.closest('tr').remove();
            recalcTotal();
        }
    });

    document.getElementById('addRowBtn').addEventListener('click', function () { addRow(); });

    document.getElementById('importForm').addEventListener('submit', function (e) {
        if (document.querySelectorAll('.item-row').length === 0) {
            e.preventDefault();
            alert('Vui lòng thêm ít nhất một sách vào phiếu nhập.');
        }
    });

    // Khởi tạo
    if (OLD_ITEMS && OLD_ITEMS.length) {
        OLD_ITEMS.forEach(function (it) { addRow(it); });
    } else {
        addRow();
    }
</script>
@endpush
