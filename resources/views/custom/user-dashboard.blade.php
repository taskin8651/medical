@extends('custom.master')

@section('title', 'My Dashboard')

@section('content')
@php
    $approval = $user->approval_status ?? 'pending';
    $approvalColors = [
        'approved' => ['bg' => '#dcfce7', 'text' => '#166534', 'icon' => 'fa-check-circle'],
        'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => 'fa-times-circle'],
        'pending' => ['bg' => '#fef9c3', 'text' => '#92400e', 'icon' => 'fa-clock'],
    ];
    $approvalStyle = $approvalColors[$approval] ?? $approvalColors['pending'];
@endphp

<style>
    .user-dash-page { background:#f8fafc; }
    .dash-card {
        background:#fff;
        border:1px solid #e2e8f0;
        border-radius:8px;
        box-shadow:0 14px 34px rgba(15,23,42,.05);
    }
    .dash-hero {
        display:grid;
        grid-template-columns:1.1fr .9fr;
        gap:22px;
        align-items:stretch;
        margin-bottom:24px;
    }
    .profile-card { padding:28px; }
    .profile-head {
        display:flex;
        align-items:center;
        gap:16px;
        margin-bottom:22px;
    }
    .profile-avatar {
        width:70px;
        height:70px;
        border-radius:8px;
        background:var(--theme-color);
        color:#fff;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:28px;
        font-weight:900;
        flex-shrink:0;
    }
    .profile-name { margin:0 0 5px; color:#0f172a; font-weight:900; font-size:24px; }
    .profile-email { margin:0; color:#64748b; }
    .approval-pill {
        display:inline-flex;
        align-items:center;
        gap:7px;
        padding:7px 12px;
        border-radius:999px;
        font-size:12px;
        font-weight:900;
        text-transform:uppercase;
        margin-top:10px;
    }
    .detail-grid {
        display:grid;
        grid-template-columns:repeat(2,1fr);
        gap:14px;
    }
    .detail-box {
        border:1px solid #e8eef6;
        border-radius:8px;
        background:#fbfdff;
        padding:14px;
    }
    .detail-label {
        font-size:11px;
        color:#94a3b8;
        font-weight:900;
        text-transform:uppercase;
        margin-bottom:6px;
    }
    .detail-value {
        color:#0f172a;
        font-weight:800;
        word-break:break-word;
    }
    .summary-grid {
        display:grid;
        grid-template-columns:repeat(2,1fr);
        gap:14px;
        height:100%;
    }
    .summary-card {
        padding:22px;
        display:flex;
        flex-direction:column;
        justify-content:space-between;
    }
    .summary-card i {
        width:38px;
        height:38px;
        display:flex;
        align-items:center;
        justify-content:center;
        border-radius:8px;
        background:color-mix(in srgb, var(--theme-color) 12%, white);
        color:var(--theme-color);
        margin-bottom:16px;
    }
    .summary-card p { margin:0; color:#64748b; font-size:13px; font-weight:700; }
    .summary-card h3 { margin:6px 0 0; color:#0f172a; font-size:26px; font-weight:900; }
    .section-card { margin-top:24px; overflow:hidden; }
    .section-header {
        padding:18px 22px;
        border-bottom:1px solid #edf2f7;
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
    }
    .section-header h4 { margin:0; color:#0f172a; font-weight:900; font-size:18px; }
    .business-grid {
        display:grid;
        grid-template-columns:repeat(3,1fr);
        gap:14px;
        padding:22px;
    }
    .order-row {
        display:grid;
        grid-template-columns:1fr .7fr .7fr .8fr auto;
        gap:16px;
        align-items:center;
        padding:18px 22px;
        border-bottom:1px solid #edf2f7;
    }
    .order-row:last-child { border-bottom:none; }
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
    .empty-state { padding:30px 22px; color:#64748b; }
    @media (max-width:991px) {
        .dash-hero, .business-grid { grid-template-columns:1fr; }
        .order-row { grid-template-columns:1fr; }
    }
    @media (max-width:575px) {
        .detail-grid, .summary-grid { grid-template-columns:1fr; }
        .profile-head { align-items:flex-start; flex-direction:column; }
    }
</style>

<div class="site-breadcrumb">
    <div class="site-breadcrumb-bg"><i class="fas fa-briefcase-medical"></i></div>
    <div class="container">
        <div class="site-breadcrumb-wrap">
            <h4 class="breadcrumb-title">My Dashboard</h4>
            <ul class="breadcrumb-menu">
                <li><a href="{{ route('home') }}"><i class="fas fa-house"></i> Home</a></li>
                <li class="active"><i class="fas fa-chevron-right"></i> My Dashboard</li>
            </ul>
        </div>
    </div>
</div>

<div class="user-dash-page py-100">
    <div class="container">
        <div class="dash-hero">
            <div class="dash-card profile-card">
                <div class="profile-head">
                    <div class="profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div>
                        <h1 class="profile-name">{{ $user->name }}</h1>
                        <p class="profile-email">{{ $user->email }}</p>
                        <span class="approval-pill" style="background:{{ $approvalStyle['bg'] }}; color:{{ $approvalStyle['text'] }};">
                            <i class="fas {{ $approvalStyle['icon'] }}"></i> {{ ucfirst($approval) }}
                        </span>
                    </div>
                </div>

                <div class="detail-grid">
                    <div class="detail-box">
                        <div class="detail-label">Phone</div>
                        <div class="detail-value">{{ $user->phone ?: '-' }}</div>
                    </div>
                    <div class="detail-box">
                        <div class="detail-label">Member Since</div>
                        <div class="detail-value">{{ optional($user->created_at)->format('d M Y') ?? '-' }}</div>
                    </div>
                    <div class="detail-box">
                        <div class="detail-label">Email Status</div>
                        <div class="detail-value">{{ $user->email_verified_at ? 'Verified' : 'Pending' }}</div>
                    </div>
                    <div class="detail-box">
                        <div class="detail-label">Account Type</div>
                        <div class="detail-value">Wholesale Buyer</div>
                    </div>
                </div>
            </div>

            <div class="summary-grid">
                <div class="dash-card summary-card">
                    <div><i class="fas fa-receipt"></i><p>Total Orders</p><h3>{{ $summary['total_orders'] }}</h3></div>
                </div>
                <div class="dash-card summary-card">
                    <div><i class="fas fa-clock"></i><p>Active Orders</p><h3>{{ $summary['pending_orders'] }}</h3></div>
                </div>
                <div class="dash-card summary-card">
                    <div><i class="fas fa-truck-fast"></i><p>Delivered</p><h3>{{ $summary['delivered_orders'] }}</h3></div>
                </div>
                <div class="dash-card summary-card">
                    <div><i class="fas fa-wallet"></i><p>Total Spent</p><h3>Rs. {{ number_format($summary['total_spent'], 2) }}</h3></div>
                </div>
            </div>
        </div>

        <div class="dash-card section-card">
            <div class="section-header">
                <h4>Business Details</h4>
            </div>
            <div class="business-grid">
                <div class="detail-box"><div class="detail-label">Business Name</div><div class="detail-value">{{ $user->business_name ?: '-' }}</div></div>
                <div class="detail-box"><div class="detail-label">Business Type</div><div class="detail-value">{{ $user->business_type ?: '-' }}</div></div>
                <div class="detail-box"><div class="detail-label">GST Number</div><div class="detail-value">{{ $user->gst_no ?: '-' }}</div></div>
                <div class="detail-box"><div class="detail-label">Drug License</div><div class="detail-value">{{ $user->drug_license_no ?: '-' }}</div></div>
                <div class="detail-box"><div class="detail-label">City / State</div><div class="detail-value">{{ collect([$user->city, $user->state, $user->pincode])->filter()->implode(', ') ?: '-' }}</div></div>
                <div class="detail-box"><div class="detail-label">Country</div><div class="detail-value">{{ $user->country ?: '-' }}</div></div>
                <div class="detail-box" style="grid-column:1 / -1;"><div class="detail-label">Address</div><div class="detail-value">{{ $user->address ?: '-' }}</div></div>
            </div>
        </div>

        <div class="dash-card section-card">
            <div class="section-header">
                <h4>Recent Orders</h4>
                <a href="{{ route('orders.index') }}" class="theme-btn">View All <i class="fas fa-arrow-right"></i></a>
            </div>

            @forelse($orders as $order)
                <div class="order-row">
                    <div>
                        <div class="detail-label">Order Number</div>
                        <div class="detail-value">{{ $order->order_number }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Date</div>
                        <div class="detail-value">{{ optional($order->created_at)->format('d M Y') }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Total</div>
                        <div class="detail-value">Rs. {{ number_format($order->total ?? 0, 2) }}</div>
                    </div>
                    <div>
                        <div class="detail-label">Status</div>
                        <span class="status-pill status-{{ $order->status }}">{{ ucfirst($order->status ?? 'pending') }}</span>
                    </div>
                    <div style="display:flex;gap:8px;flex-wrap:wrap;">
                        <a href="{{ route('orders.show', $order->order_number) }}" class="theme-btn">View</a>
                        <a href="{{ route('orders.bill', $order->order_number) }}" class="theme-btn">Bill</a>
                    </div>
                </div>
            @empty
                <div class="empty-state">No orders yet. Your recent orders will appear here.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
