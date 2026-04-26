@extends('layouts.admin')
@section('page-title', 'Brand Details')

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
.status-active { background: #D1FAE5; color: #065F46; }
.status-inactive { background: #FEE2E2; color: #991B1B; }
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
        <a href="{{ route('admin.brands.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">← Back to brands</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Brand #{{ $brand->id }}</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Brand details and information</p>
    </div>
    <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:#fff; border:1px solid #E2E8F0; border-radius:12px;">
        <div style="width:38px; height:38px; border-radius:12px; background:#E0E7FF; color:#3730A3; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">{{ strtoupper(substr($brand->name, 0, 1)) }}</div>
        <div>
            <p style="font-size:14px; font-weight:700; margin:0; color:#0F172A;">{{ $brand->name }}</p>
            <p style="font-size:12px; color:#64748B; margin:0;">ID #{{ $brand->id }}</p>
        </div>
    </div>
</div>

<div class="detail-grid">
    <div class="detail-card">
        <div class="detail-header">
            <div class="detail-icon"><i class="fas fa-copyright"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Brand Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Basic brand details</p>
            </div>
        </div>
        <div class="detail-body">
            <div class="detail-item">
                <div class="detail-label">Brand ID</div>
                <div class="detail-value">#{{ $brand->id }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Name</div>
                <div class="detail-value">{{ $brand->name }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Slug</div>
                <div class="detail-value">{{ $brand->slug }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Manufacturer Name</div>
                <div class="detail-value">{{ $brand->manufacturer_name ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Drug License No</div>
                <div class="detail-value">{{ $brand->drug_license_no ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">GST No</div>
                <div class="detail-value">{{ $brand->gst_no ?? '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge status-{{ $brand->is_active ? 'active' : 'inactive' }}">
                        {{ $brand->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Created At</div>
                <div class="detail-value">{{ $brand->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Updated At</div>
                <div class="detail-value">{{ $brand->updated_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-header">
            <div class="detail-icon"><i class="fas fa-box"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Products</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Products under this brand</p>
            </div>
        </div>
        <div class="detail-body">
            @forelse($brand->products as $product)
            <div style="display:flex; justify-content:space-between; align-items:center; padding:12px 0; border-bottom:1px solid #F1F5F9;">
                <div>
                    <p style="font-size:14px; font-weight:600; color:#0F172A; margin:0;">{{ $product->name }}</p>
                    <p style="font-size:12px; color:#64748B; margin:2px 0 0;">SKU: {{ $product->sku ?? 'N/A' }}</p>
                </div>
                <div style="text-align:right;">
                    <p style="font-size:14px; font-weight:600; color:#0F172A; margin:0;">${{ number_format($product->price ?? 0, 2) }}</p>
                    <p style="font-size:12px; color:#64748B; margin:2px 0 0;">{{ $product->is_active ? 'Active' : 'Inactive' }}</p>
                </div>
            </div>
            @empty
            <p style="font-size:14px; color:#64748B; margin:0;">No products found for this brand.</p>
            @endforelse
        </div>
    </div>
</div>

<div style="display:flex; justify-content:flex-end; gap:12px; margin-top:18px;">
    @can('brand_edit')
    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn-outline" style="border-color:color-mix(in srgb, var(--accent) 40%, transparent); color:var(--accent);">Edit Brand</a>
    @endcan
</div>

@endsection