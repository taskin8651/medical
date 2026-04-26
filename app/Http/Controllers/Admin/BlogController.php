<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);
        return view('admin.blog.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $blog = Blog::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        // Featured image (single)
        if ($request->hasFile('featured')) {
            $blog->addMedia($request->file('featured'))
                 ->toMediaCollection('featured');
        }

        // Multiple images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $blog->addMedia($file)
                     ->toMediaCollection('gallery');
            }
        }

        return redirect()->route('blog.index')->with('success','Blog Added');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        return view('admin.blog.edit', compact('blog'));
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::findOrFail($id);

        $blog->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'short_description' => $request->short_description,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        // Replace featured image
        if ($request->hasFile('featured')) {
            $blog->clearMediaCollection('featured');
            $blog->addMedia($request->file('featured'))
                 ->toMediaCollection('featured');
        }

        // Add more gallery images (optional: clear first)
        if ($request->hasFile('gallery')) {
            // optional: $blog->clearMediaCollection('gallery');
            foreach ($request->file('gallery') as $file) {
                $blog->addMedia($file)
                     ->toMediaCollection('gallery');
            }
        }

        return redirect()->route('blog.index')->with('success','Updated');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->clearMediaCollection();
        $blog->delete();

        return back()->with('success','Deleted');
    }
}