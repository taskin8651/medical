@extends('layouts.admin')
@section('page-title', 'Edit Product')

@section('styles')
<style>
.form-card{background:#fff;border:1px solid #e2e8f0;border-radius:12px;margin-bottom:18px;overflow:hidden}
.form-card-header{padding:16px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;gap:10px}
.form-card-icon{width:34px;height:34px;border-radius:9px;background:var(--accent-light);color:var(--accent);display:flex;align-items:center;justify-content:center}
.form-card-body{padding:20px}
.field-group{margin-bottom:16px}
.field-label{display:block;font-size:13px;font-weight:700;color:#334155;margin-bottom:6px}
.field-label .req{color:#ef4444}
.field-input,.field-select,.field-textarea{width:100%;padding:11px 13px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:13.5px;color:#0f172a;background:#fff;outline:none}
.field-input:focus,.field-select:focus,.field-textarea:focus{border-color:var(--accent);box-shadow:0 0 0 3px color-mix(in srgb,var(--accent) 14%,transparent)}
.field-textarea{min-height:105px;resize:vertical}
.field-error{font-size:12px;color:#dc2626;margin-top:6px}
.grid-cols-2{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:16px}
.grid-cols-3{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}
.grid-cols-4{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:16px}
.checkbox-row{display:flex;flex-wrap:wrap;gap:12px}
.checkbox-label{display:inline-flex;align-items:center;gap:9px;padding:11px 13px;border:1.5px solid #e2e8f0;border-radius:10px;background:#fff;font-size:13px;font-weight:600;color:#334155}
.btn-primary{display:inline-flex;align-items:center;gap:8px;padding:10px 20px;border-radius:10px;background:var(--accent);color:#fff;font-size:13.5px;font-weight:700;border:0;text-decoration:none;cursor:pointer}
.btn-ghost{display:inline-flex;align-items:center;gap:8px;padding:10px 18px;border-radius:10px;background:#f8fafc;color:#475569;font-size:13.5px;font-weight:700;border:1.5px solid #e2e8f0;text-decoration:none}
@media(max-width:900px){.grid-cols-2,.grid-cols-3,.grid-cols-4{grid-template-columns:1fr}}
</style>
@endsection

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:22px;gap:12px;flex-wrap:wrap">
    <div>
        <a href="{{ route('admin.products.index') }}" style="font-size:13px;color:var(--accent);font-weight:700;text-decoration:none">&larr; Back to products</a>
        <h2 style="font-size:22px;font-weight:800;color:#0f172a;margin:6px 0 0">Edit Product</h2>
        <p style="font-size:13px;color:#64748b;margin:4px 0 0">{{ $product->name }}</p>
    </div>
    <button type="button" class="btn-primary" onclick="openMediaModal({{ $product->id }})">
        <i class="fas fa-images"></i> Manage Media
    </button>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    @include('admin.products.partials.form', compact('product', 'subcategories'))

    <div style="display:flex;justify-content:flex-end;gap:12px;margin-top:18px">
        <a href="{{ route('admin.products.index') }}" class="btn-ghost">Cancel</a>
        <button type="submit" class="btn-primary"><i class="fas fa-save"></i> Update Product</button>
    </div>
</form>

@include('admin.products.media_modal')
@endsection

@section('scripts')
@include('admin.products.partials.subcategory-script')
@endsection
