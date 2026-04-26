<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return view('admin.testimonial.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonial.create');
    }

    public function store(Request $request)
    {
        $testimonial = Testimonial::create([
            'name' => $request->name,
            'designation' => $request->designation,
            'message' => $request->message,
            'status' => $request->status ?? 1,
        ]);

        // image upload
        if ($request->hasFile('image')) {
            $testimonial->addMedia($request->file('image'))
                        ->toMediaCollection('testimonial');
        }

        return redirect()->route('testimonial.index')->with('success','Added');
    }

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        return view('admin.testimonial.edit', compact('testimonial'));
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $testimonial->update([
            'name' => $request->name,
            'designation' => $request->designation,
            'message' => $request->message,
            'status' => $request->status ?? 1,
        ]);

        // replace image
        if ($request->hasFile('image')) {
            $testimonial->clearMediaCollection('testimonial');
            $testimonial->addMedia($request->file('image'))
                        ->toMediaCollection('testimonial');
        }

        return redirect()->route('testimonial.index')->with('success','Updated');
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);
        $testimonial->clearMediaCollection('testimonial');
        $testimonial->delete();

        return back()->with('success','Deleted');
    }
}