@extends('custom.master')

@section('content')
    @php
        $money = fn ($amount) => 'Rs. ' . number_format((float) $amount, 2);
    @endphp

    @if($heroes->isNotEmpty())
        <div class="hero-section hs-1 mt-30">
            <div class="container">
                <div class="hero-slider owl-carousel owl-theme">
                    @foreach($heroes as $hero)
                        <div class="hero-single">
                            <div class="container">
                                <div class="row align-items-center">
                                    <div class="col-lg-6">
                                        <div class="hero-content">
                                            @if($hero->subtitle)
                                                <h6 class="hero-sub-title" data-animation="fadeInUp" data-delay=".25s">{{ $hero->subtitle }}</h6>
                                            @endif

                                            <h1 class="hero-title" data-animation="fadeInRight" data-delay=".50s">{{ $hero->title }}</h1>

                                            @if($hero->description)
                                                <p data-animation="fadeInLeft" data-delay=".75s">{{ $hero->description }}</p>
                                            @endif

                                            <div class="hero-btn" data-animation="fadeInUp" data-delay="1s">
                                                <a href="{{ $hero->button_link ?: route('shop') }}" class="theme-btn">
                                                    {{ $hero->button_text ?: 'Shop Now' }} <i class="fas fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="hero-right" data-animation="fadeInRight" data-delay=".25s">
                                            <div class="hero-img">
                                                <img src="{{ asset('storage/' . $hero->image) }}" alt="{{ $hero->title }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($categories->isNotEmpty())
        <div class="category-area pt-80 pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInDown" data-wow-delay=".25s">
                        <div class="site-heading-inline">
                            <h2 class="site-title">Top Category</h2>
                            <a href="{{ route('categories') }}">View More <i class="fas fa-angle-double-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="category-slider owl-carousel owl-theme wow fadeInUp" data-wow-delay=".25s">
                    @foreach($categories as $category)
                        @php
                            $categoryImage = $category->getFirstMediaUrl('category');
                        @endphp
                        <div class="category-item">
                            <a href="{{ route('category.show', $category->slug) }}">
                                <div class="category-info">
                                    <div class="icon">
                                        @if($categoryImage)
                                            <img src="{{ $categoryImage }}" alt="{{ $category->name }}">
                                        @else
                                            <i class="fas fa-capsules"></i>
                                        @endif
                                    </div>
                                    <div class="content">
                                        <h4>{{ $category->name }}</h4>
                                        <p>{{ $category->products_count }} Items</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($featuredProducts->isNotEmpty())
        <div class="product-area pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInDown" data-wow-delay=".25s">
                        <div class="site-heading-inline">
                            <h2 class="site-title">Featured Items</h2>
                            <a href="{{ route('shop') }}">View More <i class="fas fa-angle-double-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="product-wrap item-2 wow fadeInUp" data-wow-delay=".25s">
                    <div class="product-slider owl-carousel owl-theme">
                        @foreach($featuredProducts as $product)
                            @include('custom.partials.product-card', ['product' => $product, 'money' => $money])
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($latestProducts->isNotEmpty())
        <div class="product-area pb-100">
            <div class="container">
                <div class="row">
                    <div class="col-12 wow fadeInDown" data-wow-delay=".25s">
                        <div class="site-heading-inline">
                            <h2 class="site-title">Latest Items</h2>
                            <a href="{{ route('shop') }}">View More <i class="fas fa-angle-double-right"></i></a>
                        </div>
                    </div>
                </div>

                <div class="row g-4 item-4">
                    @foreach($latestProducts as $product)
                        <div class="col-md-6 col-lg-3">
                            @include('custom.partials.product-card', ['product' => $product, 'money' => $money])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

  @if($brands->isNotEmpty())
