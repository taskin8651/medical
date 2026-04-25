<?php

 
// ============================================================
// FILE: app/Http/Controllers/Admin/TierPricingController.php
// ============================================================
 
namespace App\Http\Controllers\Admin;
 
use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\TierPricing;
use Illuminate\Http\Request;
 
class TierPricingController extends Controller
{
    // ----------------------------------------------------------------
    // LIST (nested under variant)
    // ----------------------------------------------------------------
 
    public function index(ProductVariant $variant)
    {
        $tiers = $variant->tierPricings()->orderBy('customer_type')->orderBy('min_qty')->get();
        return view('admin.products.variants.tiers.index', compact('variant', 'tiers'));
    }
 
    // ----------------------------------------------------------------
    // STORE (AJAX or regular POST)
    // ----------------------------------------------------------------
 
    public function store(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'min_qty'          => 'required|integer|min:1',
            'max_qty'          => 'nullable|integer|gte:min_qty',
            'price_per_unit'   => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'customer_type'    => 'required|in:all,retailer,stockist,hospital',
            'is_active'        => 'boolean',
        ]);
 
        // Check for overlapping ranges for the same customer type
        $overlap = $variant->tierPricings()
            ->where('customer_type', $data['customer_type'])
            ->where('is_active', true)
            ->where(function ($q) use ($data) {
                $q->where(function ($q2) use ($data) {
                    $q2->where('min_qty', '<=', $data['min_qty'])
                       ->where(function ($q3) use ($data) {
                           $q3->whereNull('max_qty')
                              ->orWhere('max_qty', '>=', $data['min_qty']);
                       });
                });
                if (!is_null($data['max_qty'] ?? null)) {
                    $q->orWhere(function ($q2) use ($data) {
                        $q2->where('min_qty', '<=', $data['max_qty'])
                           ->where('min_qty', '>=', $data['min_qty']);
                    });
                }
            })->exists();
 
        if ($overlap) {
            return back()
                ->withInput()
                ->with('error', 'Qty range overlaps with an existing tier for this customer type.');
        }
 
        $tier = $variant->tierPricings()->create($data);
 
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'tier' => $tier]);
        }
 
        return back()->with('success', 'Tier pricing added.');
    }
 
    // ----------------------------------------------------------------
    // UPDATE
    // ----------------------------------------------------------------
 
    public function update(Request $request, ProductVariant $variant, TierPricing $tier)
    {
        $data = $request->validate([
            'min_qty'          => 'required|integer|min:1',
            'max_qty'          => 'nullable|integer|gte:min_qty',
            'price_per_unit'   => 'required|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'customer_type'    => 'required|in:all,retailer,stockist,hospital',
            'is_active'        => 'boolean',
        ]);
 
        $tier->update($data);
 
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }
 
        return back()->with('success', 'Tier updated.');
    }
 
    // ----------------------------------------------------------------
    // DESTROY
    // ----------------------------------------------------------------
 
    public function destroy(ProductVariant $variant, TierPricing $tier)
    {
        $tier->delete();
 
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
 
        return back()->with('success', 'Tier removed.');
    }
 
    // ----------------------------------------------------------------
    // BULK REPLACE — replace ALL tiers for a variant at once
    // POST body: { tiers: [ {min_qty, max_qty, price_per_unit, customer_type}, ... ] }
    // ----------------------------------------------------------------
 
    public function bulkReplace(Request $request, ProductVariant $variant)
    {
        $data = $request->validate([
            'tiers'                  => 'required|array|min:1',
            'tiers.*.min_qty'        => 'required|integer|min:1',
            'tiers.*.max_qty'        => 'nullable|integer',
            'tiers.*.price_per_unit' => 'required|numeric|min:0',
            'tiers.*.customer_type'  => 'required|in:all,retailer,stockist,hospital',
        ]);
 
        $variant->tierPricings()->delete();
 
        foreach ($data['tiers'] as $tier) {
            $variant->tierPricings()->create(array_merge($tier, ['is_active' => true]));
        }
 
        return response()->json(['success' => true, 'count' => count($data['tiers'])]);
    }
}