<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(10);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:brands,name',
            'manufacturer_name' => 'nullable|string|max:255',
            'drug_license_no' => 'nullable|string|max:100',
            'gst_no' => 'nullable|string|max:20',
        ]);

        Brand::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'manufacturer_name' => $request->manufacturer_name,
            'drug_license_no' => $request->drug_license_no,
            'gst_no' => $request->gst_no,
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand Created');
    }

    public function show(Brand $brand)
    {
        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|unique:brands,name,' . $brand->id,
            'manufacturer_name' => 'nullable|string|max:255',
            'drug_license_no' => 'nullable|string|max:100',
            'gst_no' => 'nullable|string|max:20',
        ]);

        $brand->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'manufacturer_name' => $request->manufacturer_name,
            'drug_license_no' => $request->drug_license_no,
            'gst_no' => $request->gst_no,
            'is_active' => $request->is_active ?? false,
        ]);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand Updated');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return back()->with('success', 'Brand Deleted');
    }
}