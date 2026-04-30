@extends('custom.master')

@section('content')

<div class="shop-checkout py-90">
    <div class="container">
        <div class="text-center">
            <h2>Order Placed Successfully!</h2>
            <p>Your order number is:</p>

            <h4>{{ $order->order_number }}</h4>

            <p>Total Amount: ₹{{ number_format($order->total, 2) }}</p>
            <p>Payment Status: {{ ucfirst($order->payment_status) }}</p>

            <a href="{{ url('/shop') }}" class="theme-btn mt-3">
                Continue Shopping
            </a>
        </div>
    </div>
</div>

@endsection