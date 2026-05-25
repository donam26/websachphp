@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
{{-- ===== HERO BANNER ===== --}}
<section class="hero-section mb-4">
    <div class="row g-3">
        <div class="col-lg-9">
            <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="hero-slide" style="background-image: linear-gradient(135deg, rgba(201,33,39,.85), rgba(165,26,31,.85));">
                            <div class="hero-content">
                                <span class="hero-tag">Sách bán chạy</span>
                                <h2>Khám phá thế giới<br>qua từng trang sách</h2>
                                <p>Hàng ngàn đầu sách hay từ các tác giả nổi tiếng. Mua ngay - giảm tới 50%.</p>
                                <a href="{{ route('books.index') }}" class="btn btn-light btn-lg fw-bold">
                                    <i class="bi bi-book me-2"></i>Khám phá ngay
                                </a>
                            </div>
                            <div class="hero-decor">
                                <i class="bi bi-book"></i>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="hero-slide" style="background-image: linear-gradient(135deg, rgba(42,82,152,.9), rgba(30,60,114,.9));">
                            <div class="hero-content">
                                <span class="hero-tag">Mới phát hành</span>
                                <h2>Sách mới mỗi ngày<br>Giảm giá mỗi tuần</h2>
                                <p>Nhập mã <strong>WELCOME50</strong> giảm ngay 50.000đ cho đơn đầu tiên.</p>
                                <a href="{{ route('books.index', ['sort' => 'latest']) }}" class="btn btn-warning btn-lg fw-bold">
                                    <i class="bi bi-stars me-2"></i>Xem sách mới
                                </a>
                            </div>
                            <div class="hero-decor"><i class="bi bi-bookmark-star"></i></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="hero-slide" style="background-image: linear-gradient(135deg, rgba(28,166,114,.9), rgba(5,150,105,.9));">
                            <div class="hero-content">
                                <span class="hero-tag">Khuyến mãi</span>
                                <h2>Freeship toàn quốc<br>Đơn từ 250.000đ</h2>
                                <p>Giao hàng nhanh chóng — Đảm bảo nguyên đai nguyên kiện.</p>
                                <a href="{{ route('books.index', ['sort' => 'discount']) }}" class="btn btn-light btn-lg fw-bold">
                                    <i class="bi bi-tag me-2"></i>Xem khuyến mãi
                                </a>
                            </div>
                            <div class="hero-decor"><i class="bi bi-truck"></i></div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
        <div class="col-lg-3 d-none d-lg-block">
            <div class="d-flex flex-column gap-3 h-100">
                <div class="side-banner side-banner-orange flex-fill">
                    <div>
                        <small>FREESHIP</small>
                        <h6>Đơn từ 250K</h6>
                    </div>
                    <i class="bi bi-truck"></i>
                </div>
                <div class="side-banner side-banner-purple flex-fill">
                    <div>
                        <small>GIẢM ĐẾN</small>
                        <h6>50% sách hot</h6>
                    </div>
                    <i class="bi bi-percent"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== FEATURE STRIP ===== --}}
<section class="feature-strip mb-4">
    <div class="row g-3">
        <div class="col-md-3 col-6">
            <div class="feature-item">
                <i class="bi bi-truck text-primary"></i>
                <div><strong>Giao hàng nhanh</strong><small>Toàn quốc 2-5 ngày</small></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="feature-item">
                <i class="bi bi-shield-check text-success"></i>
                <div><strong>Sách chính hãng</strong><small>100% bản quyền</small></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="feature-item">
                <i class="bi bi-arrow-counterclockwise text-warning"></i>
                <div><strong>Đổi trả 7 ngày</strong><small>Miễn phí đổi trả</small></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="feature-item">
                <i class="bi bi-headset text-info"></i>
                <div><strong>Hỗ trợ 24/7</strong><small>Hotline 1900 6364</small></div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CATEGORIES ===== --}}
