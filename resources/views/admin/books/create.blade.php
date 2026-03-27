@extends('layouts.admin')

@section('title', 'Them san pham moi')

@section('content')
<div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem;">
    <a href="{{ route('admin.books.index') }}" class="ad-btn ad-btn-outline ad-btn-icon" style="width:40px;height:40px;">
        <i class="bi bi-arrow-left"></i>
    </a>
    <h1 style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:var(--ad-primary);margin:0;">
        Them san pham moi
    </h1>
</div>

<form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="ad-card">
                <div class="ad-card-header"><h5>Thong tin san pham</h5></div>
                <div class="ad-card-body">
                    <div class="mb-3">
                        <label class="form-label">Ten san pham</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" required placeholder="VD: Ao Polo Classic, Quan Jeans Slim Fit...">
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thuong hieu</label>
                        <input type="text" name="brand" class="form-control @error('brand') is-invalid @enderror"
                               value="{{ old('brand') }}" required placeholder="VD: Nike, Adidas, Zara, H&M...">
                        @error('brand') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mo ta san pham</label>
                        <textarea name="description" rows="5" class="form-control @error('description') is-invalid @enderror"
                                  required placeholder="Mo ta chi tiet ve san pham, chat lieu, kieu dang...">{{ old('description') }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Kich co</label>
                            <input type="text" name="sizes" class="form-control @error('sizes') is-invalid @enderror"
                                   value="{{ old('sizes', 'S,M,L,XL,XXL') }}" placeholder="VD: S,M,L,XL,XXL">
                            @error('sizes') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mau sac</label>
                            <input type="text" name="colors" class="form-control @error('colors') is-invalid @enderror"
                                   value="{{ old('colors') }}" placeholder="VD: Den,Trang,Xanh navy">
                            @error('colors') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3 mt-3">
                        <label class="form-label">Chat lieu</label>
                        <input type="text" name="material" class="form-control @error('material') is-invalid @enderror"
                               value="{{ old('material') }}" placeholder="VD: Cotton 100%, Polyester...">
                        @error('material') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="ad-card mb-4">
                <div class="ad-card-header"><h5>Hinh anh & Phan loai</h5></div>
                <div class="ad-card-body">
                    <div class="mb-3">
                        <label class="form-label">Anh san pham</label>
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*" required>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Danh muc</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">Chon danh muc</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gioi tinh</label>
                        <select name="gender" class="form-select @error('gender') is-invalid @enderror" required>
                            <option value="unisex" {{ old('gender') === 'unisex' ? 'selected' : '' }}>Unisex</option>
                            <option value="nam" {{ old('gender') === 'nam' ? 'selected' : '' }}>Nam</option>
                            <option value="nu" {{ old('gender') === 'nu' ? 'selected' : '' }}>Nu</option>
                        </select>
                        @error('gender') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="ad-card mb-4">
                <div class="ad-card-header"><h5>Gia & Kho hang</h5></div>
                <div class="ad-card-body">
                    <div class="mb-3">
                        <label class="form-label">Gia ban (VND)</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                               value="{{ old('price') }}" min="0" step="1000" required placeholder="VD: 299000">
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">So luong trong kho</label>
                        <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                               value="{{ old('quantity') }}" min="0" required>
                        @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trang thai</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="available" {{ old('status') === 'available' ? 'selected' : '' }}>Con hang</option>
                            <option value="unavailable" {{ old('status') === 'unavailable' ? 'selected' : '' }}>Het hang</option>
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div style="display:flex;gap:8px;">
                <a href="{{ route('admin.books.index') }}" class="ad-btn ad-btn-outline" style="flex:1;justify-content:center;">Huy</a>
                <button type="submit" class="ad-btn ad-btn-primary" style="flex:2;justify-content:center;">
                    <i class="bi bi-plus-lg"></i> Them san pham
                </button>
            </div>
        </div>
    </div>
</form>
@endsection
