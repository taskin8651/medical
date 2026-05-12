@extends('custom.master')

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Our Blog</h4>
            <ul class="breadcrumb-menu">
                <li>
                    <a href="{{ url('/') }}">
                        <i class="fas fa-house"></i> Home
                    </a>
                </li>
                <li class="active">Our Blog</li>
            </ul>
        </div>
    </div>
</div>
<!-- breadcrumb end -->


<!-- blog area -->
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
            @forelse($blogs as $blog)
                @php
                    $image = $blog->getFirstMediaUrl('featured') ?: asset('assets/img/blog/01.jpg');
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                        <div class="blog-item-img">
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                <img src="{{ $image }}" alt="{{ $blog->title }}">
                            </a>

                            <span class="blog-date">
                                <i class="fas fa-calendar-days"></i>
                                {{ $blog->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <div class="blog-item-info">
                            <div class="blog-item-meta">
                                <ul>
                                    <li>
                                        <a href="#">
                                            <i class="fas fa-circle-user"></i> By Admin
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <h4 class="blog-title">
                                <a href="{{ route('blog.show', $blog->slug) }}">
                                    {{ $blog->title }}
                                </a>
                            </h4>

                            <p>
                                {{ Str::limit($blog->short_description ?? strip_tags($blog->description), 120) }}
                            </p>

                            <a class="theme-btn" href="{{ route('blog.show', $blog->slug) }}">
                                Read More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <h5>No blogs found.</h5>
                    </div>
                </div>
            @endforelse
        </div>

        @if($blogs->hasPages())
            <div class="pagination-area mt-60">
                {{ $blogs->links() }}
            </div>
        @endif
    </div>
</div>
<!-- blog area end -->

@endsection