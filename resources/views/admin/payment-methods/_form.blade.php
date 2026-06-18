@php $pm = $paymentMethod ?? null; @endphp

<div class="mb-2 text-start">
    <label class="form-label small text-muted">Mã (code) <span class="text-danger">*</span></label>
    @if($pm)
        <input type="text" class="form-control" value="{{ $pm->code }}" disabled>
        <small class="text-muted">Mã là khoá kỹ thuật, không thể đổi sau khi tạo (đơn hàng tham chiếu theo mã).</small>
    @else
        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror"
               value="{{ old('code') }}" placeholder="vd: bank_transfer" required>
        @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
        <small class="text-muted">Chữ thường, số, gạch ngang/gạch dưới.</small>
    @endif
</div>

<div class="mb-2 text-start">
    <label class="form-label small text-muted">Tên hiển thị <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $pm->name ?? '') }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-2 text-start">
    <label class="form-label small text-muted">Mô tả ngắn</label>
    <input type="text" name="description" class="form-control"
           value="{{ old('description', $pm->description ?? '') }}" placeholder="Hiển thị ở trang thanh toán">
</div>

<div class="row g-2">
    <div class="col-8 text-start">
        <label class="form-label small text-muted">Icon (Bootstrap Icons)</label>
        <input type="text" name="icon" class="form-control"
               value="{{ old('icon', $pm->icon ?? '') }}" placeholder="vd: bi-cash-coin text-warning">
        <small class="text-muted">Tên class icon, vd <code>bi-bank</code>.</small>
    </div>
    <div class="col-4 text-start">
        <label class="form-label small text-muted">Thứ tự</label>
        <input type="number" name="sort_order" class="form-control" min="0" max="9999"
               value="{{ old('sort_order', $pm->sort_order ?? 0) }}">
    </div>
</div>

<div class="form-check form-switch mt-3 text-start">
    <input type="hidden" name="is_active" value="0">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_{{ $pm->id ?? 'new' }}"
           {{ old('is_active', $pm->is_active ?? true) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active_{{ $pm->id ?? 'new' }}">Kích hoạt (hiển thị cho khách chọn)</label>
</div>
