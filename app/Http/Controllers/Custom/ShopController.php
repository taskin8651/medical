<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', 1);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $categories = is_array($request->category) ? $request->category : [$request->category];
            $query->whereIn('category_id', $categories);
        }

        // Brand filter
        if ($request->filled('brand')) {
            $brands = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand_id', $brands);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Stock status filter
        if ($request->filled('stock_status')) {
            if ($request->stock_status == 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($request->stock_status == 'out_of_stock') {
                $query->where('stock', '<=', 0);
            }
        }

        // Sale/On Sale filter
        if ($request->filled('on_sale') && $request->on_sale == 1) {
            $query->whereNotNull('sale_price')
                  ->where('sale_price', '<', 'price');
        }

        // Rating filter
        if ($request->filled('rating')) {
            $rating = (int)$request->rating;
            $query->where('rating', '>=', $rating);
        }

        // Sorting
        $sortBy = $request->sort_by ?? 'default';
        switch ($sortBy) {
            case 'latest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'best_seller':
                $query->orderBy('sales_count', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->per_page ?? 12;
        $products = $query->paginate($perPage);

        // Get filter data for sidebar
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();
        
        // Price range
        $minPrice = Product::where('is_active', 1)->min('price') ?? 0;
        $maxPrice = Product::where('is_active', 1)->max('price') ?? 10000;

        // For AJAX request - return JSON
        if ($request->ajax()) {
            return response()->json([
                'products' => $products,
                'html' => view('custom.shop-products', ['products' => $products])->render(),
            ]);
        }

        return view('custom.shop', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'filters' => [
                'search' => $request->search,
                'category' => $request->category,
                'brand' => $request->brand,
                'min_price' => $request->min_price ?? $minPrice,
                'max_price' => $request->max_price ?? $maxPrice,
                'stock_status' => $request->stock_status,
                'sort_by' => $sortBy,
            ]
        ]);
    }

    public function filter(Request $request)
    {
        return $this->index($request);
    }
}
