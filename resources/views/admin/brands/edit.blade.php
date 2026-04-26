@extends('layouts.admin')
@section('page-title', 'Edit Brand')

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
.btn-primary { display:inline-flex; align-items:center; gap:8px; padding:10px 22px; border-radius:10px; background:var(--accent); color:#fff; font-size:13.5px; font-weight:600; border:none; cursor:pointer; transition:opacity .2s; }
.btn-primary:hover { opacity:.88; }
.btn-ghost { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:10px; background:#F8FAFC; color:#475569; font-size:13.5px; font-weight:600; border:1.5px solid #E2E8F0; text-decoration:none; }
.checkbox-group { display:flex; align-items:center; gap:8px; }
.checkbox-group input[type="checkbox"] { width:16px; height:16px; accent-color:var(--accent); }
.checkbox-group label { font-size:13px; color:#374151; margin:0; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <a href="{{ route('admin.brands.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">← Back to brands</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Edit Brand</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Update brand details for {{ $brand->name }}.</p>
    </div>
    <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:#fff; border:1px solid #E2E8F0; border-radius:12px;">
        <div style="width:38px; height:38px; border-radius:12px; background:#E0E7FF; color:#3730A3; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">{{ strtoupper(substr($brand->name, 0, 1)) }}</div>
        <div>
            <p style="font-size:14px; font-weight:700; margin:0; color:#0F172A;">{{ $brand->name }}</p>
            <p style="font-size:12px; color:#64748B; margin:0;">ID #{{ $brand->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.brands.update', $brand) }}">
    @method('PUT')
    @csrf

    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-copyright"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Brand Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Update brand details</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="name">Name <span class="req">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name', $brand->name) }}" class="field-input {{ $errors->has('name') ? 'error' : '' }}" required>
                @error('name')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="manufacturer_name">Manufacturer Name</label>
                <input type="text" name="manufacturer_name" id="manufacturer_name" value="{{ old('manufacturer_name', $brand->manufacturer_name) }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="drug_license_no">Drug License No</label>
                <input type="text" name="drug_license_no" id="drug_license_no" value="{{ old('drug_license_no', $brand->drug_license_no) }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="gst_no">GST No</label>
                <input type="text" name="gst_no" id="gst_no" value="{{ old('gst_no', $brand->gst_no) }}" class="field-input">
            </div>
            <div class="field-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $brand->is_active) ? 'checked' : '' }}>
                    <label for="is_active">Active</label>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:18px;">
        <a href="{{ route('admin.brands.index') }}" class="btn-ghost">Cancel</a>
        <button type="submit" class="btn-primary">Update Brand</button>
    </div>
</form>

@endsection