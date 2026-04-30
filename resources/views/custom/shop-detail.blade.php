@extends('custom.master')

@section('content')

    <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url(assets/img/breadcrumb/01.html)"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Shop Single</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="index.html"><i class="far fa-home"></i> Home</a></li>
                        <li class="active">Shop Single</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- shop single -->
        <div class="shop-single py-90">
            <div class="container">
                @php
            $galleryImages = $product->images;
        @endphp
        <div class="row">
                    <div class="col-md-9 col-lg-6 col-xxl-5">
                        <div class="shop-single-gallery">
                            <a class="shop-single-video popup-youtube" href="{{ $product->video_url ?? 'https://www.youtube.com/watch?v=ckHzmP1evNU' }}" data-tooltip="tooltip" title="Watch Video">
                                <i class="far fa-play"></i>
                            </a>
                            <div class="flexslider-thumbnails">
                                <ul class="slides">
                                    @if($galleryImages->count())
                                        @foreach($galleryImages as $image)
                                            <li data-thumb="{{ $image->getUrl() }}" rel="adjustX:10, adjustY:">
                                                <img src="{{ $image->getUrl() }}" alt="{{ $product->name }}">
                                            </li>
                                        @endforeach
                                    @else
                                        <li data-thumb="{{ asset('assets/img/product/01.png') }}" rel="adjustX:10, adjustY:">
                                            <img src="{{ asset('assets/img/product/01.png') }}" alt="{{ $product->name }}">
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-6 col-xxl-6">
                        <div class="shop-single-info">
                            <h4 class="shop-single-title">{{ $product->name }}</h4>
                            <div class="shop-single-rating">
                                @php $rating = $product->rating ?? 4; @endphp
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $rating)
                                        <i class="fas fa-star"></i>
                                    @else
                                        <i class="far fa-star"></i>
                                    @endif
                                @endfor
                                <span class="rating-count"> ({{ $product->reviews_count ?? 4 }} Customer Reviews)</span>
                            </div>
                            <div class="shop-single-price">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <del>${{ number_format($product->price, 2) }}</del>
                                    <span class="amount">${{ number_format($product->sale_price, 2) }}</span>
                                    <span class="discount-percentage">{{ round((1 - $product->sale_price / max($product->price, 1)) * 100) }}% Off</span>
                                @else
                                    <span class="amount">${{ number_format($product->price, 2) }}</span>
                                @endif
                            </div>
                            <p class="mb-3">
                                {!! nl2br(e($product->short_description ?: $product->description ?: 'No description available.')) !!}
                            </p>
                            <div class="shop-single-cs">
                                <div class="row">
                                    <div class="col-md-3 col-lg-4 col-xl-3">
                                        <div class="shop-single-size">
                                            <h6>Quantity</h6>
                                            <div class="shop-cart-qty">
                                                <button class="minus-btn"><i class="fal fa-minus"></i></button>
                                                <input class="quantity" type="text" value="1" disabled="">
                                                <button class="plus-btn"><i class="fal fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                   
                                </div>
                            </div>
                            <div class="shop-single-sortinfo">
                                <ul>
                                    <li>Stock: <span>{{ $product->stock > 0 ? 'Available' : 'Out of Stock' }}</span></li>
                                    <li>SKU: <span>{{ $product->sku ?? '-' }}</span></li>
                                    <li>Category: <span>{{ $product->category->name ?? 'N/A' }}</span></li>
                                    <li>Brand: <span>{{ $product->brand->name ?? 'N/A' }}</span></li>
                                    <li>Pack: <span>{{ trim(($product->pack_size ?? '') . ' ' . ($product->pack_type ?? '')) ?: '-' }}</span></li>
                                </ul>
                            </div>
                            <div class="shop-single-action">
                                <div class="row align-items-center">
                                    <div class="col-md-6 col-lg-12 col-xl-6">
                                        <div class="shop-single-btn">
