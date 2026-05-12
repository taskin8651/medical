<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Gallery;
use App\Models\Hero;
use App\Models\Product;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        $heroes = Hero::where('status', 1)->latest()->take(5)->get();

        $categories = Category::active()
            ->withCount(['products' => fn ($query) => $query->where('is_active', 1)])
            ->latest()
            ->take(12)
            ->get();

        $featuredProducts = Product::with(['category', 'brand', 'media'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->latest()
            ->take(10)
            ->get();

        $latestProducts = Product::with(['category', 'brand', 'media'])
            ->where('is_active', 1)
            ->latest()
            ->take(12)
            ->get();

        $brands = Brand::withCount(['products' => fn ($query) => $query->where('is_active', 1)])
            ->where('is_active', 1)
            ->latest()
            ->take(12)
            ->get();

        $galleries = Gallery::with('media')
            ->latest()
            ->take(6)
            ->get();

        $testimonials = Testimonial::with('media')
            ->where('status', 1)
            ->latest()
            ->take(8)
            ->get();

        $blogs = Blog::with('media')
            ->where('status', 1)
            ->latest()
            ->take(3)
            ->get();

        return view('custom.home', compact(
            'heroes',
            'categories',
            'featuredProducts',
            'latestProducts',
            'brands',
            'galleries',
            'testimonials',
            'blogs'
        ));
    }
}
