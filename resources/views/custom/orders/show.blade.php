@extends('custom.master')

@section('title', 'Order ' . $order->order_number)

@section('content')
@php
    $billing = is_array($order->billing_address) ? $order->billing_address : [];
    $shipping = is_array($order->shipping_address) ? $order->shipping_address : $billing;
    $status = $order->status ?? 'pending';
    $paymentStatus = $order->payment_status ?? 'pending';
    $amountPaid = (float) ($order->amount_paid ?? 0);
    $balanceDue = max(0, (float) ($order->total ?? 0) - $amountPaid);
    $steps = ['pending' => 'Placed', 'confirmed' => 'Confirmed', 'processing' => 'Processing', 'dispatched' => 'Dispatched', 'delivered' => 'Delivered'];
    $statusIndex = array_search($status, array_keys($steps), true);
    $statusIndex = $statusIndex === false ? 0 : $statusIndex;
@endphp

<style>
    .order-detail-page { background:#f8fafc; }
    .order-shell {
        background:#fff;
        border:1px solid #e2e8f0;
        border-radius:8px;
        overflow:hidden;
        box-shadow:0 16px 38px rgba(15,23,42,.06);
    }
    .order-hero {
        padding:28px;
        background:linear-gradient(135deg,#f8fbff,#ffffff);
        border-bottom:1px solid #e2e8f0;
        display:grid;
        grid-template-columns:1.2fr .8fr;
        gap:24px;
        align-items:start;
    }
    .back-orders {
        display:inline-flex;
        align-items:center;
        gap:8px;
        color:var(--theme-color);
        font-weight:800;
        margin-bottom:12px;
    }
    .order-hero h1 { margin:0 0 8px; font-size:30px; color:#0f172a; font-weight:900; }
    .order-meta { color:#64748b; margin:0; }
    .hero-actions { display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap; }
    .outline-btn {
        display:inline-flex;
        align-items:center;
        gap:8px;
        border:1px solid #dbe3ef;
        background:#fff;
        color:#0f172a;
        border-radius:7px;
        padding:11px 16px;
        font-weight:800;
        cursor:pointer;
    }
    .status-pill {
        display:inline-flex;
        align-items:center;
        gap:7px;
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        text-transform:uppercase;
    }
    .status-pending { background:#fff7d6; color:#9a6100; }
    .status-confirmed, .status-processing { background:#dbeafe; color:#1d4ed8; }
    .status-dispatched { background:#e0f2fe; color:#0369a1; }
    .status-delivered, .status-paid { background:#dcfce7; color:#166534; }
    .status-cancelled, .status-refunded { background:#fee2e2; color:#991b1b; }
    .summary-strip {
        display:grid;
        grid-template-columns:repeat(4,1fr);
        border-bottom:1px solid #e2e8f0;
    }
    .summary-box {
        padding:18px 22px;
        border-right:1px solid #edf2f7;
    }
    .summary-box:last-child { border-right:none; }
    .summary-label { color:#94a3b8; font-size:11px; text-transform:uppercase; font-weight:900; margin-bottom:6px; }
    .summary-value { color:#0f172a; font-size:16px; font-weight:900; }
    .timeline-section, .order-section { padding:26px 28px; border-bottom:1px solid #edf2f7; }
    .section-heading {
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:18px;
    }
    .section-heading i {
        width:36px;
        height:36px;
        border-radius:8px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        background:color-mix(in srgb, var(--theme-color) 12%, white);
        color:var(--theme-color);
    }
    .section-heading h4 { margin:0; color:#0f172a; font-size:17px; font-weight:900; }
    .order-timeline {
        display:grid;
        grid-template-columns:repeat(5,1fr);
        gap:14px;
    }
    .timeline-step {
        position:relative;
        padding:16px;
        border:1px solid #e8eef6;
        border-radius:8px;
        background:#fbfdff;
    }
    .timeline-step.done {
        background:#f0fdf4;
        border-color:#bbf7d0;
    }
    .timeline-icon {
        width:34px;
        height:34px;
        border-radius:50%;
        display:flex;
        align-items:center;
        justify-content:center;
        background:#e2e8f0;
        color:#64748b;
        margin-bottom:10px;
    }
    .timeline-step.done .timeline-icon { background:#16a34a; color:#fff; }
    .timeline-step h6 { margin:0 0 4px; font-weight:900; color:#0f172a; }
    .timeline-step p { margin:0; color:#64748b; font-size:12px; }
    .address-grid {
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:18px;
    }
    .address-card, .note-card {
        border:1px solid #e8eef6;
        border-radius:8px;
        background:#fbfdff;
        padding:18px;
    }
    .address-card h5, .note-card h5 { margin:0 0 10px; color:#0f172a; font-weight:900; }
    .address-card p, .note-card p { margin:4px 0; color:#475569; line-height:1.55; }
    .items-table-wrap {
        overflow-x:auto;
        border:1px solid #e2e8f0;
        border-radius:8px;
    }
    .items-table { width:100%; min-width:840px; border-collapse:collapse; }
    .items-table th {
        background:#f8fafc;
        padding:13px 14px;
        font-size:11px;
        color:#64748b;
        font-weight:900;
        text-transform:uppercase;
        border-bottom:1px solid #e2e8f0;
    }
    .items-table td {
        padding:15px 14px;
        color:#334155;
        border-bottom:1px solid #edf2f7;
        vertical-align:top;
    }
    .items-table tbody tr:last-child td { border-bottom:none; }
    .product-name { font-weight:900; color:#0f172a; margin-bottom:4px; }
    .product-meta { color:#64748b; font-size:12px; }
    .text-right { text-align:right; }
    .detail-bottom {
        display:grid;
        grid-template-columns:1fr 380px;
        gap:24px;
        align-items:start;
    }
    .total-card {
        border:1px solid #e2e8f0;
        border-radius:8px;
        overflow:hidden;
    }
    .total-row {
        display:flex;
        justify-content:space-between;
        gap:16px;
        padding:12px 16px;
        border-bottom:1px solid #edf2f7;
        color:#475569;
    }
    .total-row strong { color:#0f172a; }
    .total-row.grand {
        background:#0f172a;
        color:#fff;
        font-weight:900;
        font-size:17px;
        border-bottom:none;
    }
    .total-row.grand strong { color:#fff; }
    @media (max-width:991px) {
        .order-hero, .address-grid, .detail-bottom { grid-template-columns:1fr; }
        .hero-actions { justify-content:flex-start; }
        .summary-strip, .order-timeline { grid-template-columns:1fr 1fr; }
    }
    @media (max-width:575px) {
        .summary-strip, .order-timeline { grid-template-columns:1fr; }
        .order-hero, .timeline-section, .order-section { padding:20px; }
        .order-hero h1 { font-size:24px; }
    }
    @media print {
        header, footer, .site-breadcrumb, .back-orders, .hero-actions, #scroll-top { display:none !important; }
        .order-detail-page { background:#fff; }
        .order-shell { box-shadow:none; border:none; }
    }
</style>

<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg" style="background: url({{ asset('assets/img/breadcrumb/01.html') }})"></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">Order Details</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home') }}"><i class="fas fa-house"></i> Home</a></li>
                <li><a href="{{ route('orders.index') }}">My Orders</a></li>
                <li class="active">{{ $order->order_number }}</li>
            </ul>
        </div>
    </div>
</div>

<div class="order-detail-page py-100">
    <div class="container">
        <div class="order-shell">
            <div class="order-hero">
                <div>
                    <a class="back-orders" href="{{ route('orders.index') }}"><i class="fas fa-arrow-left"></i> Back to orders</a>
                    <h1>Order {{ $order->order_number }}</h1>
                    <p class="order-meta">Placed on {{ optional($order->created_at)->format('d M Y, h:i A') }}</p>
                </div>
                <div class="hero-actions">
                    <span class="status-pill status-{{ $status }}"><i class="fas fa-circle"></i> {{ ucfirst($status) }}</span>
                    <span class="status-pill status-{{ $paymentStatus }}"><i class="fas fa-credit-card"></i> {{ ucfirst($paymentStatus) }}</span>
                    <button type="button" class="outline-btn" onclick="window.print()"><i class="fas fa-print"></i> Print</button>
                </div>
            </div>

            <div class="summary-strip">
                <div class="summary-box">
                    <div class="summary-label">Grand Total</div>
                    <div class="summary-value">Rs. {{ number_format($order->total ?? 0, 2) }}</div>
                </div>
                <div class="summary-box">
                    <div class="summary-label">Payment Method</div>
                    <div class="summary-value">{{ $order->payment_method ? ucwords(str_replace('_', ' ', $order->payment_method)) : '-' }}</div>
                </div>
                <div class="summary-box">
                    <div class="summary-label">Dispatch Mode</div>
                    <div class="summary-value">{{ $order->dispatch_mode ? ucwords(str_replace('_', ' ', $order->dispatch_mode)) : '-' }}</div>
                </div>
                <div class="summary-box">
                    <div class="summary-label">Tracking Number</div>
                    <div class="summary-value">{{ $order->tracking_number ?? '-' }}</div>
                </div>
            </div>

            <div class="timeline-section">
                <div class="section-heading">
                    <i class="fas fa-route"></i>
                    <h4>Order Progress</h4>
                </div>
                <div class="order-timeline">
                    @foreach($steps as $stepStatus => $label)
                        @php
                            $stepPosition = array_search($stepStatus, array_keys($steps), true);
                            $done = $status !== 'cancelled' && $stepPosition <= $statusIndex;
                        @endphp
                        <div class="timeline-step {{ $done ? 'done' : '' }}">
                            <div class="timeline-icon"><i class="fas {{ $done ? 'fa-check' : 'fa-circle' }}"></i></div>
                            <h6>{{ $label }}</h6>
                            <p>
                                @if($stepStatus === 'dispatched' && $order->dispatched_at)
                                    {{ $order->dispatched_at->format('d M Y') }}
                                @elseif($stepStatus === 'delivered' && $order->delivered_at)
                                    {{ $order->delivered_at->format('d M Y') }}
                                @elseif($done)
                                    Completed
                                @else
                                    Pending
                                @endif
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="order-section">
                <div class="section-heading">
                    <i class="fas fa-location-dot"></i>
                    <h4>Billing & Shipping</h4>
                </div>
                <div class="address-grid">
                    <div class="address-card">
                        <h5>Billing Address</h5>
                        <p><strong>{{ $billing['name'] ?? trim(($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? '')) ?: '-' }}</strong></p>
                        <p>{{ $billing['email'] ?? '-' }}</p>
                        <p>{{ $billing['phone'] ?? '-' }}</p>
                        <p>{{ $billing['address_1'] ?? '-' }}</p>
                        @if(!empty($billing['address_2']))<p>{{ $billing['address_2'] }}</p>@endif
                        <p>{{ $billing['city'] ?? '-' }}, {{ $billing['state'] ?? '-' }} {{ $billing['postcode'] ?? '' }}</p>
                        <p>{{ $billing['country'] ?? '-' }}</p>
                    </div>
                    <div class="address-card">
                        <h5>Shipping Address</h5>
                        <p><strong>{{ $shipping['name'] ?? trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '')) ?: '-' }}</strong></p>
                        <p>{{ $shipping['email'] ?? '-' }}</p>
                        <p>{{ $shipping['phone'] ?? '-' }}</p>
                        <p>{{ $shipping['address_1'] ?? '-' }}</p>
                        @if(!empty($shipping['address_2']))<p>{{ $shipping['address_2'] }}</p>@endif
                        <p>{{ $shipping['city'] ?? '-' }}, {{ $shipping['state'] ?? '-' }} {{ $shipping['postcode'] ?? '' }}</p>
                        <p>{{ $shipping['country'] ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <div class="order-section">
                <div class="section-heading">
                    <i class="fas fa-pills"></i>
                    <h4>Order Items</h4>
                </div>
                <div class="items-table-wrap">
                    <table class="items-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Qty</th>
                                <th class="text-right">GST</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="product-name">{{ $item->product_name }}</div>
                                        @if($item->variant_name)<div class="product-meta">{{ $item->variant_name }}</div>@endif
                                        @if($item->hsn_code)<div class="product-meta">HSN: {{ $item->hsn_code }}</div>@endif
                                    </td>
                                    <td>{{ $item->sku ?? '-' }}</td>
                                    <td class="text-right">Rs. {{ number_format($item->unit_price ?? 0, 2) }}</td>
                                    <td class="text-right">{{ $item->qty ?? 0 }}</td>
                                    <td class="text-right">{{ number_format($item->gst_rate ?? 0, 2) }}%<div class="product-meta">Rs. {{ number_format($item->gst_amount ?? 0, 2) }}</div></td>
                                    <td class="text-right"><strong>Rs. {{ number_format($item->total ?? 0, 2) }}</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align:center; padding:22px; color:#64748b;">No items found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="order-section">
                <div class="detail-bottom">
                    <div class="note-card">
                        <h5>Customer Notes</h5>
                        <p>{{ $order->notes ?: 'No notes added with this order.' }}</p>

                        <h5 style="margin-top:18px;">Payment Details</h5>
                        <p>Invoice: <strong>{{ $order->invoice_number ?? '-' }}</strong></p>
                        <p>Invoice Date: <strong>{{ $order->invoice_date ? $order->invoice_date->format('d M Y') : '-' }}</strong></p>
                        <p>Amount Paid: <strong>Rs. {{ number_format($amountPaid, 2) }}</strong></p>
                        <p>Balance Due: <strong>Rs. {{ number_format($balanceDue, 2) }}</strong></p>
                    </div>

                    <div class="total-card">
                        <div class="total-row"><span>Subtotal</span><strong>Rs. {{ number_format($order->subtotal ?? 0, 2) }}</strong></div>
                        <div class="total-row"><span>Discount</span><strong>- Rs. {{ number_format($order->discount_amount ?? 0, 2) }}</strong></div>
                        <div class="total-row"><span>Shipping</span><strong>{{ ($order->shipping_charge ?? 0) > 0 ? 'Rs. ' . number_format($order->shipping_charge, 2) : 'Free' }}</strong></div>
                        <div class="total-row"><span>Total GST</span><strong>Rs. {{ number_format($order->total_gst ?? 0, 2) }}</strong></div>
                        <div class="total-row"><span>CGST</span><strong>Rs. {{ number_format($order->cgst ?? 0, 2) }}</strong></div>
                        <div class="total-row"><span>SGST</span><strong>Rs. {{ number_format($order->sgst ?? 0, 2) }}</strong></div>
                        <div class="total-row"><span>IGST</span><strong>Rs. {{ number_format($order->igst ?? 0, 2) }}</strong></div>
                        <div class="total-row"><span>Balance Due</span><strong>Rs. {{ number_format($balanceDue, 2) }}</strong></div>
                        <div class="total-row grand"><span>Grand Total</span><strong>Rs. {{ number_format($order->total ?? 0, 2) }}</strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