@if(isset($categories) && $categories->count())
<section class="mb-4">
    <div class="section-header">
        <h2 class="section-title">Danh mục sách</h2>
        <a href="{{ route('books.index') }}" class="text-decoration-none text-muted small">
            Xem tất cả <i class="bi bi-chevron-right"></i>
        </a>
    </div>
    <div class="row g-3">
        @foreach($categories as $category)
        <div class="col-lg-2 col-md-3 col-4">
            <a href="{{ route('books.category', $category->slug) }}" class="category-tile">
                <div class="category-icon"><i class="{{ $category->icon ?? 'bi bi-book' }}"></i></div>
                <div class="category-name">{{ $category->name }}</div>
            </a>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== NEW BOOKS ===== --}}
@if(isset($newBooks) && $newBooks->count())
<section class="mb-4">
    <div class="section-header">
        <h2 class="section-title"><i class="bi bi-stars text-warning me-2"></i>Sách mới phát hành</h2>
        <a href="{{ route('books.index', ['sort' => 'latest']) }}" class="text-decoration-none text-muted small">
            Xem tất cả <i class="bi bi-chevron-right"></i>
        </a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
        @foreach($newBooks as $book)
        <div class="col">
            @include('books._product-card', ['book' => $book])
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== PROMO BANNER ===== --}}
<section class="mb-4">
    <div class="row g-3">
        <div class="col-md-6">
            <div class="promo-banner promo-1">
                <div class="promo-content">
                    <small>FLASH SALE</small>
                    <h4>Sách Văn học giảm đến 50%</h4>
                    <a href="{{ route('books.index') }}" class="btn btn-light btn-sm fw-bold">Mua ngay <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="promo-banner promo-2">
                <div class="promo-content">
                    <small>HOT DEAL</small>
                    <h4>Sách Kinh tế - Quà tặng kèm</h4>
                    <a href="{{ route('books.index') }}" class="btn btn-light btn-sm fw-bold">Khám phá <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== BEST SELLERS ===== --}}