<section class="brand-area brand-premium-area pt-80 pb-80">
    <div class="container">

        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="site-heading text-center">
                    <span class="site-title-tagline">Popular Brands</span>
                    <h2 class="site-title">Shop By <span>Brand</span></h2>
                    <p>Explore trusted medicine and healthcare brands.</p>
                </div>
            </div>
        </div>

        <div class="brand-slider owl-carousel owl-theme">
            @foreach($brands as $brand)
                <div class="brand-item">
                    <a href="{{ route('shop', ['brand' => $brand->id]) }}" class="brand-card">

                        <div class="brand-logo-box">
                            @if(!empty($brand->logo))
                                <img src="{{ asset('storage/' . $brand->logo) }}"
                                     alt="{{ $brand->name }}"
                                     onerror="this.style.display='none'; this.closest('.brand-logo-box').classList.add('no-logo');">
                            @else
                                <div class="brand-letter">
                                    {{ strtoupper(substr($brand->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="brand-content">
                            <h5>{{ $brand->name }}</h5>
                            <span>{{ $brand->products_count ?? 0 }} Items</span>
                        </div>

                    </a>
                </div>
            @endforeach
        </div>

    </div>
</section>
@endif

    @if($galleries->isNotEmpty())
        <div class="gallery-area py-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline">Gallery</span>
                            <h2 class="site-title">Our Product <span>Gallery</span></h2>
                        </div>
                    </div>
                </div>

                <div class="row g-4 popup-gallery">
                    @foreach($galleries as $gallery)
                        @foreach($gallery->getMedia('gallery')->take(2) as $media)
                            <div class="col-md-4 col-lg-3">
                                <div class="gallery-item wow fadeInUp" data-wow-delay=".25s">
                                    <div class="gallery-img">
                                        <img src="{{ $media->getUrl() }}" alt="{{ $gallery->title ?: 'Gallery image' }}">
                                        <a class="popup-img gallery-link" href="{{ $media->getUrl() }}">
                                            <i class="fas fa-plus"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($testimonials->isNotEmpty())
        <div class="testimonial-area ts-bg py-80">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline">Testimonials</span>
                            <h2 class="site-title text-white">What Our Client Say's <span>About Us</span></h2>
                        </div>
                    </div>
                </div>

                <div class="testimonial-slider owl-carousel owl-theme wow fadeInUp" data-wow-delay=".25s">
                    @foreach($testimonials as $testimonial)
                        @php
                            $testimonialImage = $testimonial->getFirstMediaUrl('testimonial');
                        @endphp
                        <div class="testimonial-item">
                            <div class="testimonial-author">
                                @if($testimonialImage)
                                    <div class="testimonial-author-img">
                                        <img src="{{ $testimonialImage }}" alt="{{ $testimonial->name }}">
                                    </div>
                                @endif
                                <div class="testimonial-author-info">
                                    <h4>{{ $testimonial->name }}</h4>
                                    @if($testimonial->designation)
                                        <p>{{ $testimonial->designation }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="testimonial-quote">
                                <p>{{ $testimonial->message }}</p>
                            </div>
                            <div class="testimonial-rate">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star"></i>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @if($blogs->isNotEmpty())
        <div class="blog-area py-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline">Our Blog</span>
                            <h2 class="site-title">Our Latest News & <span>Blog</span></h2>
                        </div>
                    </div>
                </div>

                <div class="row g-4">
                    @foreach($blogs as $blog)
                        @php
                            $blogImage = $blog->getFirstMediaUrl('featured') ?: asset('assets/img/blog/01.jpg');
                        @endphp
                        <div class="col-md-6 col-lg-4">
                            <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                                <div class="blog-item-img">
                                    <a href="{{ route('blog.show', $blog->slug) }}">
                                        <img src="{{ $blogImage }}" alt="{{ $blog->title }}">
                                    </a>
                                    <span class="blog-date">
                                        <i class="fas fa-calendar-days"></i> {{ $blog->created_at->format('M d, Y') }}
                                    </span>
                                </div>
                                <div class="blog-item-info">
                                    <div class="blog-item-meta">
                                        <ul>
                                            <li><a href="#"><i class="fas fa-circle-user"></i> By Admin</a></li>
                                        </ul>
                                    </div>
                                    <h4 class="blog-title">
                                        <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                                    </h4>
                                    <p>{{ Str::limit($blog->short_description ?: strip_tags($blog->description), 120) }}</p>
                                    <a class="theme-btn" href="{{ route('blog.show', $blog->slug) }}">
                                        Read More <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
