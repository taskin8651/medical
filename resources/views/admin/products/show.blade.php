@extends('layouts.admin')
@section('page-title', 'Product Details')

@section('styles')
<style>
.detail-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
}
.detail-row {
    display: flex; gap: 12px; padding: 14px 0;
    border-bottom: 1px solid #F1F5F9;
    align-items: flex-start;
}
.detail-row:last-child { border-bottom: none; }
.detail-label {
    width: 160px; flex-shrink: 0;
    font-size: 12px; font-weight: 700; color: #94A3B8;
    text-transform: uppercase; letter-spacing: .05em;
    padding-top: 2px;
}
.detail-value { font-size: 14px; color: #1E293B; font-weight: 500; }
.status-pill {
    display: inline-flex; align-items:center; gap:6px;
    padding:7px 12px; border-radius:999px; font-size:12px; font-weight:700;
}
.status-pill.active { background:#DCFCE7; color:#166534; }
.status-pill.inactive { background:#FFF1F2; color:#B91C1C; }
.status-pill.prescription { background:#E0E7FF; color:#3730A3; }
.btn-primary {
    display:inline-flex; align-items:center; gap:8px;
    padding:9px 18px; border-radius:10px; background:var(--accent); color:#fff;
    font-size:13px; font-weight:600; text-decoration:none; border:none; cursor:pointer; transition:opacity .2s;
}
.btn-primary:hover { opacity:.88; }
.btn-outline {
    display:inline-flex; align-items:center; gap:7px;
    padding:9px 18px; border-radius:10px;
    background:#fff; color:#475569; font-size:13px; font-weight:600; text-decoration:none;
    border:1.5px solid #E2E8F0; transition:background .15s;
}
.btn-outline:hover { background:#F8FAFC; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <a href="{{ route('admin.products.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">← Back to products</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Product Details</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Review the complete product information below.</p>
    </div>
    <div style="display:flex; gap:10px; flex-wrap:wrap;">
        @can('product_edit')
        <a href="{{ route('admin.products.edit', $product) }}" class="btn-primary">Edit</a>
        @endcan
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; align-items:start;">
    <div>
        <div class="detail-card" style="margin-bottom:16px;">
            <div style="padding:24px 22px; background:linear-gradient(135deg, var(--accent-light) 0%, #fff 60%); border-bottom:1px solid #F1F5F9; text-align:center;">
                <div style="width:72px; height:72px; border-radius:18px; background:#EEF2FF; color:#3730A3; display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:700; margin:0 auto 14px;">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
                <p style="font-size:17px; font-weight:700; color:#0F172A; margin:0 0 4px;">{{ $product->name }}</p>
                <p style="font-size:13px; color:#64748B; margin:0;">{{ $product->sku ?? 'No SKU' }}</p>
            </div>
            <div style="padding:18px 22px; display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div style="background:#F8FAFC; border-radius:12px; padding:14px;">
                    <p style="font-size:11px; color:#94A3B8; margin:0 0 6px;">Category</p>
                    <p style="font-size:14px; color:#0F172A; margin:0;">{{ optional($product->category)->name ?? '-' }}</p>
                </div>
                <div style="background:#F8FAFC; border-radius:12px; padding:14px;">
                    <p style="font-size:11px; color:#94A3B8; margin:0 0 6px;">Brand</p>
                    <p style="font-size:14px; color:#0F172A; margin:0;">{{ optional($product->brand)->name ?? '-' }}</p>
                </div>
                <div style="background:#F8FAFC; border-radius:12px; padding:14px;">
                    <p style="font-size:11px; color:#94A3B8; margin:0 0 6px;">Price</p>
                    <p style="font-size:16px; font-weight:700; color:#0F172A; margin:0;">₹{{ number_format($product->price, 2) }}</p>
                </div>
                <div style="background:#F8FAFC; border-radius:12px; padding:14px;">
                    <p style="font-size:11px; color:#94A3B8; margin:0 0 6px;">Stock</p>
                    <p style="font-size:16px; font-weight:700; color:#0F172A; margin:0;">{{ $product->stock ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="detail-card">
            <div style="padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; border-radius:8px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center; font-size:13px;"><i class="fas fa-info-circle"></i></div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Product overview</p>
            </div>
            <div style="padding:18px 22px;">
                <div class="detail-row">
                    <span class="detail-label">Generic name</span>
                    <span class="detail-value">{{ $product->generic_name ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Form</span>
                    <span class="detail-value">{{ $product->form ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Strength</span>
                    <span class="detail-value">{{ $product->strength ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Requires prescription</span>
                    <span class="detail-value">{{ $product->requires_prescription ? 'Yes' : 'No' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Status</span>
                    <span class="detail-value">
                        <span class="status-pill {{ $product->is_active ? 'active' : 'inactive' }}">{{ $product->is_active ? 'Active' : 'Inactive' }}</span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Created at</span>
                    <span class="detail-value">{{ optional($product->created_at)->format('d M Y, H:i') ?? '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Updated at</span>
                    <span class="detail-value">{{ optional($product->updated_at)->format('d M Y, H:i') ?? '—' }}</span>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div class="detail-card" style="margin-bottom:16px;">
            <div style="padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; border-radius:8px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center; font-size:13px;"><i class="fas fa-list"></i></div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Detailed specs</p>
            </div>
            <div style="padding:18px 22px;">
                <div class="detail-row">
                    <span class="detail-label">Description</span>
                    <span class="detail-value">{!! nl2br(e($product->description ?: 'No description available.')) !!}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Composition</span>
                    <span class="detail-value">{!! nl2br(e($product->composition ?: '—')) !!}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Sale price</span>
                    <span class="detail-value">{{ $product->sale_price ? '₹'.number_format($product->sale_price, 2) : '—' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">GST</span>
                    <span class="detail-value">{{ $product->gst_rate ?? '0' }}%</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Variants</span>
                    <span class="detail-value">{{ $product->variants_count ?? $product->variants->count() }} variants</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Primary Image</span>
                    <span class="detail-value">{{ optional($product->primaryImage)->file_name ?? 'None' }}</span>
                </div>
            </div>
        </div>
        @if($product->media->where('type', 'image')->count())
        <div class="detail-card" style="padding:18px;">
            <p style="font-size:13px; font-weight:700; color:#0F172A; margin:0 0 12px;">Product images</p>
            <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:12px;">
                @foreach($product->media->where('type','image') as $media)
                <div style="border:1px solid #E2E8F0; border-radius:14px; overflow:hidden; background:#F8FAFC;">
                    <img src="{{ asset('storage/'.$media->file_path) }}" alt="{{ $product->name }}" style="width:100%; height:140px; object-fit:cover;">
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @if($product->media->where('type', 'brochure')->count())
        <div class="detail-card" style="padding:18px;">
            <p style="font-size:13px; font-weight:700; color:#0F172A; margin:0 0 12px;">Documents</p>
            <ul style="list-style:none; padding:0; margin:0; display:grid; gap:10px;">
                @foreach($product->media->where('type','brochure') as $media)
                <li style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:12px 14px; display:flex; align-items:center; justify-content:space-between; gap:10px;">
                    <span style="font-size:13px; color:#475569;">{{ $media->file_name }}</span>
                    <a href="{{ asset('storage/'.$media->file_path) }}" target="_blank" style="color:var(--accent); font-weight:600;">Download</a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
</div>

@endsection