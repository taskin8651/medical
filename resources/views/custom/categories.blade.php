@extends('custom.master')
@section('content')

 <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.html') }})"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Categories</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('home') }}"><i class="far fa-home"></i> Home</a></li>
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
                                        <img src="{{ asset('storage/' . $category->image ?? 'assets/img/category/default.jpg') }}"
                                             alt="{{ $category->name }}"
                                             onerror="this.src='{{ asset('assets/img/category/default.jpg') }}'">
                                    </a>
                                    <div class="category-overlay">
                                        <div class="category-content">
                                            <h4><a href="{{ route('category.show', $category->slug) }}">{{ $category->name }}</a></h4>
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
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 4px 15px rgba(0,0,0,0.1);
                transition: all 0.3s ease;
                height: 250px;
            }

            .category-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            }

            .category-img {
                position: relative;
                height: 100%;
                overflow: hidden;
            }

            .category-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s ease;
            }

            .category-card:hover .category-img img {
                transform: scale(1.1);
            }

            .category-overlay {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: linear-gradient(135deg, rgba(0,123,255,0.8) 0%, rgba(0,123,255,0.6) 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .category-card:hover .category-overlay {
                opacity: 1;
            }

            .category-content {
                text-align: center;
                color: white;
            }

            .category-content h4 {
                margin-bottom: 10px;
                font-size: 20px;
                font-weight: 600;
            }

            .category-content h4 a {
                color: white;
                text-decoration: none;
            }

            .category-content p {
                margin: 0;
                font-size: 14px;
                opacity: 0.9;
            }

            .section-title {
                margin-bottom: 50px;
            }

            .section-title h2 {
                font-size: 32px;
                font-weight: 700;
                color: #333;
                margin-bottom: 10px;
            }

            .section-title p {
                font-size: 16px;
                color: #666;
                margin: 0;
            }
        </style>
        @endpush

@endsection