@extends('custom.master')
@section('content')

 <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg"><i class="fas fa-briefcase-medical"></i></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Categories</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('home') }}"><i class="fas fa-house"></i> Home</a></li>
                        <li class="active">Categories</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->

        <!-- categories area -->
        <div class="shop-area bg py-90">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title text-center mb-50">
                            <h2>Shop by Category</h2>
                            <p>Explore our wide range of medical and healthcare categories</p>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                   @forelse($categories as $category)
    <div class="col-lg-3 col-md-4 col-sm-6">
        <div class="category-card">
            <div class="category-img">
                <a href="{{ route('category.show', $category->slug) }}">
                    <img src="{{ $category->image_url }}"
                         alt="{{ $category->name }}"
                         onerror="this.src='{{ asset('assets/img/category/default.jpg') }}'">
                </a>

                <div class="category-overlay">
                    <div class="category-content">
                        <h4>
                            <a href="{{ route('category.show', $category->slug) }}">
                                {{ $category->name }}
                            </a>
                        </h4>
                        <p>{{ $category->products_count ?? 0 }} Products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info text-center">
            <h5>No categories available</h5>
            <p>Please check back later for new categories</p>
        </div>
    </div>
@endforelse
                </div>
            </div>
        </div>
        <!-- categories area end -->

        @push('styles')
      <style>
    .category-card {
        position: relative;
        border-radius: 22px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 14px 35px rgba(15, 23, 42, 0.10);
        transition: all 0.35s ease;
        height: 270px;
        border: 1px solid rgba(226, 232, 240, 0.9);
    }

    .category-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 22px 55px rgba(15, 23, 42, 0.18);
    }

    .category-img {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: #f8fafc;
    }

    .category-img a {
        display: block;
        width: 100%;
        height: 100%;
    }

    .category-img img {
        width: 100%;
        height: 100%;
        display: block;
        object-fit: cover;
        object-position: center;
        transition: transform 0.6s ease, filter 0.6s ease;
    }

    .category-card:hover .category-img img {
        transform: scale(1.08);
        filter: brightness(0.82);
    }

    .category-overlay {
        position: absolute;
        inset: 0;
        background:
            linear-gradient(180deg, rgba(15, 23, 42, 0.05) 0%, rgba(15, 23, 42, 0.70) 100%),
            linear-gradient(135deg, rgba(16, 185, 129, 0.20), rgba(14, 165, 233, 0.15));
        display: flex;
        align-items: flex-end;
        justify-content: flex-start;
        padding: 22px;
        opacity: 1;
        transition: all 0.35s ease;
        pointer-events: none;
    }

    .category-card:hover .category-overlay {
        background:
            linear-gradient(180deg, rgba(15, 23, 42, 0.10) 0%, rgba(15, 23, 42, 0.82) 100%),
            linear-gradient(135deg, rgba(16, 185, 129, 0.32), rgba(14, 165, 233, 0.24));
    }

    .category-content {
        width: 100%;
        text-align: left;
        color: #fff;
        transform: translateY(0);
        transition: transform 0.35s ease;
    }

    .category-card:hover .category-content {
        transform: translateY(-4px);
    }

    .category-content h4 {
        margin: 0 0 8px;
        font-size: 21px;
        line-height: 1.25;
        font-weight: 800;
        color: #fff;
        text-shadow: 0 3px 12px rgba(0,0,0,0.25);
    }

    .category-content h4 a {
        color: #fff;
        text-decoration: none;
    }

    .category-content p {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin: 0;
        padding: 7px 12px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.18);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.24);
        font-size: 13px;
        font-weight: 700;
        color: rgba(255,255,255,0.95);
    }

    .section-title {
        margin-bottom: 45px;
    }

    .section-title h2 {
        font-size: 36px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .section-title p {
        font-size: 16px;
        color: #64748b;
        margin: 0 auto;
        max-width: 650px;
        line-height: 1.7;
    }

    @media (max-width: 991px) {
        .category-card {
            height: 245px;
            border-radius: 20px;
        }

        .section-title h2 {
            font-size: 30px;
        }
    }

    @media (max-width: 575px) {
        .category-card {
            height: 220px;
            border-radius: 18px;
        }

        .category-overlay {
            padding: 18px;
        }

        .category-content h4 {
            font-size: 18px;
        }

        .category-content p {
            font-size: 12px;
            padding: 6px 10px;
        }

        .section-title {
            margin-bottom: 30px;
        }

        .section-title h2 {
            font-size: 26px;
        }
    }
</style>
        @endpush

@endsection