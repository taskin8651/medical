<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $summary = $this->calculateCartSummary($cart);

        return view('custom.checkout', [
            'cart' => $cart,
            'subTotal' => $summary['subtotal'],
            'discount' => $summary['discount'],
            'shipping' => $summary['shipping'],
            'tax' => $summary['tax'],
            'total' => $summary['total'],
        ]);
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'billing_first_name' => 'required|string|max:100',
            'billing_last_name' => 'nullable|string|max:100',
            'billing_email' => 'required|email|max:150',
            'billing_phone' => 'required|string|max:20',
            'billing_address_1' => 'required|string|max:255',
            'billing_address_2' => 'nullable|string|max:255',
            'billing_country' => 'required|string|max:100',
            'billing_state' => 'required|string|max:100',
            'billing_city' => 'required|string|max:100',
            'billing_postcode' => 'required|string|max:20',

            'buyer_gst_no' => 'nullable|string|max:50',
            'buyer_drug_license' => 'nullable|string|max:100',

            'same_as_billing' => 'nullable|in:1',

            'shipping_first_name' => 'nullable|string|max:100',
            'shipping_last_name' => 'nullable|string|max:100',
            'shipping_email' => 'nullable|email|max:150',
            'shipping_phone' => 'nullable|string|max:20',
            'shipping_address_1' => 'nullable|string|max:255',
            'shipping_address_2' => 'nullable|string|max:255',
            'shipping_country' => 'nullable|string|max:100',
            'shipping_state' => 'nullable|string|max:100',
            'shipping_city' => 'nullable|string|max:100',
            'shipping_postcode' => 'nullable|string|max:20',

            'shipping_method' => 'required|string|in:standard,express,courier',
            'payment_method' => 'required|string|in:cod,bank_transfer,upi',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $billingAddress = [
                'first_name' => $request->billing_first_name,
                'last_name' => $request->billing_last_name,
                'name' => trim($request->billing_first_name . ' ' . $request->billing_last_name),
                'email' => $request->billing_email,
                'phone' => $request->billing_phone,
                'address_1' => $request->billing_address_1,
                'address_2' => $request->billing_address_2,
                'country' => $request->billing_country,
                'state' => $request->billing_state,
                'city' => $request->billing_city,
                'postcode' => $request->billing_postcode,
            ];

            if ($request->same_as_billing == 1) {
                $shippingAddress = $billingAddress;
            } else {
                $shippingAddress = [
                    'first_name' => $request->shipping_first_name,
                    'last_name' => $request->shipping_last_name,
                    'name' => trim($request->shipping_first_name . ' ' . $request->shipping_last_name),
                    'email' => $request->shipping_email,
                    'phone' => $request->shipping_phone,
                    'address_1' => $request->shipping_address_1,
                    'address_2' => $request->shipping_address_2,
                    'country' => $request->shipping_country,
                    'state' => $request->shipping_state,
                    'city' => $request->shipping_city,
                    'postcode' => $request->shipping_postcode,
                ];
            }

            $summary = $this->calculateCartSummary($cart, $request->shipping_method);

            $sellerState = config('app.seller_state', env('SELLER_STATE', 'Rajasthan'));
            $buyerState = $shippingAddress['state'] ?? $billingAddress['state'];

            $isInterState = strtolower(trim($sellerState)) !== strtolower(trim($buyerState));

            $cgst = $isInterState ? 0 : round($summary['tax'] / 2, 2);
            $sgst = $isInterState ? 0 : round($summary['tax'] / 2, 2);
            $igst = $isInterState ? $summary['tax'] : 0;

            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'user_id' => Auth::id(),

                'buyer_gst_no' => $request->buyer_gst_no,
                'buyer_drug_license' => $request->buyer_drug_license,

                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,

                'subtotal' => $summary['subtotal'],
                'total_gst' => $summary['tax'],
                'cgst' => $cgst,
                'sgst' => $sgst,
                'igst' => $igst,

                'discount_amount' => $summary['discount'],
                'coupon_code' => null,
                'shipping_charge' => $summary['shipping'],
                'total' => $summary['total'],

                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_terms' => $request->payment_method === 'cod' ? 'Cash On Delivery' : 'Prepaid',
                'due_date' => now()->addDays(7),
                'amount_paid' => 0,

                'invoice_number' => Order::generateInvoiceNumber(),
                'invoice_date' => now(),

                'status' => 'pending',
                'dispatch_mode' => $request->shipping_method,
                'tracking_number' => null,

                'notes' => $request->notes,
                'internal_notes' => null,
            ]);

            foreach ($cart as $productId => $item) {
                $product = Product::find($productId);

                if (!$product || !$product->is_active) {
                    throw new \Exception('Product not available: ' . ($item['name'] ?? 'Unknown'));
                }

                $qty = (int) ($item['quantity'] ?? 1);

                if ($qty > $product->stock) {
                    throw new \Exception('Only ' . $product->stock . ' quantity available for ' . $product->name);
                }

                $unitPrice = $product->sale_price ?? $product->price ?? 0;
                $gstRate = $product->gst_rate ?? 0;

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_variant_id = null;

                $orderItem->product_name = $product->name;
                $orderItem->variant_name = trim(($product->pack_size ?? '') . ' ' . ($product->pack_type ?? ''));
                $orderItem->sku = $product->sku;
                $orderItem->hsn_code = $product->hsn_code;

                $orderItem->batch_number = null;
                $orderItem->expiry_date = null;

                $orderItem->qty = $qty;
                $orderItem->mrp = $product->mrp ?? $unitPrice;
                $orderItem->unit_price = $unitPrice;

                $orderItem->discount_percent = 0;
                $orderItem->gst_rate = $gstRate;

                $orderItem->calculateTotals($isInterState);
                $orderItem->save();

                $product->decrement('stock', $qty);
            }

            session()->forget('cart');

            DB::commit();

            return redirect()
                ->route('checkout.success', $order->id)
                ->with('success', 'Order placed successfully.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    private function calculateCartSummary(array $cart, string $shippingMethod = 'standard'): array
    {
        $subtotal = 0;
        $tax = 0;

        foreach ($cart as $item) {
            $price = $item['price'] ?? 0;
            $quantity = $item['quantity'] ?? 1;
            $gstAmount = $item['gst_amount'] ?? 0;

            $subtotal += $price * $quantity;
            $tax += $gstAmount * $quantity;
        }

        $discount = 0;

        $shipping = match ($shippingMethod) {
            'express' => 150,
            'courier' => 250,
            default => 0,
        };

        $total = $subtotal - $discount + $shipping + $tax;

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'shipping' => round($shipping, 2),
            'tax' => round($tax, 2),
            'total' => round($total, 2),
        ];
    }

    public function success($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        return view('custom.order-success', compact('order'));
    }
}