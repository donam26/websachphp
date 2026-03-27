@extends('layouts.app')

@section('title', 'San pham thoi trang')

@section('content')
<div class="fs-section">
    <div class="container">
        <div class="row g-4">
            <!-- Filter Sidebar -->
            <div class="col-lg-3">
                <div class="fs-card" style="position:sticky;top:100px;">
                    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--fs-border);display:flex;align-items:center;justify-content:space-between;">
                        <h6 style="margin:0;font-family:'Playfair Display',serif;font-weight:600;font-size:1rem;">Bo loc</h6>
                        <i class="bi bi-sliders" style="color:var(--fs-accent);"></i>
                    </div>
                    <div style="padding:1.5rem;">
                        <form action="{{ route('books.index') }}" method="GET">
                            <div class="mb-3">
                                <label class="form-label">Tim kiem</label>
                                <div style="position:relative;">
                                    <input type="text" name="search" class="form-control" style="padding-left:2.5rem;"
                                           placeholder="Ten, thuong hieu..."
                                           value="{{ request('search') }}">
                                    <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--fs-muted);"></i>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Danh muc</label>
                                <select name="category" class="form-select">
                                    <option value="">Tat ca danh muc</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gioi tinh</label>
                                <select name="gender" class="form-select">
                                    <option value="">Tat ca</option>
                                    <option value="nam" {{ request('gender') == 'nam' ? 'selected' : '' }}>Nam</option>
                                    <option value="nu" {{ request('gender') == 'nu' ? 'selected' : '' }}>Nu</option>
                                    <option value="unisex" {{ request('gender') == 'unisex' ? 'selected' : '' }}>Unisex</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kich co</label>
                                <select name="size" class="form-select">
                                    <option value="">Tat ca size</option>
                                    @foreach(['S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $s)
                                        <option value="{{ $s }}" {{ request('size') == $s ? 'selected' : '' }}>{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sap xep</label>
                                <select name="sort" class="form-select">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Moi nhat</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Gia tang dan</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Gia giam dan</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Ten A-Z</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Ten Z-A</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Khoang gia</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" name="price_from" class="form-control" placeholder="Tu" value="{{ request('price_from') }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" name="price_to" class="form-control" placeholder="Den" value="{{ request('price_to') }}">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="fs-btn fs-btn-primary w-100">
                                <i class="bi bi-search"></i> Tim kiem
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Product Grid -->
            <div class="col-lg-9">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
                    <div>
                        <h1 class="fs-section-title" style="margin-bottom:0.25rem;">Bo suu tap</h1>
                        <p style="color:var(--fs-muted);font-size:0.875rem;margin:0;">
                            Hien thi {{ $books->count() }} / {{ $books->total() }} san pham
                        </p>
                    </div>
                </div>

                @if($books->isEmpty())
                    <div class="fs-card" style="text-align:center;padding:4rem 2rem;">
                        <i class="bi bi-search" style="font-size:3rem;color:var(--fs-border);"></i>
                        <h5 style="margin-top:1rem;font-family:'Playfair Display',serif;">Khong tim thay san pham</h5>
                        <p style="color:var(--fs-muted);font-size:0.875rem;">Thu thay doi bo loc de tim san pham phu hop.</p>
                    </div>
                @else
                    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                        @foreach($books as $book)
                        <div class="col">
                            <div class="product-card fs-card" style="height:100%;">
                                <div class="product-img-wrap">
                                    <a href="{{ route('books.show', $book) }}">
                                        <img src="{{ asset('storage/books/'.$book->image) }}"
                                             alt="{{ $book->title }}" loading="lazy">
                                    </a>
                                    @if($book->gender)
                                    <span class="product-gender-tag">
                                        {{ $book->gender == 'nam' ? 'Nam' : ($book->gender == 'nu' ? 'Nu' : 'Unisex') }}
                                    </span>
                                    @endif
                                    <div class="product-overlay">
                                        <a href="{{ route('books.show', $book) }}" class="product-overlay-btn">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($book->quantity > 0)
                                        <button class="product-overlay-btn" data-add-to-wishlist="{{ $book->id }}">
                                            <i class="bi bi-heart"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div style="padding:1.25rem;">
                                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
                                        <span class="fs-badge fs-badge-accent" style="font-size:0.65rem;">
                                            {{ $book->category ? $book->category->name : 'Chua phan loai' }}
                                        </span>
                                        @if($book->quantity > 0)
                                            <span class="fs-badge fs-badge-success">Con hang</span>
                                        @else
                                            <span class="fs-badge fs-badge-danger">Het hang</span>
                                        @endif
                                    </div>
                                    <h5 class="product-title">
                                        <a href="{{ route('books.show', $book) }}">{{ $book->title }}</a>
                                    </h5>
                                    <p style="color:var(--fs-muted);font-size:0.8rem;margin-bottom:0.5rem;">
                                        {{ $book->brand ?: $book->author }}
                                    </p>
                                    @if($book->sizes)
                                    <div style="margin-bottom:0.75rem;display:flex;flex-wrap:wrap;gap:4px;">
                                        @foreach(explode(',', $book->sizes) as $size)
                                            <span style="padding:2px 8px;border-radius:4px;background:var(--fs-surface);font-size:0.7rem;font-weight:500;color:var(--fs-muted);">{{ trim($size) }}</span>
                                        @endforeach
                                    </div>
                                    @endif
                                    <div style="display:flex;justify-content:space-between;align-items:center;">
                                        <span style="font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--fs-primary);">
                                            {{ number_format($book->price) }}d
                                        </span>
                                        @if($book->quantity > 0)
                                        <a href="{{ route('books.show', $book) }}" class="fs-btn fs-btn-primary" style="padding:0.5rem 1rem;font-size:0.75rem;">
                                            <i class="bi bi-bag-plus"></i> Mua
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center" style="margin-top:2.5rem;">
                        {{ $books->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.product-card {
    border-radius: var(--fs-radius) !important;
    overflow: hidden;
}

.product-card:hover { transform: translateY(-6px); }

.product-img-wrap {
    position: relative;
    overflow: hidden;
    aspect-ratio: 3/4;
    background: #f0eeeb;
}

.product-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.product-card:hover .product-img-wrap img {
    transform: scale(1.08);
}

.product-gender-tag {
    position: absolute;
    top: 12px;
    left: 12px;
    padding: 4px 12px;
    background: var(--fs-primary);
    color: #fff;
    font-size: 0.65rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-radius: 100px;
}

.product-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 1rem;
    display: flex;
    justify-content: center;
    gap: 8px;
    background: linear-gradient(transparent, rgba(0,0,0,0.3));
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.4s ease;
}

.product-card:hover .product-overlay {
    opacity: 1;
    transform: translateY(0);
}

.product-overlay-btn {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    border: none;
    background: rgba(255,255,255,0.95);
    color: var(--fs-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    backdrop-filter: blur(10px);
}

.product-overlay-btn:hover {
    background: var(--fs-accent);
    color: #fff;
    transform: scale(1.1);
}

.product-title {
    font-family: 'DM Sans', sans-serif;
    font-size: 0.92rem;
    font-weight: 600;
    line-height: 1.4;
    height: 2.6em;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 0.35rem;
}

.product-title a {
    color: var(--fs-text);
    text-decoration: none;
    transition: color 0.3s ease;
}

.product-title a:hover { color: var(--fs-accent); }
</style>
@endpush
