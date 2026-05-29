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
                        <div class="hero-slide s1">
                            <div class="hero-content">
                                <span class="hero-tag"><i class="bi bi-fire"></i> Sách bán chạy</span>
                                <h2>Khám phá thế giới<br>qua từng trang sách</h2>
                                <p>Hàng ngàn đầu sách hay từ các tác giả nổi tiếng. Mua ngay — giảm tới 50%.</p>
                                <a href="{{ route('books.index') }}" class="btn btn-light btn-lg">
                                    <i class="bi bi-book me-2"></i>Khám phá ngay
                                </a>
                            </div>
                            <div class="hero-decor"><i class="bi bi-book"></i></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="hero-slide s2">
                            <div class="hero-content">
                                <span class="hero-tag"><i class="bi bi-stars"></i> Mới phát hành</span>
                                <h2>Sách mới mỗi ngày<br>Giảm giá mỗi tuần</h2>
                                <p>Nhập mã <strong class="hero-code">WELCOME50</strong> giảm ngay 50.000đ cho đơn đầu tiên.</p>
                                <a href="{{ route('books.index', ['sort' => 'latest']) }}" class="btn btn-light btn-lg">
                                    <i class="bi bi-stars me-2"></i>Xem sách mới
                                </a>
                            </div>
                            <div class="hero-decor"><i class="bi bi-bookmark-star"></i></div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="hero-slide s3">
                            <div class="hero-content">
                                <span class="hero-tag"><i class="bi bi-truck"></i> Khuyến mãi</span>
                                <h2>Freeship toàn quốc<br>Đơn từ 250.000đ</h2>
                                <p>Giao hàng nhanh chóng — Đảm bảo nguyên đai nguyên kiện.</p>
                                <a href="{{ route('books.index', ['sort' => 'discount']) }}" class="btn btn-light btn-lg">
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
                <a href="{{ route('books.index') }}" class="side-banner side-banner-amber flex-fill">
                    <div>
                        <small>FREESHIP</small>
                        <h6>Đơn từ 250K</h6>
                        <span class="side-link">Mua sắm ngay <i class="bi bi-arrow-right"></i></span>
                    </div>
                    <i class="bi bi-truck side-icon"></i>
                </a>
                <a href="{{ route('books.index', ['sort' => 'discount']) }}" class="side-banner side-banner-indigo flex-fill">
                    <div>
                        <small>GIẢM ĐẾN</small>
                        <h6>50% sách hot</h6>
                        <span class="side-link">Săn deal ngay <i class="bi bi-arrow-right"></i></span>
                    </div>
                    <i class="bi bi-percent side-icon"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ===== FEATURE STRIP ===== --}}
<section class="feature-strip mb-4">
    <div class="row g-2">
        <div class="col-md-3 col-6">
            <div class="feature-item">
                <span class="feature-ico ico-indigo"><i class="bi bi-truck"></i></span>
                <div><strong>Giao hàng nhanh</strong><small>Toàn quốc 2-5 ngày</small></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="feature-item">
                <span class="feature-ico ico-success"><i class="bi bi-shield-check"></i></span>
                <div><strong>Sách chính hãng</strong><small>100% bản quyền</small></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="feature-item">
                <span class="feature-ico ico-amber"><i class="bi bi-arrow-counterclockwise"></i></span>
                <div><strong>Đổi trả 7 ngày</strong><small>Miễn phí đổi trả</small></div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="feature-item">
                <span class="feature-ico ico-info"><i class="bi bi-headset"></i></span>
                <div><strong>Hỗ trợ 24/7</strong><small>Hotline 1900 6364</small></div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CATEGORIES ===== --}}
