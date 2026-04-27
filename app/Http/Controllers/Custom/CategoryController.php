<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display all categories
     */
    public function index()
    {
        $categories = Category::where('is_active', 1)->withCount('products')->get();

        return view('custom.categories', compact('categories'));
    }

    /**
     * Display products for a specific category
     */
    public function show(Request $request, $slug)
    {
        $category = Category::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        $query = Product::where('category_id', $category->id)->where('is_active', 1);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('sku', 'like', "%$search%");
            });
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
        $brands = $category->products()->with('brand')->get()->pluck('brand')->unique()->filter();

        // Price range
        $minPrice = $category->products()->where('is_active', 1)->min('price') ?? 0;
        $maxPrice = $category->products()->where('is_active', 1)->max('price') ?? 10000;

        return view('custom.category-show', [
            'category' => $category,
            'products' => $products,
            'brands' => $brands,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'filters' => [
                'search' => $request->search,
                'brand' => $request->brand,
                'min_price' => $request->min_price ?? $minPrice,
                'max_price' => $request->max_price ?? $maxPrice,
                'stock_status' => $request->stock_status,
                'sort_by' => $sortBy,
            ]
        ]);
    }
}