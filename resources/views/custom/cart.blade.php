@extends('custom.master')

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Shop Cart</h4>
            <ul class="breadcrumb-menu">
                <li>
                    <a href="{{ url('/') }}">
                        <i class="far fa-home"></i> Home
                    </a>
                </li>
                <li class="active">Shop Cart</li>
            </ul>
        </div>
    </div>
</div>
<!-- breadcrumb end -->


<!-- shop cart -->
<div class="shop-cart py-90">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="shop-cart-wrap">
            <div class="row">

                <div class="col-lg-8">
                    <div class="cart-table">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Product Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Sub Total</th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($cart as $productId => $item)
                                        @php
                                            $price = $item['price'] ?? 0;
                                            $quantity = $item['quantity'] ?? 1;
                                            $gstRate = $item['gst_rate'] ?? 0;
                                            $gstAmount = $item['gst_amount'] ?? 0;

                                            $lineSubTotal = $price * $quantity;
                                            $lineGst = $gstAmount * $quantity;
                                            $lineTotal = $lineSubTotal + $lineGst;

                                            $minQty = $item['min_qty'] ?? 1;
                                            $maxQty = $item['max_qty'] ?? $item['stock'] ?? 1;

                                            $packLabel = trim(($item['pack_size'] ?? '') . ' ' . ($item['pack_type'] ?? ''));
                                        @endphp

                                        <tr>
                                            <td>
                                                <div class="shop-cart-img">
                                                    <a href="{{ route('shop.show', $item['slug']) }}">
                                                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                                                    </a>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="shop-cart-content">
                                                    <h5 class="shop-cart-name">
                                                        <a href="{{ route('shop.show', $item['slug']) }}">
                                                            {{ $item['name'] }}
                                                        </a>
                                                    </h5>

                                                    <div class="shop-cart-info">
                                                        @if(!empty($item['sku']))
                                                            <p>
                                                                <span>SKU:</span>{{ $item['sku'] }}
                                                            </p>
                                                        @endif

                                                        @if(!empty($item['generic_name']))
                                                            <p>
                                                                <span>Generic:</span>{{ $item['generic_name'] }}
                                                            </p>
                                                        @endif

                                                        @if(!empty($item['category']))
                                                            <p>
                                                                <span>Category:</span>{{ $item['category'] }}
                                                            </p>
                                                        @endif

                                                        @if(!empty($item['brand']))
                                                            <p>
                                                                <span>Brand:</span>{{ $item['brand'] }}
                                                            </p>
                                                        @endif

                                                        @if(!empty($packLabel))
                                                            <p>
                                                                <span>Pack:</span>{{ $packLabel }}
                                                            </p>
                                                        @endif

                                                        @if(!empty($item['units_per_pack']))
                                                            <p>
                                                                <span>Units:</span>{{ $item['units_per_pack'] }} per pack
                                                            </p>
                                                        @endif

                                                        @if(!empty($item['drug_schedule']))
                                                            <p>
                                                                <span>Schedule:</span>{{ $item['drug_schedule'] }}
                                                            </p>
                                                        @endif

                                                        @if(!empty($item['requires_prescription']))
                                                            <p>
                                                                <span>Prescription:</span>Required
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="shop-cart-price">
                                                    <span>₹{{ number_format($price, 2) }}</span>

                                                    @if($gstRate > 0)
                                                        <small class="d-block">
                                                            + {{ $gstRate }}% GST
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                <div class="shop-cart-qty">

                                                    <form action="{{ route('cart.update', $productId) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="action" value="minus">

                                                        <button type="submit" class="minus-btn">
                                                            <i class="fal fa-minus"></i>
                                                        </button>
                                                    </form>

                                                    <input class="quantity"
                                                           type="text"
                                                           value="{{ $quantity }}"
                                                           disabled>

                                                    <form action="{{ route('cart.update', $productId) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        <input type="hidden" name="action" value="plus">

                                                        <button type="submit" class="plus-btn">
                                                            <i class="fal fa-plus"></i>
                                                        </button>
                                                    </form>

                                                </div>

                                                <small class="d-block mt-2">
                                                    Min: {{ $minQty }} | Max: {{ $maxQty }}
                                                </small>
                                            </td>

                                            <td>
                                                <div class="shop-cart-subtotal">
                                                    <span>₹{{ number_format($lineTotal, 2) }}</span>

                                                    @if($lineGst > 0)
                                                        <small class="d-block">
                                                            GST: ₹{{ number_format($lineGst, 2) }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </td>

                                            <td>
                                                <a href="{{ route('cart.remove', $productId) }}"
                                                   class="shop-cart-remove"
                                                   onclick="return confirm('Remove this product from cart?')">
                                                    <i class="fa fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <h5>Your cart is empty.</h5>

                                                <a href="{{ url('/shop') }}" class="theme-btn mt-3">
                                                    Continue Shopping
                                                </a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if(count($cart) > 0)
                        <div class="shop-cart-footer">
                            <div class="row">
                                <div class="col-md-7 col-lg-6">
                                    
                                </div>
                                
                                <div class="col-md-5 col-lg-6">
                                    <div class="shop-cart-btn text-md-end">
                                        <a href="{{ url('/shop') }}" class="theme-btn">
                                            <span class="fas fa-arrow-left"></span>
                                            Continue Shopping
                                        </a>

                                        <a href="{{ route('cart.clear') }}"
                                           class="theme-btn"
                                           onclick="return confirm('Clear complete cart?')">
                                            Clear Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="shop-cart-summary">
                        <h5>Cart Summary</h5>

                        <ul>
                            <li>
                                <strong>Sub Total:</strong>
                                <span>₹{{ number_format($subTotal, 2) }}</span>
                            </li>

                            <li>
                                <strong>Discount:</strong>
                                <span>₹{{ number_format($discount, 2) }}</span>
                            </li>

                            <li>
                                <strong>Shipping:</strong>
                                <span>
                                    {{ $shipping > 0 ? '₹'.number_format($shipping, 2) : 'Free' }}
                                </span>
                            </li>

                            <li>
                                <strong>GST:</strong>
                                <span>₹{{ number_format($tax, 2) }}</span>
                            </li>

                            <li class="shop-cart-total">
                                <strong>Total:</strong>
                                <span>₹{{ number_format($total, 2) }}</span>
                            </li>
                        </ul>

                        @if(count($cart) > 0)
                            <div class="text-end mt-40">
                               <a href="{{ route('checkout.index') }}" class="theme-btn">
    Checkout Now <i class="fas fa-arrow-right"></i>
</a>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- shop cart end -->

@endsection