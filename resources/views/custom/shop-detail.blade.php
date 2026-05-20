@extends('custom.master')

@section('content')
@php
    $images = $product->getMedia('images');
    $documents = $product->getMedia('documents');
    $fallbackImage = asset('assets/img/product/01.png');
    $imageItems = $images->count()
        ? $images->map(fn ($media) => ['url' => $media->getUrl(), 'alt' => $media->name ?: $product->name])->values()
        : collect([['url' => $fallbackImage, 'alt' => $product->name]]);
    $price = $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price;
    $discount = $product->sale_price && $product->sale_price < $product->price && $product->price > 0
        ? round((($product->price - $product->sale_price) / $product->price) * 100)
        : null;
    $packLabel = trim(($product->pack_size ?? '') . ' ' . ($product->pack_type ?? ''));
    $stockQty = max(0, (int) $product->stock);
    $minQty = max(1, (int) ($product->min_qty ?? 1));
    $maxQty = (int) ($product->max_qty ?: $stockQty);
    $maxQty = $maxQty > 0 ? min($maxQty, $stockQty) : $stockQty;
    $minQty = $stockQty > 0 ? min($minQty, $maxQty) : $minQty;
    $money = fn ($amount) => '&#8377;' . number_format((float) $amount, 2);
    $shareUrl = urlencode(url()->current());
    $shareTitle = urlencode($product->name ?? 'Product');
@endphp

