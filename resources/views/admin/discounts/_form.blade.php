<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Thông tin chung</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Mã giảm giá <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror"
                               name="code" value="{{ old('code', $discount->code ?? '') }}" required placeholder="VD: WELCOME50">
                        <small class="text-muted">Mã viết hoa, không dấu cách</small>
                        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tên hiển thị <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               name="name" value="{{ old('name', $discount->name ?? '') }}" required placeholder="VD: Giảm 50K cho thành viên mới">
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Mô tả chi tiết về voucher...">{{ old('description', $discount->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-cash-coin me-2"></i>Loại & giá trị</div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Loại giảm giá <span class="text-danger">*</span></label>
                        <select class="form-select" name="type" id="discount-type" required>
                            <option value="fixed" {{ old('type', $discount->type ?? 'fixed') === 'fixed' ? 'selected' : '' }}>Cố định (VND)</option>
                            <option value="percent" {{ old('type', $discount->type ?? '') === 'percent' ? 'selected' : '' }}>Phần trăm (%)</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Giá trị giảm <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="value"
                                   value="{{ old('value', $discount->value ?? '') }}" required step="0.01">
                            <span class="input-group-text type-suffix">đ</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Đơn tối thiểu</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="min_order_amount"
                                   value="{{ old('min_order_amount', $discount->min_order_amount ?? 0) }}">
                            <span class="input-group-text">đ</span>
                        </div>
                    </div>
                    <div class="col-md-6" id="max-discount-group">
                        <label class="form-label">Giảm tối đa</label>
                        <div class="input-group">
                            <input type="number" class="form-control" name="max_discount_amount"
                                   value="{{ old('max_discount_amount', $discount->max_discount_amount ?? '') }}">
                            <span class="input-group-text">đ</span>
                        </div>
                        <small class="text-muted">Chỉ áp dụng cho giảm %</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-calendar-range me-2"></i>Hiệu lực</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Bắt đầu</label>
                    <input type="datetime-local" class="form-control" name="start_date"
                           value="{{ old('start_date', isset($discount->start_date) && $discount->start_date ? \Carbon\Carbon::parse($discount->start_date)->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Kết thúc</label>
                    <input type="datetime-local" class="form-control" name="end_date"
                           value="{{ old('end_date', isset($discount->end_date) && $discount->end_date ? \Carbon\Carbon::parse($discount->end_date)->format('Y-m-d\TH:i') : '') }}">
                </div>
                <div class="mb-0">
                    <label class="form-label">Giới hạn số lần dùng</label>
                    <input type="number" class="form-control" name="usage_limit"
                           value="{{ old('usage_limit', $discount->usage_limit ?? '') }}" placeholder="Để trống = không giới hạn">
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-toggles me-2"></i>Trạng thái</div>
            <div class="card-body">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $discount->is_active ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label">Kích hoạt mã giảm giá</label>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-secondary flex-fill">Hủy</a>
            <button type="submit" class="btn btn-primary flex-fill"><i class="bi bi-check2 me-1"></i>Lưu</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.getElementById('discount-type');
    const typeSuffix = document.querySelector('.type-suffix');
    const maxGroup = document.getElementById('max-discount-group');

    function updateType() {
        const type = typeSelect.value;
        typeSuffix.textContent = type === 'fixed' ? 'đ' : '%';
        maxGroup.style.display = type === 'fixed' ? 'none' : 'block';
    }
    typeSelect.addEventListener('change', updateType);
    updateType();
});
</script>
@endpush
