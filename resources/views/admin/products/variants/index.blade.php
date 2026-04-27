@extends('layouts.admin')
@section('page-title', 'Product Variants')

@section('styles')
<style>
.variant-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
    margin-bottom: 16px;
}
.variant-header {
    padding: 16px 20px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.variant-body {
    padding: 20px;
}
.variant-name {
    font-size: 16px;
    font-weight: 600;
    color: #0F172A;
    margin: 0;
}
.variant-sku {
    font-size: 13px;
    color: #64748B;
    margin: 4px 0 0;
}
.variant-meta {
    display: flex;
    gap: 16px;
    font-size: 13px;
    color: #475569;
}
.variant-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}
.variant-actions {
    display: flex;
    gap: 8px;
}
.btn-sm {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    text-decoration: none;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.btn-primary { background: var(--accent); color: #fff; }
.btn-outline { background: #fff; color: var(--accent); border: 1px solid var(--accent); }
.btn-danger { background: #DC2626; color: #fff; }
.btn-success { background: #10B981; color: #fff; }
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
}
.status-active { background: #D1FAE5; color: #065F46; }
.status-inactive { background: #FEE2E2; color: #991B1B; }
.status-deleted { background: #F3F4F6; color: #6B7280; }
.tier-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 12px;
}
.tier-table th, .tier-table td {
    padding: 8px 12px;
    text-align: left;
    border-bottom: 1px solid #E2E8F0;
}
.tier-table th {
    background: #F8FAFC;
    font-weight: 600;
    font-size: 12px;
    color: #374151;
}
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #64748B;
}
.empty-state i { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <a href="{{ route('admin.products.show', $product) }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">← Back to product</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Product Variants</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Manage variants for {{ $product->name }}</p>
    </div>
    <a href="{{ route('admin.products.variants.create', $product) }}" class="btn-primary" style="padding:10px 18px; border-radius:8px; text-decoration:none;">
        <i class="fas fa-plus"></i> Add Variant
    </a>
</div>

@if($variants->count() > 0)
    @foreach($variants as $variant)
        <div class="variant-card">
            <div class="variant-header">
                <div>
                    <h3 class="variant-name">{{ $variant->name }}</h3>
                    <p class="variant-sku">SKU: {{ $variant->sku }}</p>
                </div>
                <div class="variant-actions">
                    @if($variant->trashed())
                        <form method="POST" action="{{ route('admin.products.variants.restore', [$product, $variant->id]) }}" style="display:inline;">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn-sm btn-success">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                        </form>
                    @else
                        <a href="{{ route('admin.products.variants.edit', [$product, $variant]) }}" class="btn-sm btn-outline">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form method="POST" action="{{ route('admin.products.variants.destroy', [$product, $variant]) }}" style="display:inline;" onsubmit="return confirm('Delete this variant?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-sm btn-danger">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="variant-body">
                <div class="variant-meta">
                    <div class="variant-meta-item">
                        <i class="fas fa-boxes"></i>
                        <span>Stock: {{ $variant->stock }}</span>
                    </div>
                    <div class="variant-meta-item">
                        <i class="fas fa-rupee-sign"></i>
                        <span>Price: ₹{{ number_format($variant->price, 2) }}</span>
                    </div>
                    @if($variant->expiry_date)
                        <div class="variant-meta-item">
                            <i class="fas fa-calendar-times"></i>
                            <span>Expires: {{ $variant->expiry_date->format('M d, Y') }}</span>
                        </div>
                    @endif
                    <div class="status-badge {{ $variant->trashed() ? 'status-deleted' : ($variant->is_active ? 'status-active' : 'status-inactive') }}">
                        {{ $variant->trashed() ? 'Deleted' : ($variant->is_active ? 'Active' : 'Inactive') }}
                    </div>
                </div>

                @if(!$variant->trashed() && $variant->tierPricings->count() > 0)
                    <div style="margin-top:16px;">
                        <h4 style="font-size:14px; font-weight:600; color:#374151; margin-bottom:8px;">Tier Pricing</h4>
                        <table class="tier-table">
                            <thead>
                                <tr>
                                    <th>Quantity Range</th>
                                    <th>Price per Unit</th>
                                    <th>Customer Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($variant->tierPricings as $tier)
                                    <tr>
                                        <td>{{ $tier->getRangeLabel() }}</td>
                                        <td>₹{{ number_format($tier->price_per_unit, 2) }}</td>
                                        <td>{{ ucfirst($tier->customer_type) }}</td>
                                        <td>
                                            <span class="status-badge {{ $tier->is_active ? 'status-active' : 'status-inactive' }}">
                                                {{ $tier->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('admin.tiers.index', $variant) }}" style="font-size:12px; color:var(--accent); text-decoration:none; margin-top:8px; display:inline-block;">
                            Manage tier pricing →
                        </a>
                    </div>
                @elseif(!$variant->trashed())
                    <div style="margin-top:16px; padding:16px; background:#F8FAFC; border-radius:8px; text-align:center;">
                        <p style="margin:0; color:#64748B; font-size:13px;">No tier pricing configured</p>
                        <a href="{{ route('admin.tiers.index', $variant) }}" style="font-size:12px; color:var(--accent); text-decoration:none; margin-top:4px; display:inline-block;">
                            Add tier pricing →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endforeach
@else
    <div class="empty-state">
        <i class="fas fa-boxes"></i>
        <h3 style="font-size:18px; color:#374151; margin:0 0 8px;">No Variants Yet</h3>
        <p style="margin:0 0 20px;">Create different variants of this product with different pricing, packaging, or specifications.</p>
        <a href="{{ route('admin.products.variants.create', $product) }}" class="btn-primary" style="padding:12px 24px; text-decoration:none;">
            <i class="fas fa-plus"></i> Create First Variant
        </a>
    </div>
@endif

@endsection