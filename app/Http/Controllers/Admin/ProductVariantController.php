<?php
 
// ============================================================
// FILE: app/Http/Controllers/Admin/ProductVariantController.php
// ============================================================
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
 
class ProductVariantController extends Controller
{
    // ----------------------------------------------------------------
    // LIST  (nested under product)
    // ----------------------------------------------------------------
 
    public function index(Product $product)
    {
        $variants = $product->variants()
            ->with('tierPricings')
            ->withTrashed()
            ->latest()
            ->get();
 
        return view('admin.products.variants.index', compact('product', 'variants'));
    }
 
    // ----------------------------------------------------------------
    // CREATE / STORE
    // ----------------------------------------------------------------
 
    public function create(Product $product)
    {
        return view('admin.products.variants.create', compact('product'));
    }
 
    public function store(Request $request, Product $product)
    {
        $validated = $this->validateVariant($request);
 
        $variant = $product->variants()->create($validated);
 
        // Optionally seed a default tier pricing row
        if ($request->filled('default_min_qty')) {
            $variant->tierPricings()->create([
                'min_qty'        => $request->default_min_qty,
                'max_qty'        => null,
                'price_per_unit' => $validated['price'],
                'customer_type'  => 'all',
                'is_active'      => true,
            ]);
        }
 
        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Variant added.');
    }
 
    // ----------------------------------------------------------------
    // EDIT / UPDATE
    // ----------------------------------------------------------------
 
    public function edit(Product $product, ProductVariant $variant)
    {
        $variant->load('tierPricings');
        return view('admin.products.variants.edit', compact('product', 'variant'));
    }
 
    public function update(Request $request, Product $product, ProductVariant $variant)
    {
        $validated = $this->validateVariant($request, $variant->id);
        $variant->update($validated);
 
        return redirect()
            ->route('admin.products.variants.index', $product)
            ->with('success', 'Variant updated.');
    }
 
    // ----------------------------------------------------------------
    // DESTROY / RESTORE
    // ----------------------------------------------------------------
 
    public function destroy(Product $product, ProductVariant $variant)
    {
        $variant->delete(); // soft delete — order history stays intact
        return back()->with('success', 'Variant removed.');
    }
 
    public function restore(Product $product, int $variantId)
    {
        ProductVariant::withTrashed()->findOrFail($variantId)->restore();
        return back()->with('success', 'Variant restored.');
    }
 
    // ----------------------------------------------------------------
    // STOCK ADJUSTMENT (AJAX)
    // POST body: { type: 'add'|'subtract', qty: 50, reason: 'Purchase' }
    // ----------------------------------------------------------------
 
    public function adjustStock(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'type'   => 'required|in:add,subtract',
            'qty'    => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);
 
        $newStock = $data['type'] === 'add'
            ? $variant->stock + $data['qty']
            : max(0, $variant->stock - $data['qty']);
 
        $variant->update(['stock' => $newStock]);
 
        // TODO: log to a stock_movements table if you need full audit trail
 
        return response()->json([
            'success'   => true,
            'new_stock' => $newStock,
            'low_stock' => $variant->is_low_stock,
        ]);
    }
 
    // ----------------------------------------------------------------
    // UPDATE BATCH / EXPIRY (AJAX)
    // ----------------------------------------------------------------
 
    public function updateBatch(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'batch_number' => 'required|string|max:100',
            'expiry_date'  => 'required|date|after:today',
        ]);
 
        $variant->update($data);
 
        return response()->json(['success' => true]);
    }
 
    // ----------------------------------------------------------------
    // PRICE CHECK (AJAX) — returns tier price for a qty & customer type
    // ----------------------------------------------------------------
 
    public function checkPrice(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'qty'           => 'required|integer|min:1',
            'customer_type' => 'nullable|string',
        ]);
 
        $price = $variant->getPriceForQty($data['qty'], $data['customer_type'] ?? 'all');
 
        $gstRate   = $variant->effective_gst_rate;
        $gstAmount = round($price * $gstRate / 100, 2);
 
        return response()->json([
            'unit_price'   => $price,
            'gst_rate'     => $gstRate,
            'gst_amount'   => $gstAmount,
            'price_incl'   => $price + $gstAmount,
            'line_total'   => round(($price + $gstAmount) * $data['qty'], 2),
        ]);
    }
 
    // ----------------------------------------------------------------
    // PRIVATE HELPER
    // ----------------------------------------------------------------
 
    private function validateVariant(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'                  => 'required|string|max:255',
            'sku'                   => 'required|string|max:100|unique:product_variants,sku,' . $ignoreId,
            'barcode'               => 'nullable|string|max:100',
            'strength'              => 'nullable|string|max:100',
            'pack_size'             => 'nullable|string|max:100',
            'pack_type'             => 'nullable|string|max:100',
            'batch_number'          => 'nullable|string|max:100',
            'expiry_date'           => 'nullable|date',
            'manufacturer_batch_no' => 'nullable|string|max:100',
            'mrp'                   => 'nullable|numeric|min:0',
            'ptr'                   => 'nullable|numeric|min:0',
            'pts'                   => 'nullable|numeric|min:0',
            'price'                 => 'required|numeric|min:0',
            'gst_rate'              => 'nullable|in:0,5,12,18',
            'stock'                 => 'integer|min:0',
            'low_stock_alert'       => 'integer|min:0',
            'is_active'             => 'boolean',
        ]);
    }
}