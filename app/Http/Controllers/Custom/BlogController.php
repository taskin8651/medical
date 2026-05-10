<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::where('status', 1)
            ->latest()
            ->paginate(9);

        return view('custom.blog', compact('blogs'));
    }

    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 1)
            ->firstOrFail();

        $recentBlogs = Blog::where('status', 1)
            ->where('id', '!=', $blog->id)
            ->latest()
            ->take(5)
            ->get();

        return view('custom.blog-detail', compact('blog', 'recentBlogs'));
    }
}