<?php

namespace App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::check()
            ? Order::with('items')->where('user_id', Auth::id())->latest()->paginate(8)
            : null;

        return view('custom.orders.index', compact('orders'));
    }

    public function track(Request $request)
    {
        $validated = $request->validate([
            'order_number' => ['required', 'string', 'max:50'],
            'identifier' => ['required', 'string', 'max:150'],
        ]);

        $order = Order::with('items')
            ->where('order_number', trim($validated['order_number']))
            ->first();

        if (!$order || !$this->identifierMatches($order, $validated['identifier'])) {
            return back()
                ->withInput()
                ->with('error', 'Order not found. Please check your order number and email or phone.');
        }

        session()->put('verified_customer_orders.' . $order->id, true);

        return redirect()->route('orders.show', $order->order_number);
    }

    public function show(string $orderNumber)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $isOwner = Auth::check() && (int) $order->user_id === (int) Auth::id();
        $isVerified = session()->has('verified_customer_orders.' . $order->id);

        if (!$isOwner && !$isVerified) {
            return redirect()
                ->route('orders.index')
                ->with('error', 'Please verify your order number with email or phone to view details.');
        }

        return view('custom.orders.show', compact('order'));
    }

    private function identifierMatches(Order $order, string $identifier): bool
    {
        $identifier = trim($identifier);
        $normalizedIdentifier = $this->normalizePhone($identifier);
        $lowerIdentifier = strtolower($identifier);

        foreach ([$order->billing_address ?? [], $order->shipping_address ?? []] as $address) {
            if (!is_array($address)) {
                continue;
            }

            $email = strtolower(trim($address['email'] ?? ''));
            $phone = $this->normalizePhone($address['phone'] ?? '');

            if ($email && $email === $lowerIdentifier) {
                return true;
            }

            if ($phone && $phone === $normalizedIdentifier) {
                return true;
            }
        }

        return false;
    }

    private function normalizePhone(string $phone): string
    {
        return preg_replace('/\D+/', '', $phone) ?? '';
    }
}
