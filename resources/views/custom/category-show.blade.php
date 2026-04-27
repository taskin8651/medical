@extends('custom.master')
@section('content')

 <!-- breadcrumb -->
        <div class="site-breadcrumb">
            <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.html') }})"></div>
            <div class="container">
                <div class="site-breadcrumb-wrap">
                    <h4 class="breadcrumb-title">{{ $category->name }}</h4>
                    <ul class="breadcrumb-menu">
                        <li><a href="{{ route('home') }}"><i class="far fa-home"></i> Home</a></li>
                        <li><a href="{{ route('categories') }}">Categories</a></li>
                        <li class="active">{{ $category->name }}</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- breadcrumb end -->

        <!-- category products area -->
        <div class="shop-area bg py-90">
            <div class="container">
                <div class="row">
                    <!-- Sidebar Filters -->
                    <div class="col-lg-3">
                        <div class="shop-sidebar">
                            <!-- Category Info -->
                            <div class="shop-widget">
                                <div class="category-info-widget">
                                    <div class="category-info-img">
                                        <img src="{{ asset('storage/' . $category->image ?? 'assets/img/category/default.jpg') }}"
                                             alt="{{ $category->name }}"
                                             onerror="this.src='{{ asset('assets/img/category/default.jpg') }}'">
                                    </div>
                                    <h4 class="category-info-title">{{ $category->name }}</h4>
                                    @if($category->description)
                                        <p class="category-info-desc">{{ Str::limit($category->description, 100) }}</p>
                                    @endif
                                    <div class="category-stats">
                                        <span class="total-products">{{ $category->products()->where('is_active', 1)->count() }} Products</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Search Widget -->
                            <div class="shop-widget">
                                <div class="shop-search-form">
                                    <h4 class="shop-widget-title">Search in {{ $category->name }}</h4>
                                    <form action="{{ route('category.show', $category->slug) }}" method="GET" id="filter-form">
                                        <div class="form-group">
                                            <input type="text" name="search" class="form-control" placeholder="Search products..." value="{{ $filters['search'] ?? '' }}">
                                            <button type="submit"><i class="far fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
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
                                                    <span>({{ $category->products()->where('brand_id', $brand->id)->where('is_active', 1)->count() }})</span>
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
                                    <form method="GET" action="{{ route('category.show', $category->slug) }}" class="mt-3">
                                        @foreach(request()->query() as $key => $value)
                                            @if($key != 'min_price' && $key != 'max_price')
                                                <input type="hidden" name="{{ $key }}" value="{{ is_array($value) ? implode(',', $value) : $value }}">
                                            @endif
                                        @endforeach
                                        <input type="hidden" name="min_price" id="filter-min-price" value="{{ $filters['min_price'] ?? $minPrice }}">
                                        <input type="hidden" name="max_price" id="filter-max-price" value="{{ $filters['max_price'] ?? $maxPrice }}">
                                        <button type="submit" class="btn btn-sm btn-primary w-100">Apply Price</button>
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
                        </div>
                    </div>

                    <!-- Products Area -->
                    <div class="col-lg-9">
                        <div class="col-md-12">
                            <!-- Shop Sorting and View Options -->
                            <div class="shop-sort">
                                <div class="shop-sort-box">
                                    <div class="shop-sorty-label">Sort By:</div>
                                    <form action="{{ route('category.show', $category->slug) }}" method="GET" id="sort-form" style="display: inline-block;">
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
                                    <a href="#" class="shop-sort-grid active" data-bs-toggle="tooltip" title="Grid View"><i class="far fa-grid-round-2"></i></a>
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

                                                    <a href="{{ route('product.show', $product->slug) }}">
                                                        <img src="{{ asset('storage/' . $product->image ?? 'assets/img/product/01.png') }}" alt="{{ $product->name }}">
                                                    </a>
                                                    <div class="product-action-wrap">
                                                        <div class="product-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#quickview" data-product-id="{{ $product->id }}" data-tooltip="tooltip" title="Quick View"><i class="far fa-eye"></i></a>
                                                            <a href="javascript:void(0)" class="add-to-wishlist" data-product-id="{{ $product->id }}" data-tooltip="tooltip" title="Add To Wishlist"><i class="far fa-heart"></i></a>
                                                            <a href="#" data-tooltip="tooltip" title="Add To Compare"><i class="far fa-arrows-repeat"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="product-content">
                                                    <h3 class="product-title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h3>

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
                                                        <button type="button" class="product-cart-btn add-to-cart" data-product-id="{{ $product->id }}" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart">
                                                            <i class="far fa-shopping-bag"></i>
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

                                <!-- Pagination -->
                                <div class="pagination-area mt-50">
                                    <nav aria-label="Page navigation">
                                        <ul class="pagination">
                                            @if($products->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="far fa-arrow-left"></i></span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $products->previousPageUrl() }}" aria-label="Previous">
                                                        <i class="far fa-arrow-left"></i>
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
                                                        <i class="far fa-arrow-right"></i>
                                                    </a>
                                                </li>
                                            @else
                                                <li class="page-item disabled">
                                                    <span class="page-link"><i class="far fa-arrow-right"></i></span>
                                                </li>
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            @else
                                <div class="alert alert-info text-center mt-5">
                                    <h5>No products found in {{ $category->name }}</h5>
                                    <p>Try adjusting your filters or <a href="{{ route('categories') }}">browse other categories</a></p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- category products area end -->

        @push('styles')
        <style>
            .category-info-widget {
                background: #f8f9fa;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                margin-bottom: 30px;
            }

            .category-info-img {
                width: 80px;
                height: 80px;
                margin: 0 auto 15px;
                border-radius: 50%;
                overflow: hidden;
                border: 3px solid #007bff;
            }

            .category-info-img img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .category-info-title {
                font-size: 18px;
                font-weight: 600;
                color: #333;
                margin-bottom: 10px;
            }

            .category-info-desc {
                color: #666;
                font-size: 14px;
                margin-bottom: 15px;
            }

            .category-stats {
                font-size: 12px;
                color: #007bff;
                font-weight: 500;
            }
        </style>
        @endpush

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

            // Add to cart
            document.querySelectorAll('.add-to-cart').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    console.log('Added to cart:', productId);
                });
            });

            // Add to wishlist
            document.querySelectorAll('.add-to-wishlist').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const productId = this.dataset.productId;
                    console.log('Added to wishlist:', productId);
                });
            });
        </script>
        @endpush

@endsection