@if(isset($categories) && $categories->count())
<section class="mb-4">
    <div class="section-header">
        <h2 class="section-title"><span class="title-icon"><i class="bi bi-grid-3x3-gap-fill"></i></span>Danh mục sách</h2>
        <a href="{{ route('books.index') }}" class="section-link">Xem tất cả <i class="bi bi-chevron-right"></i></a>
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
        <h2 class="section-title"><span class="title-icon"><i class="bi bi-stars"></i></span>Sách mới phát hành</h2>
        <a href="{{ route('books.index', ['sort' => 'latest']) }}" class="section-link">Xem tất cả <i class="bi bi-chevron-right"></i></a>
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
            <a href="{{ route('books.index') }}" class="promo-banner promo-1">
                <div class="promo-content">
                    <small>FLASH SALE</small>
                    <h4>Sách Văn học giảm đến 50%</h4>
                    <span class="promo-cta">Mua ngay <i class="bi bi-arrow-right"></i></span>
                </div>
                <i class="bi bi-book-half promo-decor"></i>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('books.index') }}" class="promo-banner promo-2">
                <div class="promo-content">
                    <small>HOT DEAL</small>
                    <h4>Sách Kinh tế — Quà tặng kèm</h4>
                    <span class="promo-cta">Khám phá <i class="bi bi-arrow-right"></i></span>
                </div>
                <i class="bi bi-gift promo-decor"></i>
            </a>
        </div>
    </div>
</section>