@if(isset($bestSellers) && $bestSellers->count())
<section class="mb-4">
    <div class="section-header">
        <h2 class="section-title"><i class="bi bi-fire text-danger me-2"></i>Sách bán chạy</h2>
        <a href="{{ route('books.index', ['sort' => 'best_seller']) }}" class="text-decoration-none text-muted small">
            Xem tất cả <i class="bi bi-chevron-right"></i>
        </a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
        @foreach($bestSellers as $book)
        <div class="col">
            @include('books._product-card', ['book' => $book])
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== DISCOUNT BOOKS ===== --}}
@if(isset($discountBooks) && $discountBooks->count())
<section class="mb-4">
    <div class="section-header">
        <h2 class="section-title"><i class="bi bi-tag-fill text-danger me-2"></i>Sách khuyến mãi</h2>
        <a href="{{ route('books.index', ['sort' => 'discount']) }}" class="text-decoration-none text-muted small">
            Xem tất cả <i class="bi bi-chevron-right"></i>
        </a>
    </div>
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
        @foreach($discountBooks as $book)
        <div class="col">
            @include('books._product-card', ['book' => $book])
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- ===== NEWSLETTER ===== --}}
<section class="mb-2 mt-5">
    <div class="newsletter-box">
        <div class="row align-items-center g-3">
            <div class="col-md-7">
                <h3 class="mb-2 text-white"><i class="bi bi-envelope-paper-heart me-2"></i>Đăng ký nhận thông tin</h3>
                <p class="text-white-50 mb-0">Nhận ngay <strong class="text-warning">mã giảm 50K</strong> cho đơn hàng đầu tiên và thông tin sách mới mỗi tuần.</p>
            </div>
            <div class="col-md-5">
                <form class="d-flex gap-2">
                    <input type="email" class="form-control form-control-lg" placeholder="Nhập email của bạn">
                    <button type="submit" class="btn btn-warning btn-lg text-nowrap fw-bold">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* Hero carousel */
    .hero-carousel { border-radius: 12px; overflow: hidden; height: 360px; box-shadow: var(--shadow-md); }
    .hero-slide {
        height: 360px;
        background-size: cover;
        background-position: center;
        position: relative;
        display: flex;
        align-items: center;
        padding: 0 56px;
        color: #fff;
    }
    .hero-content { z-index: 2; max-width: 500px; }
    .hero-tag {
        display: inline-block;
        background: rgba(255,255,255,.2);
        color: #fff;
        padding: 5px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 14px;
        backdrop-filter: blur(10px);
    }
    .hero-slide h2 { font-size: 38px; font-weight: 800; margin-bottom: 14px; line-height: 1.2; }
    .hero-slide p { font-size: 16px; opacity: .95; margin-bottom: 22px; max-width: 420px; }
    .hero-decor {
        position: absolute;
        right: 8%;
        font-size: 220px;
        opacity: .1;
        line-height: 1;
    }
    @media (max-width: 768px) {
        .hero-carousel, .hero-slide { height: 240px; }
        .hero-slide { padding: 0 24px; }
        .hero-slide h2 { font-size: 22px; }
        .hero-slide p { font-size: 13px; }
        .hero-decor { font-size: 120px; }
    }

    /* Side banners */
    .side-banner {
        border-radius: 12px;
        padding: 20px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 173px;
        box-shadow: var(--shadow-sm);
    }
    .side-banner small { font-size: 11px; font-weight: 600; letter-spacing: 1px; opacity: .9; }
    .side-banner h6 { font-size: 20px; font-weight: 800; margin: 6px 0 0; }
    .side-banner i { font-size: 56px; opacity: .35; }
    .side-banner-orange { background: linear-gradient(135deg, #ff9800, #f57c00); }
    .side-banner-purple { background: linear-gradient(135deg, #7c3aed, #5b21b6); }

    /* Feature strip */
    .feature-strip { background: #fff; padding: 18px; border-radius: 12px; box-shadow: var(--shadow-sm); }
    .feature-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 10px;
    }
    .feature-item i { font-size: 32px; }
    .feature-item strong { display: block; font-size: 14px; }
    .feature-item small { color: var(--text-muted); font-size: 12px; display: block; }

    /* Category tile */
    .category-tile {
        background: #fff;
        border-radius: 12px;
        padding: 18px 12px;
        text-align: center;
        display: block;
        transition: all .2s ease;
        border: 1px solid var(--border-light);
        height: 100%;
    }
    .category-tile:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-md);
        border-color: var(--primary);
    }
    .category-icon {
        width: 56px;
        height: 56px;
        margin: 0 auto 10px;
        background: rgba(201,33,39,.08);
        color: var(--primary);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .category-name { font-weight: 600; color: var(--text-dark); font-size: 13px; }

    /* Promo banners */
    .promo-banner {
        height: 150px;
        border-radius: 12px;
        padding: 26px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .promo-banner.promo-1 {
        background: linear-gradient(135deg, #c92127 0%, #ff4d57 100%);
    }
    .promo-banner.promo-2 {
        background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
    }
    .promo-banner::after {
        content: '';
        position: absolute;
        right: -40px;
        top: -40px;
        width: 180px;
        height: 180px;
        background: rgba(255,255,255,.08);
        border-radius: 50%;
    }
    .promo-content small { font-size: 11px; letter-spacing: 2px; font-weight: 700; opacity: .9; }
    .promo-content h4 { font-size: 22px; font-weight: 700; margin: 6px 0 14px; }

    /* Newsletter */
    .newsletter-box {
        background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
        border-radius: 16px;
        padding: 40px;
        color: #fff;
    }
    @media (max-width: 768px) {
        .newsletter-box { padding: 24px; }
    }
</style>
@endpush

@endsection
