{{-- Expects: $supplier (Supplier|null) --}}
<div class="mb-3">
    <label class="form-label">Tên nhà cung cấp <span class="text-danger">*</span></label>
    <input type="text" class="form-control" name="name" value="{{ old('name', $supplier->name ?? '') }}" required placeholder="VD: Nhà xuất bản Trẻ">
</div>
<div class="row g-2">
    <div class="col-md-6 mb-3">
        <label class="form-label">Số điện thoại</label>
        <input type="text" class="form-control" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" placeholder="VD: 028 3931 6289">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" class="form-control" name="email" value="{{ old('email', $supplier->email ?? '') }}" placeholder="VD: contact@nxb.com.vn">
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Địa chỉ</label>
    <input type="text" class="form-control" name="address" value="{{ old('address', $supplier->address ?? '') }}" placeholder="Địa chỉ nhà cung cấp">
</div>
<div class="mb-3">
    <label class="form-label">Ghi chú</label>
    <textarea class="form-control" name="note" rows="2" maxlength="1000" placeholder="Ghi chú thêm">{{ old('note', $supplier->note ?? '') }}</textarea>
</div>
<div class="mb-0">
    <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
    <select class="form-select" name="status" required>
        <option value="active" {{ old('status', $supplier->status ?? 'active') === 'active' ? 'selected' : '' }}>Đang hợp tác</option>
        <option value="inactive" {{ old('status', $supplier->status ?? 'active') === 'inactive' ? 'selected' : '' }}>Ngừng hợp tác</option>
    </select>
</div>
