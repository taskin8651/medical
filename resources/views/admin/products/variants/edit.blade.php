@extends('layouts.admin')
@section('page-title', 'Edit Product Variant')

@section('styles')
<style>
.form-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
}
.form-card-header {
    padding: 16px 22px;
    border-bottom: 1px solid #F1F5F9;
    display: flex; align-items: center; gap: 10px;
}
.form-card-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--accent-light); color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.form-card-body { padding: 22px; }
.field-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
.field-label .req { color:#EF4444; margin-left:2px; }
.field-input, .field-select, .field-textarea {
    width:100%; padding:12px 14px;
    border:1.5px solid #E2E8F0; border-radius:10px;
    font-size:13.5px; color:#1E293B; outline:none;
    transition:border-color .2s, box-shadow .2s; background:#fff; font-family:inherit;
}
.field-input:focus, .field-select:focus, .field-textarea:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
}
.field-textarea { min-height:120px; resize:vertical; }
.field-error { font-size:12px; color:#EF4444; margin-top:6px; display:flex; align-items:center; gap:5px; }
.field-group { margin-bottom:20px; }
.field-group:last-child { margin-bottom:0; }
.grid-cols-2 { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.grid-cols-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:20px; }
.grid-cols-4 { display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:20px; }
.checkbox-label {
    display:inline-flex; align-items:center; gap:10px; padding:12px 14px; border-radius:12px;
    border:1.5px solid #E2E8F0; cursor:pointer; transition:all .2s; background:#fff;
}
.checkbox-label input { margin:0; }
.btn-primary { display:inline-flex; align-items:center; gap:8px; padding:10px 22px; border-radius:10px; background:var(--accent); color:#fff; font-size:13.5px; font-weight:600; border:none; cursor:pointer; transition:opacity .2s; }
.btn-primary:hover { opacity:.88; }
.btn-ghost { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:10px; background:#F8FAFC; color:#475569; font-size:13.5px; font-weight:600; border:1.5px solid #E2E8F0; text-decoration:none; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <a href="{{ route('admin.products.variants.index', $product) }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">← Back to variants</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Edit Product Variant</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Update variant details for {{ $variant->name }}</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.variants.update', [$product, $variant]) }}">
    @method('PUT')
    @csrf

    <!-- Basic Information -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-box-open"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Variant Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Basic details for this product variant</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="grid-cols-2">
                <div class="field-group">
                    <label class="field-label" for="name">Variant Name <span class="req">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $variant->name) }}" class="field-input {{ $errors->has('name') ? 'error' : '' }}" placeholder="e.g., 500mg Strip of 10" required>
                    @error('name')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="sku">SKU <span class="req">*</span></label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $variant->sku) }}" class="field-input {{ $errors->has('sku') ? 'error' : '' }}" required>
                    @error('sku')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid-cols-3">
                <div class="field-group">
                    <label class="field-label" for="barcode">Barcode</label>
                    <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $variant->barcode) }}" class="field-input {{ $errors->has('barcode') ? 'error' : '' }}">
                    @error('barcode')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="strength">Strength</label>
                    <input type="text" name="strength" id="strength" value="{{ old('strength', $variant->strength) }}" class="field-input {{ $errors->has('strength') ? 'error' : '' }}" placeholder="e.g., 500mg">
                    @error('strength')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="pack_type">Pack Type</label>
                    <input type="text" name="pack_type" id="pack_type" value="{{ old('pack_type', $variant->pack_type) }}" class="field-input {{ $errors->has('pack_type') ? 'error' : '' }}" placeholder="e.g., Strip, Bottle">
                    @error('pack_type')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid-cols-2">
                <div class="field-group">
                    <label class="field-label" for="pack_size">Pack Size</label>
                    <input type="text" name="pack_size" id="pack_size" value="{{ old('pack_size', $variant->pack_size) }}" class="field-input {{ $errors->has('pack_size') ? 'error' : '' }}" placeholder="e.g., 10 Tablets">
                    @error('pack_size')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="stock">Current Stock <span class="req">*</span></label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', $variant->stock) }}" class="field-input {{ $errors->has('stock') ? 'error' : '' }}" required>
                    @error('stock')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid-cols-2">
                <div class="field-group">
                    <label class="field-label" for="low_stock_alert">Low Stock Alert</label>
                    <input type="number" name="low_stock_alert" id="low_stock_alert" value="{{ old('low_stock_alert', $variant->low_stock_alert) }}" class="field-input {{ $errors->has('low_stock_alert') ? 'error' : '' }}">
                    @error('low_stock_alert')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="checkbox-label" style="margin-top:0;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $variant->is_active) ? 'checked' : '' }}>
                        Active Variant
                    </label>
                </div>
            </div>
        </div>
    </div>

    <!-- Batch Information -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-industry"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Batch Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Manufacturing and expiry details</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="grid-cols-3">
                <div class="field-group">
                    <label class="field-label" for="batch_number">Batch Number</label>
                    <input type="text" name="batch_number" id="batch_number" value="{{ old('batch_number', $variant->batch_number) }}" class="field-input {{ $errors->has('batch_number') ? 'error' : '' }}">
                    @error('batch_number')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="expiry_date">Expiry Date</label>
                    <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $variant->expiry_date?->format('Y-m-d')) }}" class="field-input {{ $errors->has('expiry_date') ? 'error' : '' }}">
                    @error('expiry_date')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="manufacturer_batch_no">Manufacturer Batch No</label>
                    <input type="text" name="manufacturer_batch_no" id="manufacturer_batch_no" value="{{ old('manufacturer_batch_no', $variant->manufacturer_batch_no) }}" class="field-input {{ $errors->has('manufacturer_batch_no') ? 'error' : '' }}">
                    @error('manufacturer_batch_no')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-tags"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Pricing Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">MRP, PTR, PTS and selling prices</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="grid-cols-4">
                <div class="field-group">
                    <label class="field-label" for="mrp">MRP</label>
                    <input type="number" step="0.01" name="mrp" id="mrp" value="{{ old('mrp', $variant->mrp) }}" class="field-input {{ $errors->has('mrp') ? 'error' : '' }}">
                    @error('mrp')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="ptr">PTR</label>
                    <input type="number" step="0.01" name="ptr" id="ptr" value="{{ old('ptr', $variant->ptr) }}" class="field-input {{ $errors->has('ptr') ? 'error' : '' }}">
                    @error('ptr')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="pts">PTS</label>
                    <input type="number" step="0.01" name="pts" id="pts" value="{{ old('pts', $variant->pts) }}" class="field-input {{ $errors->has('pts') ? 'error' : '' }}">
                    @error('pts')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="price">Selling Price <span class="req">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $variant->price) }}" class="field-input {{ $errors->has('price') ? 'error' : '' }}" required>
                    @error('price')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="field-group">
                <label class="field-label" for="gst_rate">GST Rate</label>
                <select name="gst_rate" id="gst_rate" class="field-select {{ $errors->has('gst_rate') ? 'error' : '' }}">
                    <option value="">Use product GST rate</option>
                    <option value="0" {{ old('gst_rate', $variant->gst_rate) === '0' ? 'selected' : '' }}>0%</option>
                    <option value="5" {{ old('gst_rate', $variant->gst_rate) == 5 ? 'selected' : '' }}>5%</option>
                    <option value="12" {{ old('gst_rate', $variant->gst_rate) == 12 ? 'selected' : '' }}>12%</option>
                    <option value="18" {{ old('gst_rate', $variant->gst_rate) == 18 ? 'selected' : '' }}>18%</option>
                </select>
                @error('gst_rate')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <!-- Tier Pricing Management -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-layer-group"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Tier Pricing</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Manage wholesale pricing tiers</p>
            </div>
        </div>
        <div class="form-card-body">
            @if($variant->tierPricings->count() > 0)
                <div style="margin-bottom:20px;">
                    <h4 style="font-size:14px; font-weight:600; color:#374151; margin-bottom:12px;">Current Tier Pricing</h4>
                    <div style="overflow-x:auto;">
                        <table style="width:100%; border-collapse:collapse;">
                            <thead>
                                <tr style="background:#F8FAFC;">
                                    <th style="padding:10px; text-align:left; font-size:12px; font-weight:600; color:#374151; border-bottom:1px solid #E2E8F0;">Quantity Range</th>
                                    <th style="padding:10px; text-align:left; font-size:12px; font-weight:600; color:#374151; border-bottom:1px solid #E2E8F0;">Price per Unit</th>
                                    <th style="padding:10px; text-align:left; font-size:12px; font-weight:600; color:#374151; border-bottom:1px solid #E2E8F0;">Customer Type</th>
                                    <th style="padding:10px; text-align:left; font-size:12px; font-weight:600; color:#374151; border-bottom:1px solid #E2E8F0;">Status</th>
                                    <th style="padding:10px; text-align:left; font-size:12px; font-weight:600; color:#374151; border-bottom:1px solid #E2E8F0;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($variant->tierPricings as $tier)
                                    <tr>
                                        <td style="padding:10px; border-bottom:1px solid #E2E8F0; font-size:13px;">{{ $tier->getRangeLabel() }}</td>
                                        <td style="padding:10px; border-bottom:1px solid #E2E8F0; font-size:13px;">₹{{ number_format($tier->price_per_unit, 2) }}</td>
                                        <td style="padding:10px; border-bottom:1px solid #E2E8F0; font-size:13px;">{{ ucfirst($tier->customer_type) }}</td>
                                        <td style="padding:10px; border-bottom:1px solid #E2E8F0;">
                                            <span style="padding:4px 8px; border-radius:12px; font-size:11px; font-weight:500; text-transform:uppercase; {{ $tier->is_active ? 'background:#D1FAE5; color:#065F46;' : 'background:#FEE2E2; color:#991B1B;' }}">
                                                {{ $tier->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td style="padding:10px; border-bottom:1px solid #E2E8F0;">
                                            <a href="{{ route('admin.tiers.index', $variant) }}" style="color:var(--accent); text-decoration:none; font-size:12px;">Manage</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <div style="padding:16px; background:#F8FAFC; border-radius:8px; text-align:center;">
                <p style="margin:0 0 12px; color:#64748B; font-size:13px;">Need to add or modify tier pricing?</p>
                <a href="{{ route('admin.tiers.index', $variant) }}" class="btn-primary" style="padding:8px 16px; text-decoration:none; font-size:13px;">
                    <i class="fas fa-layer-group"></i> Manage Tier Pricing
                </a>
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:18px;">
        <a href="{{ route('admin.products.variants.index', $product) }}" class="btn-ghost">Cancel</a>
        <button type="submit" class="btn-primary">Update Variant</button>
    </div>
</form>

@endsection