@extends('layouts.admin')
@section('page-title', 'Tier Pricing')

@section('styles')
<style>
.tier-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
    margin-bottom: 16px;
}
.tier-header {
    padding: 16px 20px;
    background: #F8FAFC;
    border-bottom: 1px solid #E2E8F0;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.tier-body {
    padding: 20px;
}
.tier-range {
    font-size: 16px;
    font-weight: 600;
    color: #0F172A;
    margin: 0;
}
.tier-price {
    font-size: 14px;
    color: #10B981;
    font-weight: 600;
    margin: 4px 0 0;
}
.tier-meta {
    display: flex;
    gap: 16px;
    font-size: 13px;
    color: #475569;
    margin-top: 8px;
}
.tier-meta-item {
    display: flex;
    align-items: center;
    gap: 6px;
}
.tier-actions {
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
.status-badge {
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 500;
    text-transform: uppercase;
}
.status-active { background: #D1FAE5; color: #065F46; }
.status-inactive { background: #FEE2E2; color: #991B1B; }
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #64748B;
}
.empty-state i { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
.add-tier-form {
    background: #F8FAFC;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
}
.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr 1fr auto;
    gap: 12px;
    align-items: end;
    margin-bottom: 12px;
}
.form-row:last-child { margin-bottom: 0; }
.field-input, .field-select {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid #E2E8F0;
    border-radius: 8px;
    font-size: 13px;
    outline: none;
}
.field-input:focus, .field-select:focus {
    border-color: var(--accent);
}
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <a href="{{ route('admin.products.variants.index', $variant->product) }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">← Back to variants</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Tier Pricing</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Manage wholesale pricing for {{ $variant->name }}</p>
    </div>
</div>

<!-- Add New Tier Form -->
<div class="add-tier-form">
    <h3 style="font-size:16px; font-weight:600; color:#0F172A; margin:0 0 16px;">Add New Tier</h3>
    <form method="POST" action="{{ route('admin.tiers.store', $variant) }}">
        @csrf
        <div class="form-row">
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Min Qty <span style="color:#EF4444;">*</span></label>
                <input type="number" name="min_qty" placeholder="e.g., 10" class="field-input" required>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Max Qty</label>
                <input type="number" name="max_qty" placeholder="Leave empty for unlimited" class="field-input">
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Price per Unit <span style="color:#EF4444;">*</span></label>
                <input type="number" step="0.01" name="price_per_unit" placeholder="e.g., 45.50" class="field-input" required>
            </div>
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:4px;">Customer Type</label>
                <select name="customer_type" class="field-select">
                    <option value="all">All Customers</option>
                    <option value="retailer">Retailers</option>
                    <option value="stockist">Stockists</option>
                    <option value="hospital">Hospitals</option>
                </select>
            </div>
            <div>
                <button type="submit" class="btn-sm btn-primary" style="height:38px;">
                    <i class="fas fa-plus"></i> Add
                </button>
            </div>
        </div>
        @error('min_qty')<p style="color:#EF4444; font-size:12px; margin:4px 0;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        @error('max_qty')<p style="color:#EF4444; font-size:12px; margin:4px 0;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        @error('price_per_unit')<p style="color:#EF4444; font-size:12px; margin:4px 0;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
        @error('customer_type')<p style="color:#EF4444; font-size:12px; margin:4px 0;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
    </form>
</div>

@if($tiers->count() > 0)
    @foreach($tiers as $tier)
        <div class="tier-card">
            <div class="tier-header">
                <div>
                    <h3 class="tier-range">{{ $tier->getRangeLabel() }}</h3>
                    <p class="tier-price">₹{{ number_format($tier->price_per_unit, 2) }} per unit</p>
                </div>
                <div class="tier-actions">
                    <form method="POST" action="{{ route('admin.tiers.update', [$variant, $tier]) }}" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="is_active" value="{{ $tier->is_active ? '0' : '1' }}">
                        <button type="submit" class="btn-sm {{ $tier->is_active ? 'btn-danger' : 'btn-success' }}">
                            <i class="fas fa-{{ $tier->is_active ? 'eye-slash' : 'eye' }}"></i>
                            {{ $tier->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </div>
            </div>
            <div class="tier-body">
                <div class="tier-meta">
                    <div class="tier-meta-item">
                        <i class="fas fa-users"></i>
                        <span>{{ ucfirst($tier->customer_type) }}</span>
                    </div>
                    <div class="tier-meta-item">
                        <i class="fas fa-percentage"></i>
                        <span>{{ $tier->discount_percent ? $tier->discount_percent . '% discount' : 'Fixed price' }}</span>
                    </div>
                    <div class="status-badge {{ $tier->is_active ? 'status-active' : 'status-inactive' }}">
                        {{ $tier->is_active ? 'Active' : 'Inactive' }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@else
    <div class="empty-state">
        <i class="fas fa-layer-group"></i>
        <h3 style="font-size:18px; color:#374151; margin:0 0 8px;">No Tier Pricing Yet</h3>
        <p style="margin:0;">Add tier pricing above to offer wholesale discounts for bulk purchases.</p>
    </div>
@endif

@endsection