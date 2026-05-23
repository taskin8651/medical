<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->get();
        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $gallery = Gallery::create([
            'title' => $request->title
        ]);

        // Multiple images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $gallery->addMedia($file)->toMediaCollection('gallery', 'public');
            }
        }

        return redirect()->route('admin.gallery.index')->with('success','Gallery Added');
    }

    public function edit($id)
    {
        $gallery = Gallery::findOrFail($id);
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(Request $request, $id)
    {
        $gallery = Gallery::findOrFail($id);

        $gallery->update([
            'title' => $request->title
        ]);

        if ($request->hasFile('images')) {
            $gallery->clearMediaCollection('gallery'); // optional
            foreach ($request->file('images') as $file) {
                $gallery->addMedia($file)->toMediaCollection('gallery', 'public');
            }
        }

        return redirect()->route('admin.gallery.index')->with('success','Updated');
    }

    public function deleteMedia(Gallery $gallery, $mediaId)
    {
        $media = $gallery->media()->findOrFail($mediaId);
        $media->delete();

        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Image deleted');
    }

    public function destroy($id)
    {
        $gallery = Gallery::findOrFail($id);
        $gallery->clearMediaCollection('gallery');
        $gallery->delete();

        return back()->with('success','Deleted');
    }
}
