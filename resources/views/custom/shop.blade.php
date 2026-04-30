@extends('custom.master')
@section('content')

 <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.html') }})"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">Shop</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                        <li class="active">Shop</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->


        <!-- shop-area -->
        <div class="shop-area bg py-90">
            <div class="container">
                <div class="row">
                    <!-- Sidebar Filters -->
                    <div class="col-lg-3">
                        <div class="shop-sidebar">
                            <!-- Search Widget -->
                            <div class="shop-widget">
                                <div class="shop-search-form">
                                    <h4 class="shop-widget-title">Search</h4>
                                    <form action="{{ route('shop') }}" method="GET" id="filter-form">
                                        <div class="form-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ $filters['search'] ?? '' }}">
                                            <button type="submit"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- Category Filter -->
                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Category</h4>
                                <ul class="shop-category-list">
                                    @forelse($categories as $category)
                                        <li>
                                            <a href="{{ route('shop', ['category' => $category->id]) }}" 
                                               @class(['active' => request('category') == $category->id])>
                                                {{ $category->name }}
                                                <span>({{ $category->products_count }})</span>
                                            </a>
                                        </li>
                                    @empty
                                        <li>No categories available</li>
                                    @endforelse
                                </ul>
                            </div>
                            <!-- Brand Filter -->
                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Brands</h4>
                                <ul class="shop-checkbox-list">
                                    @forelse($brands as $brand)
                                        <li>
                                            <div class="form-check">
                                                <input class="form-check-input brand-filter" type="checkbox" 
                                                       name="brand" value="{{ $brand->id }}" 
                                                       id="brand{{ $brand->id }}"
                                                       @if(is_array($filters['brand'] ?? null) && in_array($brand->id, $filters['brand'])) checked @endif
                                                       onchange="applyFilters()">
                                                <label class="form-check-label" for="brand{{ $brand->id }}">
                                                    {{ $brand->name }}
                                                    <span>({{ $brand->products_count }})</span>
                                                </label>
                                            </div>
                                        </li>
                                    @empty
                                        <li>No brands available</li>
                                    @endforelse
                                </ul>
                            </div>
                            <!-- Price Range Filter -->
                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Price Range</h4>
                                <div class="price-range-box">
                                    <div class="price-range-input">
                                        <input type="text" id="price-amount" readonly 
                                               value="Rs. {{ number_format($filters['min_price'] ?? $minPrice) }} - Rs. {{ number_format($filters['max_price'] ?? $maxPrice) }}">
                                    </div>
                                    <form method="GET" action="{{ route('shop') }}" class="mt-3">
                                        @foreach(request()->query() as $key => $value)
                                            @if($key != 'min_price' && $key != 'max_price')
                                                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                            @endif
                                        @endforeach
                                        <input type="hidden" name="min_price" id="filter-min-price" value="{{ $filters['min_price'] ?? $minPrice }}">
                                        <input type="hidden" name="max_price" id="filter-max-price" value="{{ $filters['max_price'] ?? $maxPrice }}">
                                        <button type="submit" class="theme-btn w-100">Apply Price</button>
                                    </form>
                                </div>
                            </div>
                            <!-- Stock Status Filter -->
                            <div class="shop-widget">
                                <h4 class="shop-widget-title">Stock Status</h4>
                                <ul class="shop-checkbox-list">
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stock_status" 
                                                   value="" id="stock_all"
                                                   @if(!$filters['stock_status']) checked @endif
                                                   onchange="applyFilters()">
                                            <label class="form-check-label" for="stock_all">All Products</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stock_status" 
                                                   value="in_stock" id="stock_in"
                                                   @if($filters['stock_status'] == 'in_stock') checked @endif
                                                   onchange="applyFilters()">
                                            <label class="form-check-label" for="stock_in">In Stock</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="stock_status" 
                                                   value="out_of_stock" id="stock_out"
                                                   @if($filters['stock_status'] == 'out_of_stock') checked @endif
                                                   onchange="applyFilters()">
                                            <label class="form-check-label" for="stock_out">Out Of Stock</label>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="on_sale" 
                                                   value="1" id="on_sale"
                                                   onchange="applyFilters()">
                                            <label class="form-check-label" for="on_sale">On Sale Only</label>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- Banner -->
                            <div class="shop-widget-banner mt-30 mb-50">
                                <div class="banner-img" style="background-image:url({{ asset('assets/img/banner/shop-banner.html') }})"></div>
                                <div class="banner-content">
                                    <h6>Get <span>35% Off</span></h6>
                                    <h4>New Collection of Medicine</h4>
                                    <a href="{{ route('shop') }}" class="theme-btn">Shop Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Products Area -->
                    <div class="col-lg-9">
                        <div class="col-md-12">
                            <!-- Shop Sorting and View Options -->
                            <div class="shop-sort">
                                <div class="shop-sort-box">
                                    <div class="shop-sorty-label">Sort By:</div>
                                    <form action="{{ route('shop') }}" method="GET" id="sort-form" style="display: inline-block;">
                                        @foreach(request()->query() as $key => $value)
                                            @if($key != 'sort_by' && $key != 'page')
                                                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                            @endif
                                        @endforeach
                                        <select name="sort_by" class="select" onchange="document.getElementById('sort-form').submit()">
                                            <option value="default" @if($filters['sort_by'] == 'default') selected @endif>Default Sorting</option>
                                            <option value="latest" @if($filters['sort_by'] == 'latest') selected @endif>Latest Items</option>
                                            <option value="best_seller" @if($filters['sort_by'] == 'best_seller') selected @endif>Best Seller Items</option>
                                            <option value="price_low" @if($filters['sort_by'] == 'price_low') selected @endif>Price - Low To High</option>
                                            <option value="price_high" @if($filters['sort_by'] == 'price_high') selected @endif>Price - High To Low</option>
                                        </select>
                                    </form>
                                    <div class="shop-sort-show">
                                        Showing {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} of {{ $products->total() }} Results
                                    </div>
                                </div>
                                <div class="shop-sort-gl">
                                    <a href="#" class="shop-sort-grid active" data-bs-toggle="tooltip" title="Grid View"><i class="fas fa-th"></i></a>
                                </div>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="shop-item-wrap item-4" id="products-container">
                            @if($products->count() > 0)
                                <div class="row g-4">
                                    @foreach($products as $product)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="product-item">
                                                <div class="product-img">
                                                    @if($product->is_featured)
                                                        <span class="type">Featured</span>
                                                    @elseif($product->sale_price && $product->sale_price < $product->price)
                                                        <span class="type">Sale</span>
                                                    @else
                                                        <span class="type">New</span>
                                                    @endif
                                                    
                                                    <a href="{{ route('shop.show', $product->slug) }}">
                                                        <img src="{{ asset($product->image?->getUrl() ?? 'assets/img/product/01.png') }}" alt="{{ $product->name }}">
                                                    </a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                          <a href="#" data-bs-toggle="modal" data-bs-target="#quickview"
   data-product-id="{{ $product->id }}" title="Quick View">
   <i class="fas fa-eye"></i>