{{-- ===== BEST SELLERS ===== --}}
@if(isset($bestSellers) && $bestSellers->count())
<section class="mb-4">
    <div class="section-header">
        <h2 class="section-title"><span class="title-icon ico-amber"><i class="bi bi-fire"></i></span>Sách bán chạy</h2>
        <a href="{{ route('books.index', ['sort' => 'best_seller']) }}" class="section-link">Xem tất cả <i class="bi bi-chevron-right"></i></a>
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
        <h2 class="section-title"><span class="title-icon ico-amber"><i class="bi bi-tag-fill"></i></span>Sách khuyến mãi</h2>
        <a href="{{ route('books.index', ['sort' => 'discount']) }}" class="section-link">Xem tất cả <i class="bi bi-chevron-right"></i></a>
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
        <div class="row align-items-center g-3 position-relative" style="z-index:2;">
            <div class="col-md-7">
                <h3 class="mb-2 text-white"><i class="bi bi-envelope-paper-heart me-2"></i>Đăng ký nhận thông tin</h3>
                <p class="text-white-50 mb-0">Nhận ngay <strong class="text-amber">mã giảm 50K</strong> cho đơn hàng đầu tiên và thông tin sách mới mỗi tuần.</p>
            </div>
            <div class="col-md-5">
                <form class="d-flex gap-2">
                    <input type="email" class="form-control form-control-lg" placeholder="Nhập email của bạn">
                    <button type="submit" class="btn btn-warning btn-lg text-nowrap">Đăng ký</button>
                </form>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    /* ===== Hero carousel ===== */
    .hero-carousel { border-radius: var(--radius); overflow: hidden; height: 364px; box-shadow: var(--shadow-md); }
    .hero-slide {
        height: 364px;
        position: relative;
        display: flex;
        align-items: center;
        padding: 0 56px;
        color: #fff;
        overflow: hidden;
    }
    .hero-slide.s1 { background: linear-gradient(120deg, #4f46e5 0%, #3730a3 100%); }
    .hero-slide.s2 { background: linear-gradient(120deg, #7c3aed 0%, #4338ca 100%); }
    .hero-slide.s3 { background: linear-gradient(120deg, #0d9488 0%, #134e4a 100%); }
    .hero-slide::before {
        content: '';
        position: absolute;
        inset: 0;
        background:
            radial-gradient(circle at 78% 18%, rgba(255,255,255,.18), transparent 42%),
            radial-gradient(circle at 12% 95%, rgba(255,255,255,.10), transparent 38%);
    }
    .hero-content { z-index: 2; max-width: 520px; }
    .hero-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.16);
        border: 1px solid rgba(255,255,255,.25);
        color: #fff;
        padding: 6px 15px;
        border-radius: var(--radius-pill);
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 16px;
        backdrop-filter: blur(10px);
    }
    .hero-slide h2 { font-size: 39px; font-weight: 800; margin-bottom: 14px; line-height: 1.18; letter-spacing: -1px; text-shadow: 0 2px 16px rgba(0,0,0,.12); }
    .hero-slide p { font-size: 16px; opacity: .95; margin-bottom: 24px; max-width: 430px; }
    .hero-code { background: rgba(255,255,255,.22); padding: 1px 8px; border-radius: 6px; font-weight: 700; }
    .hero-slide .btn-light { box-shadow: var(--shadow-md); }
    .hero-decor {
        position: absolute;
        right: 6%;
        bottom: -30px;
        font-size: 270px;
        opacity: .12;
        line-height: 1;
        z-index: 1;
        transform: rotate(-8deg);
    }
    .carousel-indicators { margin-bottom: 14px; }
    .carousel-indicators [data-bs-target] { width: 28px; height: 4px; border-radius: 4px; }
    @media (max-width: 768px) {
        .hero-carousel, .hero-slide { height: 250px; }
        .hero-slide { padding: 0 26px; }
        .hero-slide h2 { font-size: 23px; }
        .hero-slide p { font-size: 13px; }
        .hero-decor { font-size: 150px; }
    }

    /* ===== Side banners ===== */
    .side-banner {
        border-radius: var(--radius);
        padding: 22px;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 174px;
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
        transition: all .25s ease;
    }
    .side-banner:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); color: #fff; }
    .side-banner small { font-size: 11px; font-weight: 700; letter-spacing: 1.5px; opacity: .92; }
    .side-banner h6 { font-size: 22px; font-weight: 800; margin: 6px 0 12px; }
    .side-banner .side-link { font-size: 12.5px; font-weight: 600; opacity: .95; }
    .side-banner .side-icon { font-size: 58px; opacity: .28; }
    .side-banner-amber { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .side-banner-indigo { background: linear-gradient(135deg, #4f46e5, #3730a3); }

    /* ===== Feature strip ===== */
    .feature-strip { background: var(--surface); padding: 12px; border-radius: var(--radius); box-shadow: var(--shadow-sm); border: 1px solid var(--border); }
    .feature-item { display: flex; align-items: center; gap: 14px; padding: 10px; }
    .feature-ico {
        width: 50px; height: 50px; flex-shrink: 0;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 24px;
    }
    .ico-indigo { background: var(--primary-50); color: var(--primary); }
    .ico-success { background: var(--success-soft); color: var(--success); }
    .ico-amber { background: var(--accent-soft); color: var(--accent-dark); }
    .ico-info { background: #e0f2fe; color: var(--info); }
    .feature-item strong { display: block; font-size: 14px; }
    .feature-item small { color: var(--text-muted); font-size: 12px; display: block; }

    /* ===== Category tile ===== */
    .category-tile {
        background: var(--surface);
        border-radius: var(--radius);
        padding: 20px 12px;
        text-align: center;
        display: block;
        transition: all .22s ease;
        border: 1px solid var(--border);
        height: 100%;
    }
    .category-tile:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--primary-light); }
    .category-icon {
        width: 58px; height: 58px;
        margin: 0 auto 12px;
        background: var(--primary-50);
        color: var(--primary);
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 25px;
        transition: all .22s ease;
    }
    .category-tile:hover .category-icon { background: var(--primary); color: #fff; }
    .category-name { font-weight: 700; color: var(--text); font-size: 13px; }

    /* ===== Promo banners ===== */
    .promo-banner {
        height: 158px;
        border-radius: var(--radius);
        padding: 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        transition: all .25s ease;
    }
    .promo-banner:hover { transform: translateY(-3px); box-shadow: var(--shadow-lg); color: #fff; }
    .promo-banner.promo-1 { background: linear-gradient(120deg, #4f46e5, #6d28d9); }
    .promo-banner.promo-2 { background: linear-gradient(120deg, #f59e0b, #d97706); }
    .promo-decor { position: absolute; right: 18px; bottom: -18px; font-size: 130px; opacity: .16; }
    .promo-content { position: relative; z-index: 2; }
    .promo-content small { font-size: 11px; letter-spacing: 2px; font-weight: 700; opacity: .92; }
    .promo-content h4 { font-size: 22px; font-weight: 800; margin: 8px 0 14px; letter-spacing: -.4px; }
    .promo-cta {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.95); color: var(--text);
        padding: 7px 16px; border-radius: var(--radius-pill);
        font-size: 13px; font-weight: 700;
    }

    /* ===== Newsletter ===== */
    .newsletter-box {
        background: linear-gradient(120deg, #1e1b4b 0%, #312e81 100%);
        border-radius: var(--radius);
        padding: 40px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .newsletter-box::before {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(245,158,11,.22), transparent 40%);
    }
    .text-amber { color: #fcd34d !important; }
    @media (max-width: 768px) {
        .newsletter-box { padding: 26px; }
    }
</style>
@endpush

@endsection
