@extends('layouts.admin')
@section('page-title', 'Order Details')

@section('styles')
<style>
.detail-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
}
.detail-header {
    padding: 16px 22px;
    border-bottom: 1px solid #F1F5F9;
    display: flex; align-items: center; gap: 10px;
}
.detail-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--accent-light); color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.detail-body { padding: 22px; }
.detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.detail-item { margin-bottom: 16px; }
.detail-label { font-size: 12px; font-weight: 700; color: #94A3B8; text-transform: uppercase; margin-bottom: 4px; }
.detail-value { font-size: 14px; color: #0F172A; font-weight: 600; }
.status-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .02em;
}
.status-pending { background: #FEF3C7; color: #92400E; }
.status-processing { background: #DBEAFE; color: #1E40AF; }
.status-completed { background: #D1FAE5; color: #065F46; }
.status-cancelled { background: #FEE2E2; color: #991B1B; }
.btn-outline {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 8px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    border: 1.5px solid; cursor: pointer; transition: background .15s;
}
.btn-outline:hover { background: #F8FAFC; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <a href="{{ route('admin.orders.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">← Back to orders</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Order #{{ $order->id }}</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Order details and information</p>
    </div>
    <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:#fff; border:1px solid #E2E8F0; border-radius:12px;">
        <div style="width:38px; height:38px; border-radius:12px; background:#E0E7FF; color:#3730A3; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">#{{ $order->id }}</div>
        <div>
            <p style="font-size:14px; font-weight:700; margin:0; color:#0F172A;">Order #{{ $order->id }}</p>
            <p style="font-size:12px; color:#64748B; margin:0;">{{ $order->created_at->format('d M Y') }}</p>
        </div>
    </div>
</div>

<div class="detail-grid">
    <div class="detail-card">
        <div class="detail-header">
            <div class="detail-icon"><i class="fas fa-shopping-cart"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Order Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Basic order details</p>
            </div>
        </div>
        <div class="detail-body">
            <div class="detail-item">
                <div class="detail-label">Order ID</div>
                <div class="detail-value">#{{ $order->id }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Customer</div>
                <div class="detail-value">{{ $order->user->name ?? 'N/A' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Total Amount</div>
                <div class="detail-value">${{ number_format($order->total_amount, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Created At</div>
                <div class="detail-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Updated At</div>
                <div class="detail-value">{{ $order->updated_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-header">
            <div class="detail-icon"><i class="fas fa-list"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Order Items</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Products in this order</p>
            </div>
        </div>
        <div class="detail-body">
            @forelse($order->orderItems as $item)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #F1F5F9;">
                <div>
                    <p style="font-size:14px; font-weight:600; color:#0F172A; margin:0;">{{ $item->product->name ?? 'N/A' }}</p>
                    <p style="font-size:12px; color:#64748B; margin:2px 0 0;">Qty: {{ $item->quantity }}</p>
                </div>
                <div style="text-align:right;">
                    <p style="font-size:14px; font-weight:600; color:#0F172A; margin:0;">${{ number_format($item->price, 2) }}</p>
                    <p style="font-size:12px; color:#64748B; margin:2px 0 0;">${{ number_format($item->price * $item->quantity, 2) }}</p>
                </div>
            </div>
            @empty
            <p style="font-size:14px; color:#64748B; margin:0;">No items found.</p>
            @endforelse
        </div>
    </div>
</div>

<div style="display:flex; justify-content:flex-end; gap:12px; margin-top:18px;">
    @can('order_edit')
    <a href="{{ route('admin.orders.edit', $order) }}" class="btn-outline" style="border-color:color-mix(in srgb, var(--accent) 40%, transparent); color:var(--accent);">Edit Order</a>
    @endcan
</div>

@endsection