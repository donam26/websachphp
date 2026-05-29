@extends('layouts.app')

@section('title', 'Danh sách sách')

@section('content')
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
        <li class="breadcrumb-item active">Tất cả sách</li>
    </ol>
</nav>

<div class="row g-4">
    {{-- ===== SIDEBAR FILTERS ===== --}}
    <aside class="col-lg-3">
        <div class="filter-sidebar">
            <form action="{{ route('books.index') }}" method="GET" id="filterForm">
                <input type="hidden" name="sort" value="{{ request('sort') }}">

                <div class="filter-block">
                    <h6 class="filter-title">Danh mục</h6>
                    <div class="filter-list">
                        <a href="{{ route('books.index') }}"
                           class="filter-item {{ !request('category') ? 'active' : '' }}">
                            <span>Tất cả</span>
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('books.index', array_merge(request()->all(), ['category' => $category->slug])) }}"
                               class="filter-item {{ request('category') == $category->slug ? 'active' : '' }}">
                                <i class="{{ $category->icon ?? 'bi bi-bookmark' }} me-2 text-muted"></i>
                                <span>{{ $category->name }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="filter-block">
                    <h6 class="filter-title">Khoảng giá</h6>
                    <div class="filter-list">
                        <a href="{{ route('books.index', array_merge(request()->except(['price_from','price_to']))) }}"
                           class="filter-item {{ !request('price_from') && !request('price_to') ? 'active' : '' }}">
                            <span>Tất cả</span>
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->all(), ['price_from' => 0, 'price_to' => 100000])) }}"
                           class="filter-item {{ request('price_to') == '100000' ? 'active' : '' }}">
                            <span>Dưới 100.000đ</span>
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->all(), ['price_from' => 100000, 'price_to' => 200000])) }}"
                           class="filter-item {{ request('price_from') == '100000' && request('price_to') == '200000' ? 'active' : '' }}">
                            <span>100.000đ - 200.000đ</span>
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->all(), ['price_from' => 200000, 'price_to' => 500000])) }}"
                           class="filter-item {{ request('price_from') == '200000' && request('price_to') == '500000' ? 'active' : '' }}">
                            <span>200.000đ - 500.000đ</span>
                        </a>
                        <a href="{{ route('books.index', array_merge(request()->all(), ['price_from' => 500000])) }}"
                           class="filter-item {{ request('price_from') == '500000' && !request('price_to') ? 'active' : '' }}">
                            <span>Trên 500.000đ</span>
                        </a>
                    </div>
                </div>

                <div class="filter-block">
                    <h6 class="filter-title">Tùy chọn lọc nâng cao</h6>
                    <div class="d-flex gap-2 mb-2">
                        <input type="number" name="price_from" class="form-control form-control-sm"
                               placeholder="Từ" value="{{ request('price_from') }}">
                        <input type="number" name="price_to" class="form-control form-control-sm"
                               placeholder="Đến" value="{{ request('price_to') }}">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-funnel me-1"></i> Áp dụng
                    </button>
                    @if(request()->hasAny(['category','price_from','price_to','search']))
                        <a href="{{ route('books.index') }}" class="btn btn-outline-secondary btn-sm w-100 mt-2">
                            <i class="bi bi-x-circle me-1"></i> Xóa bộ lọc
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </aside>

    {{-- ===== BOOK LIST ===== --}}
    <div class="col-lg-9">
        <div class="bg-white p-3 rounded shadow-sm mb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="mb-1">
                        @if(request('search'))
                            Kết quả tìm kiếm cho "<span class="text-primary">{{ request('search') }}</span>"
                        @elseif(isset($currentCategory))
                            Danh mục: <span class="text-primary">{{ $currentCategory->name }}</span>
                        @else
                            Tất cả sách
                        @endif
                    </h5>
                    <p class="text-muted small mb-0">Hiển thị {{ $books->count() }} / {{ $books->total() }} cuốn sách</p>
                </div>
                <form action="{{ route('books.index') }}" method="GET" class="d-flex align-items-center gap-2">
                    @foreach(request()->except('sort') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <label class="text-muted small mb-0 text-nowrap">Sắp xếp:</label>
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="min-width:180px;">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="best_seller" {{ request('sort') == 'best_seller' ? 'selected' : '' }}>Bán chạy</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                    </select>
                </form>
            </div>
        </div>

        @if($books->isEmpty())
            <div class="bg-white rounded shadow-sm empty-state">
                <i class="bi bi-search"></i>
                <h5>Không tìm thấy sách nào</h5>
                <p class="mb-3">Hãy thử thay đổi bộ lọc hoặc tìm kiếm với từ khóa khác.</p>
                <a href="{{ route('books.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-counterclockwise me-1"></i>Xem tất cả sách
                </a>
            </div>
        @else
            <div class="row row-cols-2 row-cols-md-3 row-cols-xl-4 g-3">
                @foreach($books as $book)
                    <div class="col">
                        @include('books._product-card', ['book' => $book])
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $books->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
    .filter-sidebar {
        background: #fff;
        border-radius: 12px;
        padding: 18px;
        box-shadow: var(--shadow-sm);
        position: sticky;
        top: 150px;
    }
    .filter-block { padding-bottom: 16px; margin-bottom: 16px; border-bottom: 1px solid var(--border-light); }
    .filter-block:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .filter-title {
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .filter-list { display: flex; flex-direction: column; gap: 2px; }
    .filter-item {
        display: flex;
        align-items: center;
        padding: 7px 10px;
        font-size: 13.5px;
        color: var(--text-dark);
        border-radius: 6px;
        transition: .2s;
    }
    .filter-item:hover {
        background: var(--bg-soft);
        color: var(--primary);
    }
    .filter-item.active {
        background: rgba(79,70,229,.08);
        color: var(--primary);
        font-weight: 600;
    }
    @media (max-width: 992px) {
        .filter-sidebar { position: static; }
    }
</style>
@endpush
@endsection