<form action="{{ route('cart.add', $product->id) }}" method="POST">
    @csrf

    @php
        $minQty = $product->min_qty ?? 1;
        $maxQty = $product->max_qty ?? $product->stock;
        $packLabel = trim(($product->pack_size ?? '') . ' ' . ($product->pack_type ?? ''));
    @endphp

    <div class="mb-3">
        @if($packLabel)
            <small class="d-block mb-1">
                Pack: {{ $packLabel }}
            </small>
        @endif

        @if($product->units_per_pack)
            <small class="d-block mb-1">
                Units Per Pack: {{ $product->units_per_pack }}
            </small>
        @endif

        <input type="number"
               name="quantity"
               value="{{ $minQty }}"
               min="{{ $minQty }}"
               max="{{ $maxQty }}"
               class="form-control"
               style="max-width: 120px;">
    </div>

    <button type="submit" class="theme-btn">
        <span class="fa fa-shopping-bag"></span> Add To Cart
    </button>
</form>
                                         <a href="#" class="theme-btn "><span class="fa fa-download"></span>Download</a>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-12 col-xl-6">
                                        <div class="shop-single-share">
                                            @php
    $shareUrl = urlencode(url()->current());
    $shareTitle = urlencode($product->name ?? 'Product');
@endphp

<span>Share:</span>

<a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
   target="_blank"
   rel="noopener">
    <i class="fab fa-facebook-f"></i>
</a>

<a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
   target="_blank"
   rel="noopener">
    <i class="fab fa-x-twitter"></i>
</a>

<a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}"
   target="_blank"
   rel="noopener">
    <i class="fab fa-linkedin-in"></i>
</a>

<a href="https://pinterest.com/pin/create/button/?url={{ $shareUrl }}&description={{ $shareTitle }}"
   target="_blank"
   rel="noopener">
    <i class="fab fa-pinterest-p"></i>