<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url('{{ asset('assets/img/breadcrumb/01.jpg') }}')"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">{{ $product->name }}</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home') }}"><i class="fas fa-house"></i> Home</a></li>
                <li><a href="{{ route('shop') }}">Shop</a></li>
                <li class="active">{{ Str::limit($product->name, 32) }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="shop-single py-90">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-lg-6">
                <div class="product-detail-gallery" data-product-gallery>
                    @if($discount)
                        <span class="gallery-badge">{{ $discount }}% Off</span>
                    @elseif($product->stock <= 0)
                        <span class="gallery-badge danger">Out Of Stock</span>
                    @endif

                    <div class="gallery-main">
                        <button type="button" class="gallery-arrow prev" data-gallery-prev aria-label="Previous image">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <img src="{{ $imageItems->first()['url'] }}" alt="{{ $imageItems->first()['alt'] }}" data-gallery-main>
                        <button type="button" class="gallery-arrow next" data-gallery-next aria-label="Next image">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>

                    <div class="gallery-thumbs" data-gallery-thumbs>
                        @foreach($imageItems as $index => $image)
                            <button type="button" class="gallery-thumb {{ $index === 0 ? 'active' : '' }}" data-gallery-thumb="{{ $index }}">
                                <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="shop-single-info detail-info">
                    <div class="detail-kicker">
                        <span>{{ $product->category->name ?? 'Medical Product' }}</span>
                        @if($product->requires_prescription)
                            <span>Prescription Required</span>
                        @endif
                    </div>

                    <h1 class="shop-single-title">{{ $product->name }}</h1>

                    <div class="detail-meta">
                        <span><i class="fas fa-box"></i> {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}</span>
                        <span><i class="fas fa-barcode"></i> {{ $product->sku ?: 'No SKU' }}</span>
                    </div>

                    <div class="shop-single-price">
                        @if($discount)
                            <del>{!! $money($product->price) !!}</del>
                            <span class="amount">{!! $money($price) !!}</span>
                            <span class="discount-percentage">{{ $discount }}% Off</span>
                        @else
                            <span class="amount">{!! $money($price) !!}</span>
                        @endif
                    </div>

                    <p class="detail-summary">
                        {!! nl2br(e($product->short_description ?: $product->description ?: 'Product description is not available yet.')) !!}
                    </p>

                    <div class="detail-facts">
                        <div><span>Brand</span><strong>{{ $product->brand->name ?? 'N/A' }}</strong></div>
                        <div><span>Pack</span><strong>{{ $packLabel ?: '-' }}</strong></div>
                        <div><span>GST</span><strong>{{ $product->gst_rate ? $product->gst_rate . '%' : '-' }}</strong></div>
                        <div><span>HSN</span><strong>{{ $product->hsn_code ?: '-' }}</strong></div>
                    </div>

                    <div class="detail-actions">
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="detail-cart-form">
                                @csrf
                                <div class="qty-control">
                                    <button type="button" data-qty-minus aria-label="Decrease quantity"><i class="fas fa-minus"></i></button>
                                    <input type="number" name="quantity" value="{{ $minQty }}" min="{{ $minQty }}" max="{{ $maxQty }}" data-qty-input>
                                    <button type="button" data-qty-plus aria-label="Increase quantity"><i class="fas fa-plus"></i></button>
                                </div>
                                <button type="submit" class="theme-btn">
                                    <span class="fas fa-bag-shopping"></span> Add To Cart
                                </button>
                            </form>
                        @else
                            <button type="button" class="theme-btn disabled" disabled>Out Of Stock</button>
                        @endif

                        @if($documents->count())
                            <a href="{{ $documents->first()->getUrl() }}" target="_blank" rel="noopener" class="theme-btn theme-btn2">
                                <span class="fas fa-file-pdf"></span> Download PDF
                            </a>
                        @endif
                    </div>

                    <div class="detail-share">
                        <span>Share</span>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}" target="_blank" rel="noopener"><i class="fab fa-x-twitter"></i></a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://api.whatsapp.com/send?text={{ $shareTitle }}%20{{ $shareUrl }}" target="_blank" rel="noopener"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="shop-single-details">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-tab1" data-bs-toggle="tab" data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1" aria-selected="true">Description</button>
                    <button class="nav-link" id="nav-tab2" data-bs-toggle="tab" data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2" aria-selected="false">Product Info</button>
                    @if($documents->count())
                        <button class="nav-link" id="nav-tab3" data-bs-toggle="tab" data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3" aria-selected="false">Documents</button>
                    @endif
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="tab1" role="tabpanel" aria-labelledby="nav-tab1">
                    <div class="shop-single-desc">
                        <p>{!! nl2br(e($product->description ?: $product->short_description ?: 'Description not available.')) !!}</p>
                    </div>
                </div>

                <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="nav-tab2">
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="shop-single-list detail-list">
                                <h5 class="title">Medical Details</h5>
                                <ul>
                                    <li><span>Generic Name:</span> {{ $product->generic_name ?: '-' }}</li>
                                    <li><span>Composition:</span> {{ $product->composition ?: '-' }}</li>
                                    <li><span>Form:</span> {{ $product->form ?: '-' }}</li>
                                    <li><span>Strength:</span> {{ $product->strength ?: '-' }}</li>
                                    <li><span>Schedule:</span> {{ $product->drug_schedule ?: '-' }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="shop-single-list detail-list">
                                <h5 class="title">Pricing & Pack</h5>
                                <ul>
                                    <li><span>MRP:</span> {!! $money($product->mrp ?? 0) !!}</li>
                                    <li><span>PTR:</span> {!! $money($product->ptr ?? 0) !!}</li>
                                    <li><span>PTS:</span> {!! $money($product->pts ?? 0) !!}</li>
                                    <li><span>Units Per Pack:</span> {{ $product->units_per_pack ?: '-' }}</li>
                                    <li><span>Storage:</span> {{ $product->storage_conditions ?: '-' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                @if($documents->count())
                    <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="nav-tab3">
                        <div class="document-grid">
                            @foreach($documents as $document)
                                <a href="{{ $document->getUrl() }}" target="_blank" rel="noopener" class="document-card">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>{{ $document->file_name }}</span>
                                    <small>Open PDF</small>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="product-area related-item pt-40">
            <div class="site-heading-inline">
                <h2 class="site-title">Related Items</h2>
                <a href="{{ route('shop') }}">View More <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="row g-4 item-2">
                @forelse($relatedProducts as $related)
                    @php
                        $relatedImage = $related->getFirstMediaUrl('images') ?: asset('assets/img/product/01.png');
                        $relatedPrice = $related->sale_price && $related->sale_price < $related->price ? $related->sale_price : $related->price;
                    @endphp
                    <div class="col-md-6 col-lg-3">
                        <div class="product-item">
                            <div class="product-img">
                                <a href="{{ route('shop.show', $related->slug) }}">
                                    <img src="{{ $relatedImage }}" alt="{{ $related->name }}">
                                </a>
                            </div>
                            <div class="product-content">
                                <h3 class="product-title"><a href="{{ route('shop.show', $related->slug) }}">{{ $related->name }}</a></h3>
                                <div class="product-bottom">
                                    <div class="product-price">
                                        @if($related->sale_price && $related->sale_price < $related->price)
                                            <span class="old-price">{!! $money($related->price) !!}</span>
                                            <span class="new-price">{!! $money($relatedPrice) !!}</span>
                                        @else
                                            <span>{!! $money($relatedPrice) !!}</span>
                                        @endif
                                    </div>
                                    @if($related->stock > 0)
                                        <form action="{{ route('cart.add', $related->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="product-cart-btn" title="Add To Cart">
                                                <i class="fas fa-bag-shopping"></i>
                                            </button>
                                        </form>
                                    @endif
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
</div>
@endsection

@push('styles')
<style>
    .product-detail-gallery {
        position: sticky;
        top: 20px;
    }

    .gallery-main {
        position: relative;
        width: 100%;
        aspect-ratio: 1 / 1;
        border: 1px solid #e7ecf3;
        border-radius: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .gallery-main img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 24px;
    }

    .gallery-badge {
        position: absolute;
        z-index: 3;
        top: 14px;
        left: 14px;
        background: var(--theme-color);
        color: #fff;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 700;
    }

    .gallery-badge.danger {
        background: #dc2626;
    }

    .gallery-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 2;
        width: 38px;
        height: 38px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, .92);
        color: #1f2937;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .12);
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .gallery-arrow.prev {
        left: 14px;
    }

    .gallery-arrow.next {
        right: 14px;
    }

    .gallery-thumbs {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
        margin-top: 12px;
    }

    .gallery-thumb {
        aspect-ratio: 1 / 1;
        border: 1px solid #e7ecf3;
        border-radius: 7px;
        background: #fff;
        overflow: hidden;
        padding: 6px;
    }

    .gallery-thumb.active {
        border-color: var(--theme-color);
        box-shadow: 0 0 0 2px rgba(18, 181, 102, .12);
    }

    .gallery-thumb img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .detail-kicker,
    .detail-meta,
    .detail-share {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .detail-kicker span {
        background: #eef8f2;
        color: var(--theme-color);
        border-radius: 50px;
        padding: 5px 12px;
        font-size: 13px;
        font-weight: 700;
    }

    .detail-info .shop-single-title {
        margin-top: 14px;
        line-height: 1.22;
    }

    .detail-meta {
        margin: 12px 0 14px;
        color: #64748b;
    }

    .detail-summary {
        margin: 18px 0;
    }

    .detail-facts {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin: 22px 0;
    }

    .detail-facts div {
        border: 1px solid #e7ecf3;
        border-radius: 8px;
        padding: 12px;
        background: #fff;
    }

    .detail-facts span {
        display: block;
        color: #64748b;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .detail-facts strong {
        color: #111827;
        font-weight: 700;
    }

    .detail-actions,
    .detail-cart-form {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
    }

    .qty-control {
        display: inline-grid;
        grid-template-columns: 40px 72px 40px;
        height: 48px;
        border: 1px solid #e7ecf3;
        border-radius: 7px;
        overflow: hidden;
        background: #fff;
    }

    .qty-control button,
    .qty-control input {
        border: 0;
        background: transparent;
        text-align: center;
        font-weight: 700;
        min-width: 0;
    }

    .qty-control button {
        color: #334155;
    }

    .detail-share {
        margin-top: 24px;
    }

    .detail-share span {
        font-weight: 700;
        color: #111827;
    }

    .detail-share a {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: #334155;
    }

    .detail-list li {
        display: flex;
        gap: 10px;
        justify-content: space-between;
    }

    .document-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px;
    }

    .document-card {
        border: 1px solid #e7ecf3;
        border-radius: 8px;
        padding: 16px;
        display: grid;
        gap: 6px;
        color: #111827;
        background: #fff;
    }

    .document-card i {
        color: #dc2626;
        font-size: 28px;
    }

    .document-card small {
        color: var(--theme-color);
        font-weight: 700;
    }

    @media (max-width: 991px) {
        .product-detail-gallery {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .gallery-thumbs {
            grid-template-columns: repeat(4, 1fr);
        }

        .detail-facts {
            grid-template-columns: 1fr;
        }

        .detail-actions,
        .detail-cart-form {
            align-items: stretch;
            flex-direction: column;
        }

        .qty-control,
        .detail-actions .theme-btn {
            width: 100%;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gallery = document.querySelector('[data-product-gallery]');
    if (!gallery) {
        return;
    }

    const images = @json($imageItems);
    const main = gallery.querySelector('[data-gallery-main]');
    const thumbs = gallery.querySelectorAll('[data-gallery-thumb]');
    let current = 0;

    function showImage(index) {
        current = (index + images.length) % images.length;
        main.src = images[current].url;
        main.alt = images[current].alt;
        thumbs.forEach(function (thumb, thumbIndex) {
            thumb.classList.toggle('active', thumbIndex === current);
        });
    }

    gallery.querySelector('[data-gallery-prev]').addEventListener('click', function () {
        showImage(current - 1);
    });

    gallery.querySelector('[data-gallery-next]').addEventListener('click', function () {
        showImage(current + 1);
    });

    thumbs.forEach(function (thumb) {
        thumb.addEventListener('click', function () {
            showImage(Number(thumb.dataset.galleryThumb));
        });
    });

    document.querySelectorAll('[data-qty-input]').forEach(function (input) {
        const form = input.closest('form');
        const minus = form.querySelector('[data-qty-minus]');
        const plus = form.querySelector('[data-qty-plus]');

        function step(delta) {
            const min = Number(input.min || 1);
            const max = Number(input.max || min);
            const value = Number(input.value || min);
            input.value = Math.min(max, Math.max(min, value + delta));
        }

        minus.addEventListener('click', function () { step(-1); });
        plus.addEventListener('click', function () { step(1); });
    });
});
</script>
@endpush