</a>

<a href="javascript:void(0)" class="add-to-wishlist"
   data-product-id="{{ $product->id }}" title="Add To Wishlist">
   <i class="fas fa-heart"></i>
</a>

<a href="#" title="Add To Compare">
   <i class="fas fa-sync-alt"></i>
</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="{{ route('shop.show', $product->slug) }}">{{ $product->name }}</a></h3>
                                                    
                                                    <div class="product-rate">
                                                        @for($i = 0; $i < 5; $i++)
                                                            @if($i < ($product->rating ?? 4))
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    
                                                    <div class="product-bottom">
                                                        <div class="product-price">
                                                            @if($product->sale_price && $product->sale_price < $product->price)
                                                                <span class="old-price">${{ number_format($product->price, 2) }}</span>
                                                                <span class="new-price">${{ number_format($product->sale_price, 2) }}</span>
                                                            @else
                                                                <span>${{ number_format($product->price, 2) }}</span>
                                                            @endif
                                                        </div>
                                                        <button type="button"
    class="product-cart-btn add-to-cart"
    data-product-id="{{ $product->id }}"
    data-bs-placement="left"
    title="Add To Cart">

    <i class="fas fa-shopping-bag"></i>
</button>
                                                    </div>
                                                    
                                                    @if($product->stock <= 0)
                                                        <span class="badge badge-danger mt-2">Out of Stock</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                        </div>
                                <!-- Pagination -->
                                <div class="pagination-area mt-50">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            @if($products->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="fas fa-arrow-left"></i></span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                                        <i class="fas fa-arrow-left"></i>
                                                    </a>
                                                </li>
                                            @endif

                                            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                                @if($page == $products->currentPage())
                                                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                                                @else
                                                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                                                @endif
                                            @endforeach

                                            @if($products->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $products->nextPageUrl() }}" aria-label="Next">
                                                        <i class="fas fa-arrow-right"></i>
                                                    </a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="fas fa-arrow-right"></i></span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            @else
                                <div class="alert alert-info text-center mt-5">
                                    <h5>No products found</h5>
                                    <p>Try adjusting your filters or search terms</p>
                                </div>
                            @endif
                        </div>
                </div>
            </div>
        </div>
        <!-- shop-area end -->

        @push('scripts')
        <script>
            function applyFilters() {
                let form = document.getElementById('filter-form');
                
                // Collect brand filters
                let brands = [];
                document.querySelectorAll('.brand-filter:checked').forEach(checkbox => {
                    brands.push(checkbox.value);
                });
                
                // Create form data
                let formData = new FormData(form);
                if (brands.length > 0) {
                    formData.set('brand', brands);
                }
                
                // Collect stock status
                let stockStatus = document.querySelector('input[name="stock_status"]:checked')?.value || '';
                if (stockStatus) {
                    formData.set('stock_status', stockStatus);
                }
                
                // Submit
                let url = new URL(form.action);
                formData.forEach((value, key) => {
                    if (key === 'brand' && Array.isArray(value)) {
                        value.forEach(v => url.searchParams.append(key + '[]', v));
                    } else {
                        url.searchParams.set(key, value);
                    }
                });
                
                window.location.href = url.toString();
            }

            // Price range slider initialization
            document.addEventListener('DOMContentLoaded', function() {
                const priceSliderDiv = document.querySelector('.price-range-slider');
                if (priceSliderDiv) {
                    const minVal = parseFloat(priceSliderDiv.dataset.selectedMin);
                    const maxVal = parseFloat(priceSliderDiv.dataset.selectedMax);
                    const min = parseFloat(priceSliderDiv.dataset.min);
                    const max = parseFloat(priceSliderDiv.dataset.max);

                    // Simple price range update (you can integrate a proper slider library like noUiSlider)
                    document.getElementById('filter-min-price').value = minVal;
                    document.getElementById('filter-max-price').value = maxVal;
                }

                // Add to cart
                document.querySelectorAll('.add-to-cart').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const productId = this.dataset.productId;
                        // Add your cart logic here
                        console.log('Added to cart:', productId);
                    });
                });

                // Add to wishlist
                document.querySelectorAll('.add-to-wishlist').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const productId = this.dataset.productId;
                        // Add your wishlist logic here
                        console.log('Added to wishlist:', productId);
                    });
                });
            });
        </script>
        @endpush

        @endsection