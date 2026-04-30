@extends('layouts.admin')

@section('page-title', 'Order Details')

@section('styles')
<style>
    .invoice-page {
        color: #0f172a;
    }

    .invoice-topbar {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
        flex-wrap: wrap;
    }

    .back-link {
        font-size: 13px;
        color: var(--accent);
        text-decoration: none;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 8px;
    }

    .page-title {
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        color: #0f172a;
    }

    .page-subtitle {
        font-size: 13px;
        color: #64748b;
        margin: 5px 0 0;
    }

    .invoice-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .invoice-btn {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 14px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        color: #334155;
        background: #fff;
        cursor: pointer;
    }

    .invoice-btn:hover {
        background: #f8fafc;
        color: #0f172a;
    }

    .invoice-btn.primary {
        background: var(--accent);
        border-color: var(--accent);
        color: #fff;
    }

    .invoice-wrapper {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 18px;
        overflow: hidden;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.04);
    }

    .invoice-header {
        padding: 28px;
        background:
            radial-gradient(circle at top right, rgba(59, 130, 246, .14), transparent 35%),
            linear-gradient(135deg, #f8fafc, #ffffff);
        border-bottom: 1px solid #e2e8f0;
        display: grid;
        grid-template-columns: 1.2fr .8fr;
        gap: 24px;
    }

    .brand-box h3 {
        margin: 0 0 8px;
        font-size: 22px;
        font-weight: 900;
        color: #0f172a;
    }

    .brand-box p {
        margin: 2px 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.55;
    }

    .invoice-meta {
        text-align: right;
    }

    .invoice-label {
        display: inline-flex;
        padding: 5px 10px;
        border-radius: 999px;
        background: #eef2ff;
        color: #3730a3;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        margin-bottom: 10px;
    }

    .invoice-number {
        font-size: 22px;
        font-weight: 900;
        color: #0f172a;
        margin: 0 0 8px;
    }

    .meta-line {
        font-size: 13px;
        color: #64748b;
        margin: 4px 0;
    }

    .meta-line strong {
        color: #0f172a;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .04em;
    }

    .status-pending { background: #fef3c7; color: #92400e; }
    .status-processing { background: #dbeafe; color: #1e40af; }
    .status-completed { background: #d1fae5; color: #065f46; }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-cancelled { background: #fee2e2; color: #991b1b; }
    .status-paid { background: #d1fae5; color: #065f46; }
    .status-unpaid { background: #fee2e2; color: #991b1b; }
    .status-failed { background: #fee2e2; color: #991b1b; }

    .invoice-section {
        padding: 24px 28px;
        border-bottom: 1px solid #f1f5f9;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 16px;
    }

    .section-title i {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: var(--accent-light);
        color: var(--accent);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
    }

    .section-title h4 {
        margin: 0;
        font-size: 15px;
        font-weight: 900;
        color: #0f172a;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
    }

    .info-box {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 14px;
    }

    .info-label {
        font-size: 11px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        word-break: break-word;
    }

    .address-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
    }

    .address-card {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 16px;
    }

    .address-card h5 {
        margin: 0 0 10px;
        font-size: 14px;
        font-weight: 900;
        color: #0f172a;
    }

    .address-card p {
        margin: 3px 0;
        font-size: 13px;
        color: #475569;
        line-height: 1.55;
    }

    .invoice-table-wrap {
        overflow-x: auto;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .invoice-table th {
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: .05em;
        padding: 13px 12px;
        border-bottom: 1px solid #e2e8f0;
        text-align: left;
        white-space: nowrap;
    }

    .invoice-table td {
        padding: 14px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: top;
        font-size: 13px;
        color: #334155;
    }

    .invoice-table tbody tr:last-child td {
        border-bottom: none;
    }

    .product-name {
        font-weight: 900;
        color: #0f172a;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .product-meta {
        font-size: 12px;
        color: #64748b;
        margin: 2px 0;
    }

    .text-right {
        text-align: right;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: 1fr 370px;
        gap: 24px;
        align-items: start;
    }

    .notes-box {
        background: #f8fafc;
        border: 1px solid #eef2f7;
        border-radius: 14px;
        padding: 16px;
    }

    .notes-box h5 {
        margin: 0 0 8px;
        font-size: 14px;
        font-weight: 900;
    }

    .notes-box p {
        margin: 0;
        font-size: 13px;
        color: #64748b;
        line-height: 1.6;
    }

    .total-card {
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        overflow: hidden;
        background: #fff;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 16px;
        border-bottom: 1px solid #f1f5f9;
        font-size: 14px;
        color: #475569;
    }

    .total-row strong {
        color: #0f172a;
    }

    .total-row.grand {
        background: #0f172a;
        color: #fff;
        font-size: 16px;
        font-weight: 900;
        border-bottom: none;
    }

    .total-row.grand strong {
        color: #fff;
    }

    .print-footer {
        padding: 18px 28px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        font-size: 12px;
        color: #64748b;
    }

    @media (max-width: 992px) {
        .invoice-header,
        .address-grid,
        .summary-grid {
            grid-template-columns: 1fr;
        }

        .invoice-meta {
            text-align: left;
        }

        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .invoice-section,
        .invoice-header {
            padding: 20px;
        }
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .invoice-wrapper,
        .invoice-wrapper * {
            visibility: visible;
        }

        .invoice-wrapper {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
            border-radius: 0;
        }

        .invoice-topbar,
        .invoice-actions,
        .admin-header,
        .sidebar,
        nav {
            display: none !important;
        }
    }
</style>
@endsection

@section('content')

@php
    $billing = $order->billing_address ?? [];
    $shipping = $order->shipping_address ?? [];

    $orderNo = $order->order_number ?? ('ORD-' . $order->id);
    $invoiceNo = $order->invoice_number ?? '-';

    $paymentStatus = $order->payment_status ?? 'pending';
    $orderStatus = $order->status ?? 'pending';

    $subtotal = $order->subtotal ?? 0;
    $discount = $order->discount_amount ?? 0;
    $shippingCharge = $order->shipping_charge ?? 0;
    $totalGst = $order->total_gst ?? 0;
    $cgst = $order->cgst ?? 0;
    $sgst = $order->sgst ?? 0;
    $igst = $order->igst ?? 0;
    $total = $order->total ?? 0;
    $amountPaid = $order->amount_paid ?? 0;
    $balanceDue = $order->balance_due ?? max(0, $total - $amountPaid);
@endphp

<div class="invoice-page">

    <div class="invoice-topbar">
        <div>
            <a href="{{ route('admin.orders.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to orders
            </a>

            <h2 class="page-title">Order Details</h2>
            <p class="page-subtitle">Professional invoice view for {{ $orderNo }}</p>
        </div>

        <div class="invoice-actions">
            <button type="button" onclick="window.print()" class="invoice-btn">
                <i class="fas fa-print"></i> Print Invoice
            </button>

            @can('order_edit')
                <a href="{{ route('admin.orders.edit', $order) }}" class="invoice-btn primary">
                    <i class="fas fa-edit"></i> Edit Order
                </a>
            @endcan
        </div>
    </div>

    <div class="invoice-wrapper">

        {{-- Invoice Header --}}
        <div class="invoice-header">
            <div class="brand-box">
                <h3>{{ config('app.name', 'Medical Store') }}</h3>

                <p>
                    Medical & Pharmaceutical Products Supplier
                </p>
                <p>
                    GST Invoice / Order Invoice
                </p>

                @if(env('SELLER_STATE'))
                    <p><strong>Seller State:</strong> {{ env('SELLER_STATE') }}</p>
                @endif
            </div>

            <div class="invoice-meta">
                <span class="invoice-label">Invoice</span>

                <p class="invoice-number">{{ $invoiceNo }}</p>

                <p class="meta-line">
                    <strong>Order No:</strong> {{ $orderNo }}
                </p>

                <p class="meta-line">
                    <strong>Order Date:</strong>
                    {{ optional($order->created_at)->format('d M Y, h:i A') }}
                </p>

                <p class="meta-line">
                    <strong>Invoice Date:</strong>
                    {{ $order->invoice_date ? $order->invoice_date->format('d M Y') : '-' }}
                </p>

                <p class="meta-line">
                    <strong>Status:</strong>
                    <span class="status-badge status-{{ $orderStatus }}">
                        {{ ucfirst($orderStatus) }}
                    </span>
                </p>

                <p class="meta-line">
                    <strong>Payment:</strong>
                    <span class="status-badge status-{{ $paymentStatus }}">
                        {{ ucfirst($paymentStatus) }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Order Summary Info --}}
        <div class="invoice-section">
            <div class="section-title">
                <i class="fas fa-file-invoice"></i>
                <h4>Order Information</h4>
            </div>

            <div class="info-grid">
                <div class="info-box">
                    <div class="info-label">Order Number</div>
                    <div class="info-value">{{ $orderNo }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Invoice Number</div>
                    <div class="info-value">{{ $invoiceNo }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Customer</div>
                    <div class="info-value">
                        {{ $order->user->name ?? ($billing['name'] ?? 'Guest Customer') }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">Payment Method</div>
                    <div class="info-value">
                        {{ $order->payment_method ? ucwords(str_replace('_', ' ', $order->payment_method)) : '-' }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">Payment Terms</div>
                    <div class="info-value">{{ $order->payment_terms ?? '-' }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Due Date</div>
                    <div class="info-value">
                        {{ $order->due_date ? $order->due_date->format('d M Y') : '-' }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">Dispatch Mode</div>
                    <div class="info-value">
                        {{ $order->dispatch_mode ? ucwords(str_replace('_', ' ', $order->dispatch_mode)) : '-' }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">Tracking No</div>
                    <div class="info-value">{{ $order->tracking_number ?? '-' }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Buyer GST No</div>
                    <div class="info-value">{{ $order->buyer_gst_no ?? '-' }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Drug License</div>
                    <div class="info-value">{{ $order->buyer_drug_license ?? '-' }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Dispatched At</div>
                    <div class="info-value">
                        {{ $order->dispatched_at ? $order->dispatched_at->format('d M Y, h:i A') : '-' }}
                    </div>
                </div>

                <div class="info-box">
                    <div class="info-label">Delivered At</div>
                    <div class="info-value">
                        {{ $order->delivered_at ? $order->delivered_at->format('d M Y, h:i A') : '-' }}
                    </div>
                </div>
            </div>
        </div>

        {{-- Address Section --}}
        <div class="invoice-section">
            <div class="section-title">
                <i class="fas fa-map-marker-alt"></i>
                <h4>Billing & Shipping Details</h4>
            </div>

            <div class="address-grid">
                <div class="address-card">
                    <h5>Billing Address</h5>

                    <p><strong>{{ $billing['name'] ?? trim(($billing['first_name'] ?? '') . ' ' . ($billing['last_name'] ?? '')) ?: '-' }}</strong></p>
                    <p>{{ $billing['email'] ?? '-' }}</p>
                    <p>{{ $billing['phone'] ?? '-' }}</p>
                    <p>{{ $billing['address_1'] ?? '-' }}</p>

                    @if(!empty($billing['address_2']))
                        <p>{{ $billing['address_2'] }}</p>
                    @endif

                    <p>
                        {{ $billing['city'] ?? '-' }},
                        {{ $billing['state'] ?? '-' }}
                        {{ $billing['postcode'] ?? '' }}
                    </p>

                    <p>{{ $billing['country'] ?? '-' }}</p>
                </div>

                <div class="address-card">
                    <h5>Shipping Address</h5>

                    <p><strong>{{ $shipping['name'] ?? trim(($shipping['first_name'] ?? '') . ' ' . ($shipping['last_name'] ?? '')) ?: '-' }}</strong></p>
                    <p>{{ $shipping['email'] ?? '-' }}</p>
                    <p>{{ $shipping['phone'] ?? '-' }}</p>
                    <p>{{ $shipping['address_1'] ?? '-' }}</p>

                    @if(!empty($shipping['address_2']))
                        <p>{{ $shipping['address_2'] }}</p>
                    @endif

                    <p>
                        {{ $shipping['city'] ?? '-' }},
                        {{ $shipping['state'] ?? '-' }}
                        {{ $shipping['postcode'] ?? '' }}
                    </p>

                    <p>{{ $shipping['country'] ?? '-' }}</p>
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <div class="invoice-section">
            <div class="section-title">
                <i class="fas fa-pills"></i>
                <h4>Order Items</h4>
            </div>

            <div class="invoice-table-wrap">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Details</th>
                            <th>Batch / Expiry</th>
                            <th>HSN</th>
                            <th class="text-right">MRP</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Taxable</th>
                            <th class="text-right">GST</th>
                            <th class="text-right">CGST</th>
                            <th class="text-right">SGST</th>
                            <th class="text-right">IGST</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($order->items as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    <div class="product-name">
                                        {{ $item->product_name ?? 'N/A' }}
                                    </div>

                                    @if(!empty($item->variant_name))
                                        <div class="product-meta">
                                            Pack: {{ $item->variant_name }}
                                        </div>
                                    @endif

                                    @if(!empty($item->sku))
                                        <div class="product-meta">
                                            SKU: {{ $item->sku }}
                                        </div>
                                    @endif
                                </td>

                                <td>
                                    <div class="product-meta">
                                        Batch: {{ $item->batch_number ?? '-' }}
                                    </div>

                                    <div class="product-meta">
                                        Expiry:
                                        {{ $item->expiry_date ? $item->expiry_date->format('d M Y') : '-' }}
                                    </div>
                                </td>

                                <td>{{ $item->hsn_code ?? '-' }}</td>

                                <td class="text-right">
                                    ₹{{ number_format($item->mrp ?? 0, 2) }}
                                </td>

                                <td class="text-right">
                                    ₹{{ number_format($item->unit_price ?? 0, 2) }}
                                </td>

                                <td class="text-right">
                                    {{ $item->qty ?? 0 }}
                                </td>

                                <td class="text-right">
                                    ₹{{ number_format($item->taxable_amount ?? 0, 2) }}
                                </td>

                                <td class="text-right">
                                    {{ number_format($item->gst_rate ?? 0, 2) }}%
                                    <div class="product-meta">
                                        ₹{{ number_format($item->gst_amount ?? 0, 2) }}
                                    </div>
                                </td>

                                <td class="text-right">
                                    ₹{{ number_format($item->cgst ?? 0, 2) }}
                                </td>

                                <td class="text-right">
                                    ₹{{ number_format($item->sgst ?? 0, 2) }}
                                </td>

                                <td class="text-right">
                                    ₹{{ number_format($item->igst ?? 0, 2) }}
                                </td>

                                <td class="text-right">
                                    <strong>₹{{ number_format($item->total ?? 0, 2) }}</strong>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="13" style="text-align:center; padding:24px; color:#64748b;">
                                    No items found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Summary --}}
        <div class="invoice-section">
            <div class="summary-grid">
                <div class="notes-box">
                    <h5>Order Notes</h5>
                    <p>{{ $order->notes ?? 'No customer notes added.' }}</p>

                    @if(!empty($order->internal_notes))
                        <h5 style="margin-top:16px;">Internal Notes</h5>
                        <p>{{ $order->internal_notes }}</p>
                    @endif

                    <h5 style="margin-top:16px;">Payment Summary</h5>
                    <p>
                        Payment Status:
                        <strong>{{ ucfirst($paymentStatus) }}</strong>
                    </p>
                    <p>
                        Amount Paid:
                        <strong>₹{{ number_format($amountPaid, 2) }}</strong>
                    </p>
                    <p>
                        Balance Due:
                        <strong>₹{{ number_format($balanceDue, 2) }}</strong>
                    </p>
                </div>

                <div class="total-card">
                    <div class="total-row">
                        <span>Subtotal</span>
                        <strong>₹{{ number_format($subtotal, 2) }}</strong>
                    </div>

                    <div class="total-row">
                        <span>Discount</span>
                        <strong>- ₹{{ number_format($discount, 2) }}</strong>
                    </div>

                    @if(!empty($order->coupon_code))
                        <div class="total-row">
                            <span>Coupon Code</span>
                            <strong>{{ $order->coupon_code }}</strong>
                        </div>
                    @endif

                    <div class="total-row">
                        <span>Shipping Charge</span>
                        <strong>
                            {{ $shippingCharge > 0 ? '₹'.number_format($shippingCharge, 2) : 'Free' }}
                        </strong>
                    </div>

                    <div class="total-row">
                        <span>Total GST</span>
                        <strong>₹{{ number_format($totalGst, 2) }}</strong>
                    </div>

                    <div class="total-row">
                        <span>CGST</span>
                        <strong>₹{{ number_format($cgst, 2) }}</strong>
                    </div>

                    <div class="total-row">
                        <span>SGST</span>
                        <strong>₹{{ number_format($sgst, 2) }}</strong>
                    </div>

                    <div class="total-row">
                        <span>IGST</span>
                        <strong>₹{{ number_format($igst, 2) }}</strong>
                    </div>

                    <div class="total-row">
                        <span>Amount Paid</span>
                        <strong>₹{{ number_format($amountPaid, 2) }}</strong>
                    </div>

                    <div class="total-row">
                        <span>Balance Due</span>
                        <strong>₹{{ number_format($balanceDue, 2) }}</strong>
                    </div>

                    <div class="total-row grand">
                        <span>Grand Total</span>
                        <strong>₹{{ number_format($total, 2) }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="print-footer">
            <div>
                <strong>Generated On:</strong>
                {{ now()->format('d M Y, h:i A') }}
            </div>

            <div>
                This is a computer generated invoice.
            </div>
        </div>

    </div>
</div>

@endsection