@extends('layouts.admin')
@section('page-title', 'Billing Dashboard')

@section('styles')
<style>
.dash-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }
.dash-card { background:#fff; border:1px solid #E2E8F0; border-radius:14px; padding:18px; }
.dash-card-icon { width:42px; height:42px; border-radius:11px; display:flex; align-items:center; justify-content:center; background:var(--accent-light); color:var(--accent); font-size:17px; }
.dash-label { font-size:11px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:.06em; margin:0 0 8px; }
.dash-value { font-size:25px; font-weight:800; color:#0F172A; margin:0; line-height:1; }
.dash-sub { font-size:12px; color:#64748B; margin:8px 0 0; }
.billing-panel { background:#fff; border:1px solid #E2E8F0; border-radius:14px; overflow:hidden; }
.panel-head { padding:16px 20px; border-bottom:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
.panel-body { padding:18px 20px; }
.dash-table { width:100%; border-collapse:collapse; }
.dash-table th { background:#F8FAFC; color:#64748B; font-size:11px; text-transform:uppercase; letter-spacing:.06em; padding:12px 14px; text-align:left; }
.dash-table td { padding:13px 14px; border-bottom:1px solid #F1F5F9; font-size:13px; color:#334155; vertical-align:middle; }
.dash-table tr:last-child td { border-bottom:0; }
.status-pill { display:inline-flex; align-items:center; gap:6px; padding:5px 10px; border-radius:999px; font-size:11px; font-weight:800; text-transform:capitalize; }
.status-pending { background:#FEF9C3; color:#92400E; }
.status-processing { background:#DBEAFE; color:#1D4ED8; }
.status-completed, .status-paid { background:#DCFCE7; color:#15803D; }
.status-cancelled, .status-overdue { background:#FEE2E2; color:#991B1B; }
.status-partial { background:#E0F2FE; color:#0369A1; }
.quick-action { display:flex; align-items:center; gap:12px; padding:14px; border:1px solid #E2E8F0; border-radius:12px; color:#0F172A; text-decoration:none; background:#fff; transition:background .15s, border-color .15s; }
.quick-action:hover { background:#F8FAFC; border-color:var(--accent); }
.progress-track { height:8px; border-radius:99px; background:#E2E8F0; overflow:hidden; }
.progress-fill { height:100%; background:var(--accent); border-radius:99px; }
@media(max-width:1200px){ .dash-grid{grid-template-columns:repeat(2,minmax(0,1fr));} }
@media(max-width:700px){ .dash-grid{grid-template-columns:1fr;} }
</style>
@endsection

@section('content')
@php
    $money = fn ($amount) => 'Rs. ' . number_format((float) $amount, 2);
    $statusMeta = [
        'pending' => ['Pending', 'status-pending'],
        'processing' => ['Processing', 'status-processing'],
        'completed' => ['Completed', 'status-completed'],
        'cancelled' => ['Cancelled', 'status-cancelled'],
        'paid' => ['Paid', 'status-paid'],
        'partial' => ['Partial', 'status-partial'],
        'failed' => ['Failed', 'status-cancelled'],
    ];
    $totalStatus = max(1, $statusCounts->sum());
@endphp

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:24px; font-weight:800; color:#0F172A; margin:0;">Billing Dashboard</h2>
        <p style="font-size:13px; color:#64748B; margin:5px 0 0;">Orders, payment dues, manual billing and stock alerts in one place.</p>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        <a href="{{ route('admin.orders.manualBilling') }}" class="btn-primary">
            <i class="fas fa-file-invoice"></i> New Manual Bill
        </a>
        <a href="{{ route('admin.orders.index') }}" class="btn-ghost">
            <i class="fas fa-list"></i> All Orders
        </a>
    </div>
</div>

<div class="dash-grid" style="margin-bottom:20px;">
    <div class="dash-card">
        <div style="display:flex; justify-content:space-between; gap:12px;">
            <div>
                <p class="dash-label">Today Billing</p>
                <p class="dash-value">{{ $money($summary['today_billing']) }}</p>
                <p class="dash-sub">{{ $summary['today_orders'] }} orders today</p>
            </div>
            <div class="dash-card-icon"><i class="fas fa-receipt"></i></div>
        </div>
    </div>
    <div class="dash-card">
        <div style="display:flex; justify-content:space-between; gap:12px;">
            <div>
                <p class="dash-label">This Month</p>
                <p class="dash-value">{{ $money($summary['month_billing']) }}</p>
                <p class="dash-sub">{{ $summary['month_orders'] }} orders this month</p>
            </div>
            <div class="dash-card-icon"><i class="fas fa-chart-line"></i></div>
        </div>
    </div>
    <div class="dash-card">
        <div style="display:flex; justify-content:space-between; gap:12px;">
            <div>
                <p class="dash-label">Payment Due</p>
                <p class="dash-value">{{ $money($summary['due_amount']) }}</p>
                <p class="dash-sub">{{ $summary['payment_pending'] }} unpaid / partial orders</p>
            </div>
            <div class="dash-card-icon"><i class="fas fa-wallet"></i></div>
        </div>
    </div>
    <div class="dash-card">
        <div style="display:flex; justify-content:space-between; gap:12px;">
            <div>
                <p class="dash-label">Overdue</p>
                <p class="dash-value">{{ $money($summary['overdue_amount']) }}</p>
                <p class="dash-sub">{{ $summary['overdue_count'] }} overdue bills</p>
            </div>
            <div class="dash-card-icon"><i class="fas fa-triangle-exclamation"></i></div>
        </div>
    </div>
</div>

<div class="dash-grid" style="margin-bottom:24px;">
    <div class="dash-card">
        <p class="dash-label">Order Status</p>
        @foreach(['pending', 'processing', 'completed', 'cancelled'] as $status)
            @php $count = (int) ($statusCounts[$status] ?? 0); @endphp
            <div style="margin-top:12px;">
                <div style="display:flex; justify-content:space-between; font-size:13px; color:#334155; margin-bottom:6px;">
                    <span>{{ ucfirst($status) }}</span><strong>{{ $count }}</strong>
                </div>
                <div class="progress-track"><div class="progress-fill" style="width:{{ round(($count / $totalStatus) * 100) }}%"></div></div>
            </div>
        @endforeach
    </div>

    <div class="dash-card">
        <p class="dash-label">Payment Status</p>
        @forelse($paymentCounts as $status => $count)
            @php $meta = $statusMeta[$status] ?? [ucfirst($status ?: 'Unknown'), 'status-pending']; @endphp
            <div style="display:flex; justify-content:space-between; align-items:center; padding:9px 0; border-bottom:1px solid #F1F5F9;">
                <span class="status-pill {{ $meta[1] }}">{{ $meta[0] }}</span>
                <strong style="font-size:14px; color:#0F172A;">{{ $count }}</strong>
            </div>
        @empty
            <p class="dash-sub">No payment data yet.</p>
        @endforelse
    </div>

    <div class="dash-card">
        <p class="dash-label">Important Counts</p>
        <div style="display:grid; gap:12px;">
            <div style="display:flex; justify-content:space-between;"><span>Pending Orders</span><strong>{{ $summary['pending_orders'] }}</strong></div>
            <div style="display:flex; justify-content:space-between;"><span>Processing</span><strong>{{ $summary['processing_orders'] }}</strong></div>
            <div style="display:flex; justify-content:space-between;"><span>Low Stock Products</span><strong>{{ $summary['low_stock_products'] }}</strong></div>
            <div style="display:flex; justify-content:space-between;"><span>Approved Buyers</span><strong>{{ $summary['approved_buyers'] }}</strong></div>
        </div>
    </div>

    <div class="dash-card">
        <p class="dash-label">Quick Billing</p>
        <div style="display:grid; gap:10px;">
            <a href="{{ route('admin.orders.manualBilling') }}" class="quick-action">
                <div class="dash-card-icon"><i class="fas fa-cash-register"></i></div>
                <div><strong>Counter Bill</strong><p class="dash-sub" style="margin:2px 0 0;">Create instant manual bill</p></div>
            </a>
            <a href="{{ route('admin.products.create') }}" class="quick-action">
                <div class="dash-card-icon"><i class="fas fa-pills"></i></div>
                <div><strong>Add Product</strong><p class="dash-sub" style="margin:2px 0 0;">Add stock item for billing</p></div>
            </a>
        </div>
    </div>
</div>

<div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; align-items:start;">
    <div class="billing-panel">
        <div class="panel-head">
            <div>
                <p style="font-size:15px; font-weight:800; color:#0F172A; margin:0;">Recent Orders</p>
                <p style="font-size:12px; color:#94A3B8; margin:2px 0 0;">Latest billing and order activity</p>
            </div>
            <a href="{{ route('admin.orders.index') }}" class="btn-ghost">View All</a>
        </div>
        <div style="overflow-x:auto;">
            <table class="dash-table">
                <thead>
                    <tr>
                        <th>Order</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        @php
                            $orderMeta = $statusMeta[$order->status] ?? [ucfirst($order->status ?: 'Pending'), 'status-pending'];
                            $paymentMeta = $statusMeta[$order->payment_status] ?? [ucfirst($order->payment_status ?: 'Pending'), 'status-pending'];
                        @endphp
                        <tr>
                            <td><strong>{{ $order->order_number }}</strong><br><span style="color:#94A3B8;">{{ optional($order->created_at)->format('d M Y') }}</span></td>
                            <td>{{ $order->billing_address['name'] ?? $order->user->name ?? 'Walk-in Customer' }}</td>
                            <td>{{ $order->items_count }}</td>
                            <td><span class="status-pill {{ $orderMeta[1] }}">{{ $orderMeta[0] }}</span></td>
                            <td><span class="status-pill {{ $paymentMeta[1] }}">{{ $paymentMeta[0] }}</span></td>
                            <td><strong>{{ $money($order->total) }}</strong></td>
                            <td style="text-align:right;">
                                <a href="{{ route('admin.orders.manualBill', $order) }}" class="action-btn"><i class="fas fa-file-invoice"></i> Bill</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" style="text-align:center; color:#64748B; padding:24px;">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="billing-panel">
        <div class="panel-head">
            <div>
                <p style="font-size:15px; font-weight:800; color:#0F172A; margin:0;">Pending Payments</p>
                <p style="font-size:12px; color:#94A3B8; margin:2px 0 0;">Bills with balance due</p>
            </div>
        </div>
        <div class="panel-body">
            @forelse($pendingPayments as $order)
                <div style="padding:12px 0; border-bottom:1px solid #F1F5F9;">
                    <div style="display:flex; justify-content:space-between; gap:10px;">
                        <div>
                            <strong style="font-size:13px; color:#0F172A;">{{ $order->order_number }}</strong>
                            <p class="dash-sub" style="margin:3px 0 0;">{{ $order->billing_address['name'] ?? $order->user->name ?? 'Walk-in Customer' }}</p>
                        </div>
                        <strong style="font-size:13px; color:#BE123C;">{{ $money($order->balance_due) }}</strong>
                    </div>
                    <div style="display:flex; gap:8px; margin-top:8px;">
                        <a href="{{ route('admin.orders.show', $order) }}" class="action-btn"><i class="fas fa-eye"></i> View</a>
                        <a href="{{ route('admin.orders.manualBill', $order) }}" class="action-btn"><i class="fas fa-print"></i> Bill</a>
                    </div>
                </div>
            @empty
                <p class="dash-sub">No pending payments. Nice and clean.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
