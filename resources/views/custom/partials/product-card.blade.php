@php
    $productImage = $product->getFirstMediaUrl('images') ?: asset('assets/img/product/01.png');
    $price = $product->sale_price && $product->sale_price < $product->price ? $product->sale_price : $product->price;
    $discount = $product->sale_price && $product->sale_price < $product->price && $product->price > 0
        ? round((($product->price - $product->sale_price) / $product->price) * 100)
        : null;
@endphp

<div class="product-item">
    <div class="product-img">
        @if($product->stock <= 0)
            <span class="type">Out Of Stock</span>
        @elseif($discount)
            <span class="type">{{ $discount }}% Off</span>
        @elseif($product->is_featured)
            <span class="type new">Featured</span>
        @endif

        <a href="{{ route('shop.show', $product->slug) }}">
            <img src="{{ $productImage }}" alt="{{ $product->name }}">
        </a>

        <div class="product-action-wrap">
            <div class="product-action">
                <a href="{{ route('shop.show', $product->slug) }}" title="View Details">
                    <i class="fas fa-eye"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="product-content">
        <h3 class="product-title">
            <a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a>
        </h3>

        @if($product->category || $product->brand)
            <div class="product-rate">
                @if($product->category)
                    <span>{{ $product->category->name }}</span>
                @endif
                @if($product->brand)
                    <span>{{ $product->brand->name }}</span>
                @endif
            </div>
        @endif

        <div class="product-bottom">
            <div class="product-price">
                @if($discount)
                    <span class="old-price">{{ $money($product->price) }}</span>
                    <span class="new-price">{{ $money($price) }}</span>
                @else
                    <span>{{ $money($price) }}</span>
                @endif
            </div>

            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="product-cart-btn" title="Add To Cart">
                        <i class="fas fa-bag-shopping"></i>
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