</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- shop single details -->
                <div class="shop-single-details">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-tab1" data-bs-toggle="tab" data-bs-target="#tab1"
                                type="button" role="tab" aria-controls="tab1" aria-selected="true">Description</button>
                            <button class="nav-link" id="nav-tab2" data-bs-toggle="tab" data-bs-target="#tab2"
                                type="button" role="tab" aria-controls="tab2" aria-selected="false">Additional
                                Info</button>
                           
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="nav-tab1">
                            <div class="shop-single-desc">
                                <p>{!! nl2br(e($product->description ?: $product->short_description ?: 'Description not available.')) !!}</p>
                                @if($product->generic_name || $product->composition || $product->strength)
                                    <div class="row">
                                        <div class="col-lg-5 col-xl-4">
                                            <div class="shop-single-list">
                                                <h5 class="title">Key Medical Details</h5>
                                                <ul>
                                                    @if($product->generic_name)
                                                        <li><span>Generic Name:</span> {{ $product->generic_name }}</li>
                                                    @endif
                                                    @if($product->composition)
                                                        <li><span>Composition:</span> {{ $product->composition }}</li>
                                                    @endif
                                                    @if($product->form)
                                                        <li><span>Form:</span> {{ $product->form }}</li>
                                                    @endif
                                                    @if($product->strength)
                                                        <li><span>Strength:</span> {{ $product->strength }}</li>
                                                    @endif
                                                    @if($product->drug_schedule)
                                                        <li><span>Schedule:</span> {{ $product->drug_schedule }}</li>
                                                    @endif
                                                    @if($product->requires_prescription)
                                                        <li><span>Prescription Required:</span> Yes</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-xl-5">
                                            <div class="shop-single-list">
                                                <h5 class="title">Product Information</h5>
                                                <ul>
                                                    @if($product->pack_size)
                                                        <li><span>Pack Size:</span> {{ $product->pack_size }}</li>
                                                    @endif
                                                    @if($product->pack_type)
                                                        <li><span>Pack Type:</span> {{ $product->pack_type }}</li>
                                                    @endif
                                                    @if($product->units_per_pack)
                                                        <li><span>Units Per Pack:</span> {{ $product->units_per_pack }}</li>
                                                    @endif
                                                    @if($product->storage_conditions)
                                                        <li><span>Storage:</span> {{ $product->storage_conditions }}</li>
                                                    @endif
                                                    @if($product->shelf_life)
                                                        <li><span>Shelf Life:</span> {{ $product->shelf_life }}</li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="nav-tab2">
                            <div class="shop-single-additional">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="shop-single-list">
                                            <h5 class="title">Detailed Product Specs</h5>
                                            <table class="table table-bordered">
                                                <tbody>
                                                    <tr><th>MRP</th><td>${{ number_format($product->mrp ?? 0, 2) }}</td></tr>
                                                    <tr><th>PTR</th><td>${{ number_format($product->ptr ?? 0, 2) }}</td></tr>
                                                    <tr><th>PTS</th><td>${{ number_format($product->pts ?? 0, 2) }}</td></tr>
                                                    <tr><th>GST Rate</th><td>{{ $product->gst_rate ? $product->gst_rate . '%' : '-' }}</td></tr>
                                                    <tr><th>Price + GST</th><td>${{ number_format($product->price_with_gst ?? $product->price, 2) }}</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="shop-single-list">
                                            <h5 class="title">Safety & Usage</h5>
                                            <ul>
                                                @if($product->side_effects)
                                                    <li><span>Side Effects:</span> {{ $product->side_effects }}</li>
                                                @endif
                                                @if($product->contraindications)
                                                    <li><span>Contraindications:</span> {{ $product->contraindications }}</li>
                                                @endif
                                                <li><span>Minimum Order Qty:</span> {{ $product->min_qty ?? 'N/A' }}</li>
                                                <li><span>Maximum Order Qty:</span> {{ $product->max_qty ?? 'N/A' }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
                <!-- shop single details end -->

            
                <!-- related item -->
              <div class="product-area related-item pt-40">
    <div class="container px-0">

        <div class="row">
            <div class="col-12">
                <div class="site-heading-inline">
                    <h2 class="site-title">Related Items</h2>
                    <a href="{{ route('shop') }}">
                        View More <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 item-2">

            @forelse($relatedProducts as $related)
                @php
                    $primaryImage = $related->primaryImage();
                    $imageUrl = $primaryImage
                        ? $primaryImage->getUrl()
                        : asset('assets/img/product/no-image.png');
                @endphp

                <div class="col-md-6 col-lg-3">
                    <div class="product-item">

                        <div class="product-img">
                            @if($related->created_at && $related->created_at->gt(now()->subDays(15)))
                                <span class="type new">New</span>
                            @endif

                            <a href="{{ route('shop.show', $related->slug) }}">
                                <img src="{{ $imageUrl }}" alt="{{ $related->name }}">
                            </a>

                            <div class="product-action-wrap">
                                <div class="product-action">
                                    <a href="{{ route('shop.show', $related->slug) }}"
                                       data-bs-placement="right"
                                       data-tooltip="tooltip"
                                       title="Quick View">
                                        <i class="far fa-eye"></i>
                                    </a>

                                    

                                  
                                </div>
                            </div>
                        </div>

                        <div class="product-content">
                            <h3 class="product-title">
                                <a href="{{ route('shop.show', $related->slug) }}">
                                    {{ $related->name }}
                                </a>
                            </h3>

                           

                            <div class="product-bottom">
                                <div class="product-price">
                                    @if($related->sale_price)
                                        <span>₹{{ number_format($related->sale_price, 2) }}</span>
                                        <del>₹{{ number_format($related->price, 2) }}</del>
                                    @else
                                        <span>₹{{ number_format($related->price, 2) }}</span>
                                    @endif
                                </div>

                                <button type="button"
                                        class="product-cart-btn"
                                        data-bs-placement="left"
                                        data-tooltip="tooltip"
                                        title="Add To Cart">
                                    <i class="fas fa-shopping-bag"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p>No related products found.</p>
                </div>
            @endforelse

        </div>
    </div>
</div>
                <!-- related item end -->
            </div>
        </div>
        <!-- shop single end -->

        @endsection