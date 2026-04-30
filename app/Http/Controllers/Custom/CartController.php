<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        $subTotal = 0;
        $tax = 0;

        foreach ($cart as $item) {
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 1;
            $gstAmount = $item['gst_amount'] ?? 0;

            $subTotal += $price * $quantity;
            $tax += $gstAmount * $quantity;
        }

        $discount = 0;
        $shipping = 0;

        $total = $subTotal - $discount + $shipping + $tax;

        return view('custom.cart', compact(
            'cart',
            'subTotal',
            'discount',
            'shipping',
            'tax',
            'total'
        ));
    }

    public function add(Request $request, Product $product)
    {
        if (!$product->is_active) {
            return back()->with('error', 'Product is not available.');
        }

        if ($product->stock <= 0) {
            return back()->with('error', 'Product is out of stock.');
        }

        $minQty = $product->min_qty ?? 1;
        $maxQty = $product->max_qty ?? $product->stock;

        if ($maxQty <= 0) {
            $maxQty = $product->stock;
        }

        $quantity = (int) $request->input('quantity', $minQty);

        if ($quantity < $minQty) {
            $quantity = $minQty;
        }

        if ($quantity > $maxQty) {
            $quantity = $maxQty;
        }

        if ($quantity > $product->stock) {
            $quantity = $product->stock;
        }

        $cart = session()->get('cart', []);

        if ($product->primaryImage()) {
            $image = $product->primaryImage()->getUrl();
        } elseif ($product->getFirstMediaUrl('images')) {
            $image = $product->getFirstMediaUrl('images');
        } else {
            $image = asset('assets/img/product/no-image.png');
        }

        $price = $product->sale_price ?? $product->price ?? 0;
        $gstRate = $product->gst_rate ?? 0;
        $gstAmount = round(($price * $gstRate) / 100, 2);
        $priceWithGst = round($price + $gstAmount, 2);

        if (isset($cart[$product->id])) {
            $oldQuantity = $cart[$product->id]['quantity'] ?? 0;
            $newQuantity = $oldQuantity + $quantity;

            if ($newQuantity > $maxQty) {
                $newQuantity = $maxQty;
            }

            if ($newQuantity > $product->stock) {
                $newQuantity = $product->stock;
            }

            $cart[$product->id]['quantity'] = $newQuantity;
            $cart[$product->id]['stock'] = $product->stock;
            $cart[$product->id]['min_qty'] = $minQty;
            $cart[$product->id]['max_qty'] = $maxQty;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'sku' => $product->sku,

                'category' => $product->category->name ?? null,
                'brand' => $product->brand->name ?? null,

                'generic_name' => $product->generic_name,
                'composition' => $product->composition,
                'drug_schedule' => $product->drug_schedule,
                'requires_prescription' => $product->requires_prescription,

                'pack_size' => $product->pack_size,
                'pack_type' => $product->pack_type,
                'units_per_pack' => $product->units_per_pack,

                'price' => $price,
                'gst_rate' => $gstRate,
                'gst_amount' => $gstAmount,
                'price_with_gst' => $priceWithGst,

                'quantity' => $quantity,
                'min_qty' => $minQty,
                'max_qty' => $maxQty,
                'stock' => $product->stock,

                'image' => $image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request, $productId)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$productId])) {
            return back()->with('error', 'Product not found in cart.');
        }

        $product = Product::find($productId);

        if (!$product) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return back()->with('error', 'Product is no longer available.');
        }

        if (!$product->is_active) {
            unset($cart[$productId]);
            session()->put('cart', $cart);

            return back()->with('error', 'Product is not active.');
        }

        $minQty = $product->min_qty ?? 1;
        $maxQty = $product->max_qty ?? $product->stock;

        if ($maxQty <= 0) {
            $maxQty = $product->stock;
        }

        $action = $request->input('action');
        $currentQty = $cart[$productId]['quantity'] ?? $minQty;

        if ($action === 'plus') {
            if ($currentQty < $maxQty && $currentQty < $product->stock) {
                $cart[$productId]['quantity'] = $currentQty + 1;
            } else {
                return back()->with('error', 'Maximum quantity limit reached.');
            }
        }

        if ($action === 'minus') {
            if ($currentQty > $minQty) {
                $cart[$productId]['quantity'] = $currentQty - 1;
            } else {
                return back()->with('error', 'Minimum quantity limit is '.$minQty.'.');
            }
        }

        $price = $product->sale_price ?? $product->price ?? 0;
        $gstRate = $product->gst_rate ?? 0;
        $gstAmount = round(($price * $gstRate) / 100, 2);
        $priceWithGst = round($price + $gstAmount, 2);

        $cart[$productId]['price'] = $price;
        $cart[$productId]['gst_rate'] = $gstRate;
        $cart[$productId]['gst_amount'] = $gstAmount;
        $cart[$productId]['price_with_gst'] = $priceWithGst;
        $cart[$productId]['stock'] = $product->stock;
        $cart[$productId]['min_qty'] = $minQty;
        $cart[$productId]['max_qty'] = $maxQty;

        session()->put('cart', $cart);

        return back()->with('success', 'Cart updated successfully.');
    }

    public function remove($productId)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Product removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');

        return back()->with('success', 'Cart cleared successfully.');
    }
}