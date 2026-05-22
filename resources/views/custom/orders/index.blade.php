@extends('custom.master')

@section('title', 'My Orders')

@section('content')
<style>
    .order-track-wrap { background:#f8fafc; }
    .track-card, .orders-panel {
        background:#fff;
        border:1px solid #e2e8f0;
        border-radius:8px;
        box-shadow:0 12px 30px rgba(15,23,42,.05);
    }
    .track-card { padding:30px; }
    .track-eyebrow {
        display:inline-flex;
        align-items:center;
        gap:8px;
        color:var(--theme-color);
        font-weight:800;
        font-size:13px;
        margin-bottom:10px;
    }
    .track-title { font-size:32px; font-weight:900; color:#0f172a; margin:0 0 8px; }
    .track-copy { color:#64748b; margin-bottom:24px; line-height:1.65; }
    .track-form .form-control {
        height:52px;
        border:1px solid #dbe3ef;
        border-radius:7px;
        padding:12px 14px;
    }
    .track-form textarea.form-control { height:auto; }
    .track-info {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:14px;
        margin-top:24px;
    }
    .track-info-item {
        border:1px solid #e8eef6;
        border-radius:8px;
        padding:16px;
        background:#fbfdff;
    }
    .track-info-item i {
        color:var(--theme-color);
        font-size:20px;
        margin-bottom:10px;
    }
    .track-info-item h6 {
        margin:0 0 5px;
        font-size:14px;
        font-weight:900;
        color:#0f172a;
    }
    .track-info-item p { margin:0; color:#64748b; font-size:13px; line-height:1.5; }
    .orders-panel { margin-top:36px; overflow:hidden; }
    .orders-panel-header {
        padding:18px 22px;
        border-bottom:1px solid #edf2f7;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:14px;
        flex-wrap:wrap;
    }
    .orders-panel-header h4 { margin:0; font-size:18px; font-weight:900; color:#0f172a; }
    .customer-order-row {
        display:grid;
        grid-template-columns:1.1fr .9fr .8fr .8fr auto;
        gap:16px;
        align-items:center;
        padding:18px 22px;
        border-bottom:1px solid #edf2f7;
    }
    .customer-order-row:last-child { border-bottom:none; }
    .row-label { font-size:11px; color:#94a3b8; font-weight:800; text-transform:uppercase; margin-bottom:4px; }
    .row-value { color:#0f172a; font-weight:800; }
    .status-pill {
        display:inline-flex;
        align-items:center;
        padding:5px 10px;
        border-radius:999px;
        font-size:11px;
        font-weight:900;
        text-transform:uppercase;
    }
    .status-pending { background:#fff7d6; color:#9a6100; }
    .status-confirmed, .status-processing { background:#dbeafe; color:#1d4ed8; }
    .status-dispatched { background:#e0f2fe; color:#0369a1; }
    .status-delivered, .status-paid { background:#dcfce7; color:#166534; }
    .status-cancelled, .status-refunded { background:#fee2e2; color:#991b1b; }
    .empty-orders { padding:26px 22px; color:#64748b; }
    @media (max-width:991px) {
        .track-info { grid-template-columns:1fr; }
        .customer-order-row { grid-template-columns:1fr; }
        .track-title { font-size:26px; }
    }
</style>

<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg"><i class="fas fa-briefcase-medical"></i></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">My Orders</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home') }}"><i class="fas fa-house"></i> Home</a></li>
                <li class="active">My Orders</li>
            </ul>
        </div>
    </div>
</div>

<div class="order-track-wrap py-100">
    <div class="container">
        <div class="track-card">
            <span class="track-eyebrow"><i class="fas fa-truck-fast"></i> Track your order</span>
            <h1 class="track-title">View order status and invoice details</h1>
            <p class="track-copy">Enter your order number and the email or phone used during checkout. This keeps order details private while still making tracking quick for customers.</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">Please check the order details and try again.</div>
            @endif

            <form class="track-form" action="{{ route('orders.track') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-lg-5">
                        <input type="text" name="order_number" class="form-control @error('order_number') is-invalid @enderror" value="{{ old('order_number') }}" placeholder="Order number, e.g. ORD-2026-00001" required>
                        @error('order_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-lg-5">
                        <input type="text" name="identifier" class="form-control @error('identifier') is-invalid @enderror" value="{{ old('identifier') }}" placeholder="Billing email or phone" required>
                        @error('identifier')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-lg-2 d-grid">
                        <button type="submit" class="theme-btn">Track <i class="fas fa-arrow-right"></i></button>
                    </div>
                </div>
            </form>

            <div class="track-info">
                <div class="track-info-item">
                    <i class="fas fa-receipt"></i>
                    <h6>Invoice ready</h6>
                    <p>See order items, taxes, payment status and payable balance in one place.</p>
                </div>
                <div class="track-info-item">
                    <i class="fas fa-boxes-packing"></i>
                    <h6>Dispatch tracking</h6>
                    <p>Check order status, dispatch mode, tracking number and delivery updates.</p>
                </div>
                <div class="track-info-item">
                    <i class="fas fa-shield-halved"></i>
                    <h6>Private access</h6>
                    <p>Order details open only after matching the checkout email or phone number.</p>
                </div>
            </div>
        </div>

        @if($orders)
            <div class="orders-panel">
                <div class="orders-panel-header">
                    <h4>Recent Orders</h4>
                    <span>{{ $orders->total() }} total</span>
                </div>

                @forelse($orders as $order)
                    <div class="customer-order-row">
                        <div>
                            <div class="row-label">Order Number</div>
                            <div class="row-value">{{ $order->order_number }}</div>
                        </div>
                        <div>
                            <div class="row-label">Order Date</div>
                            <div class="row-value">{{ optional($order->created_at)->format('d M Y') }}</div>
                        </div>
                        <div>
                            <div class="row-label">Total</div>
                            <div class="row-value">Rs. {{ number_format($order->total ?? 0, 2) }}</div>
                        </div>
                        <div>
                            <div class="row-label">Status</div>
                            <span class="status-pill status-{{ $order->status }}">{{ ucfirst($order->status ?? 'pending') }}</span>
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;">
                            <a href="{{ route('orders.show', $order->order_number) }}" class="theme-btn">View</a>
                            <a href="{{ route('orders.bill', $order->order_number) }}" class="theme-btn">Bill</a>
                        </div>
                    </div>
                @empty
                    <div class="empty-orders">No orders found yet.</div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
