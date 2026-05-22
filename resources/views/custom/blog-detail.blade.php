@extends('custom.master')

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg"><i class="fas fa-briefcase-medical"></i></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Blog Details</h4>
            <ul class="breadcrumb-menu">
                <li>
                    <a href="{{ url('/') }}">
                        <i class="fas fa-house"></i> Home
                    </a>
                </li>
                <li>
                    <a href="{{ route('blog.index') }}">Blog</a>
                </li>
                <li class="active">{{ $blog->title }}</li>
            </ul>
        </div>
    </div>
</div>
<!-- breadcrumb end -->


<!-- blog single area -->
<div class="blog-single-area py-100">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-8">
                <div class="blog-single-wrapper">
                    @php
                        $featuredImage = $blog->getFirstMediaUrl('featured') ?: asset('assets/img/blog/01.jpg');
                    @endphp

                    <div class="blog-single-img mb-4">
                        <img src="{{ $featuredImage }}" alt="{{ $blog->title }}" class="img-fluid w-100">
                    </div>

                    <div class="blog-single-content">
                        <div class="blog-item-meta mb-3">
                            <ul>
                                <li>
                                    <a href="#">
                                        <i class="fas fa-circle-user"></i> By Admin
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        <i class="fas fa-calendar-days"></i>
                                        {{ $blog->created_at->format('M d, Y') }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <h2 class="blog-single-title">
                            {{ $blog->title }}
                        </h2>

                        @if(!empty($blog->short_description))
                            <p class="lead">
                                {{ $blog->short_description }}
                            </p>
                        @endif

                        <div class="blog-description">
                            {!! $blog->description !!}
                        </div>
                    </div>

                    @if($blog->getMedia('gallery')->count() > 0)
                        <div class="blog-gallery mt-5">
                            <h4 class="mb-3">Gallery</h4>

                            <div class="row g-3">
                                @foreach($blog->getMedia('gallery') as $media)
                                    <div class="col-md-4">
                                        <img src="{{ $media->getUrl() }}"
                                             alt="{{ $blog->title }}"
                                             class="img-fluid rounded">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <div class="widget">
                        <h4 class="widget-title">Recent Blogs</h4>

                        @forelse($recentBlogs as $recent)
                            @php
                                $recentImage = $recent->getFirstMediaUrl('featured') ?: asset('assets/img/blog/01.jpg');
                            @endphp

                            <div class="recent-post-item d-flex gap-3 mb-3">
                                <div class="recent-post-img" style="width:85px; flex:0 0 85px;">
                                    <a href="{{ route('blog.show', $recent->slug) }}">
                                        <img src="{{ $recentImage }}"
                                             alt="{{ $recent->title }}"
                                             style="width:85px;height:70px;object-fit:cover;border-radius:8px;">
                                    </a>
                                </div>

                                <div class="recent-post-info">
                                    <h6 style="margin:0 0 5px;">
                                        <a href="{{ route('blog.show', $recent->slug) }}">
                                            {{ Str::limit($recent->title, 45) }}
                                        </a>
                                    </h6>
                                    <small>
                                        <i class="fas fa-calendar-days"></i>
                                        {{ $recent->created_at->format('M d, Y') }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <p>No recent blogs found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- blog single area end -->

@endsection