<?php
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMedia;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
 
class ProductController extends Controller
{
    // ----------------------------------------------------------------
    // LIST
    // ----------------------------------------------------------------
 
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'category', 'primaryImage'])
            ->withCount('variants');
 
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('sku', 'like', "%{$s}%")
                  ->orWhere('generic_name', 'like', "%{$s}%")
                  ->orWhere('composition', 'like', "%{$s}%");
            });
        }
 
        if ($request->filled('category_id'))        $query->where('category_id', $request->category_id);
        if ($request->filled('brand_id'))           $query->where('brand_id', $request->brand_id);
        if ($request->filled('drug_schedule'))      $query->where('drug_schedule', $request->drug_schedule);
        if ($request->filled('requires_prescription')) $query->where('requires_prescription', $request->requires_prescription);
        if ($request->filled('is_active'))          $query->where('is_active', $request->is_active);
 
        $products   = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::active()->get();
        $brands     = Brand::where('is_active', true)->get();
 
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }
 
    // ----------------------------------------------------------------
    // CREATE / STORE
    // ----------------------------------------------------------------
 
    public function create()
    {
        $categories = Category::active()->get();
        $brands     = Brand::where('is_active', true)->get();
 
        return view('admin.products.create', compact('categories', 'brands'));
    }
 
    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);
 
        DB::beginTransaction();
        try {
            $validated['slug'] = $this->uniqueSlug($request->name);
            $product = Product::create($validated);
            $this->handleMediaUploads($request, $product);
            DB::commit();
 
            return redirect()->route('admin.products.show', $product)
                ->with('success', 'Product created successfully.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed: ' . $e->getMessage());
        }
    }
 
    // ----------------------------------------------------------------
    // SHOW / EDIT / UPDATE / DELETE
    // ----------------------------------------------------------------
 
    public function show(Product $product)
    {
        $product->load(['brand', 'category', 'subcategory', 'variants.tierPricings', 'media']);
        return view('admin.products.show', compact('product'));
    }
 
    public function edit(Product $product)
    {
        $product->load('media');
        $categories    = Category::active()->get();
        $subcategories = Subcategory::where('category_id', $product->category_id)->get();
        $brands        = Brand::where('is_active', true)->get();
 
        return view('admin.products.edit', compact('product', 'categories', 'subcategories', 'brands'));
    }
 
    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);
 
        DB::beginTransaction();
        try {
            $product->update($validated);
            $this->handleMediaUploads($request, $product);
            DB::commit();
 
            return redirect()->route('admin.products.show', $product)
                ->with('success', 'Product updated.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }
 
    public function destroy(Product $product)
    {
        foreach ($product->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }
        $product->delete();
 
        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }
 
    // ----------------------------------------------------------------
    // AJAX ACTIONS
    // ----------------------------------------------------------------
 
    /** Toggle active/inactive */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['success' => true, 'is_active' => $product->is_active]);
    }
 
    /** Delete one media file */
    public function deleteMedia(ProductMedia $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();
        return response()->json(['success' => true]);
    }
 
    /** Mark one image as primary, unset others */
    public function setPrimaryImage(ProductMedia $media)
    {
        ProductMedia::where('product_id', $media->product_id)->update(['is_primary' => false]);
        $media->update(['is_primary' => true]);
        return response()->json(['success' => true]);
    }
 
    /** Drag-and-drop sort: POST body { ids: [3,1,5,2] } */
    public function reorderMedia(Request $request, Product $product)
    {
        $ids = $request->validate(['ids' => 'required|array'])['ids'];
        foreach ($ids as $order => $id) {
            ProductMedia::where('id', $id)->where('product_id', $product->id)
                ->update(['sort_order' => $order]);
        }
        return response()->json(['success' => true]);
    }
 
    /** Return subcategories for a category (for cascading dropdown) */
    public function getSubcategories(Category $category)
    {
        return response()->json($category->subcategories()->select('id', 'name')->get());
    }
 
    // ----------------------------------------------------------------
    // PRIVATE HELPERS
    // ----------------------------------------------------------------
 
    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'                  => 'required|string|max:255',
            'category_id'           => 'required|exists:categories,id',
            'subcategory_id'        => 'nullable|exists:subcategories,id',
            'brand_id'              => 'nullable|exists:brands,id',
            'sku'                   => 'nullable|string|max:100|unique:products,sku,' . $ignoreId,
            'short_description'     => 'nullable|string|max:500',
            'description'           => 'nullable|string',
            'generic_name'          => 'nullable|string|max:255',
            'composition'           => 'nullable|string',
            'hsn_code'              => 'nullable|string|max:20',
            'drug_schedule'         => 'nullable|in:H,H1,X,G,OTC',
            'requires_prescription' => 'boolean',
            'form'                  => 'nullable|string|max:100',
            'strength'              => 'nullable|string|max:100',
            'storage_conditions'    => 'nullable|string',
            'side_effects'          => 'nullable|string',
            'contraindications'     => 'nullable|string',
            'shelf_life'            => 'nullable|string|max:100',
            'mrp'                   => 'nullable|numeric|min:0',
            'ptr'                   => 'nullable|numeric|min:0',
            'pts'                   => 'nullable|numeric|min:0',
            'price'                 => 'required|numeric|min:0',
            'sale_price'            => 'nullable|numeric|min:0',
            'gst_rate'              => 'required|in:0,5,12,18',
            'pack_size'             => 'nullable|string|max:100',
            'pack_type'             => 'nullable|string|max:100',
            'units_per_pack'        => 'integer|min:1',
            'min_qty'               => 'integer|min:1',
            'max_qty'               => 'nullable|integer|gte:min_qty',
            'stock'                 => 'integer|min:0',
            'is_active'             => 'boolean',
            'is_featured'           => 'boolean',
            'images'                => 'nullable|array|max:10',
            'images.*'              => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'documents'             => 'nullable|array|max:5',
            'documents.*'           => 'file|mimes:pdf|max:5120',
        ]);
    }
 
    private function handleMediaUploads(Request $request, Product $product): void
    {
        if ($request->hasFile('images')) {
            $hasPrimary = $product->media()->where('type', 'image')->where('is_primary', true)->exists();
            foreach ($request->file('images') as $i => $image) {
                $this->storeMedia($product, $image, 'image', !$hasPrimary && $i === 0);
            }
        }
 
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $doc) {
                $this->storeMedia($product, $doc, 'brochure', false);
            }
        }
    }
 
    private function storeMedia(Product $product, $file, string $type, bool $isPrimary): ProductMedia
    {
        $path     = $file->store("products/{$product->id}/{$type}s", 'public');
        $maxOrder = $product->media()->max('sort_order') ?? 0;
 
        return $product->media()->create([
            'file_path'  => $path,
            'file_name'  => $file->getClientOriginalName(),
            'mime_type'  => $file->getMimeType(),
            'file_size'  => $file->getSize(),
            'type'       => $type,
            'alt_text'   => $product->name,
            'is_primary' => $isPrimary,
            'sort_order' => $maxOrder + 1,
        ]);
    }
 
    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }
        return $slug;
    }
}