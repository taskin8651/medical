@extends('layouts.admin')
@section('page-title', 'Add Product')

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
        <a href="{{ route('admin.products.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">← Back to products</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Add Product</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Create a new product record for your store.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf

    <!-- Basic Information -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-box-open"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Basic Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Essential product details</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="name">Product Name <span class="req">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="field-input {{ $errors->has('name') ? 'error' : '' }}" required>
                @error('name')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="grid-cols-3">
                <div class="field-group">
                    <label class="field-label" for="sku">SKU</label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku') }}" class="field-input {{ $errors->has('sku') ? 'error' : '' }}">
                    @error('sku')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="generic_name">Generic Name</label>
                    <input type="text" name="generic_name" id="generic_name" value="{{ old('generic_name') }}" class="field-input {{ $errors->has('generic_name') ? 'error' : '' }}">
                    @error('generic_name')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="hsn_code">HSN Code</label>
                    <input type="text" name="hsn_code" id="hsn_code" value="{{ old('hsn_code') }}" class="field-input {{ $errors->has('hsn_code') ? 'error' : '' }}">
                    @error('hsn_code')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid-cols-2">
                <div class="field-group">
                    <label class="field-label" for="category_id">Category <span class="req">*</span></label>
                    <select name="category_id" id="category_id" class="field-select {{ $errors->has('category_id') ? 'error' : '' }}" required>
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="subcategory_id">Subcategory</label>
                    <select name="subcategory_id" id="subcategory_id" class="field-select {{ $errors->has('subcategory_id') ? 'error' : '' }}">
                        <option value="">Select subcategory</option>
                    </select>
                    @error('subcategory_id')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="field-group">
                <label class="field-label" for="brand_id">Brand</label>
                <select name="brand_id" id="brand_id" class="field-select {{ $errors->has('brand_id') ? 'error' : '' }}">
                    <option value="">Select brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="field-group">
                <label class="field-label" for="short_description">Short Description</label>
                <textarea name="short_description" id="short_description" class="field-textarea {{ $errors->has('short_description') ? 'error' : '' }}" rows="3">{{ old('short_description') }}</textarea>
                @error('short_description')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="field-group">
                <label class="field-label" for="description">Full Description</label>
                <textarea name="description" id="description" class="field-textarea {{ $errors->has('description') ? 'error' : '' }}" rows="4">{{ old('description') }}</textarea>
                @error('description')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <!-- Medical Information -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-pills"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Medical Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Drug-specific details and regulations</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="grid-cols-4">
                <div class="field-group">
                    <label class="field-label" for="drug_schedule">Drug Schedule</label>
                    <select name="drug_schedule" id="drug_schedule" class="field-select {{ $errors->has('drug_schedule') ? 'error' : '' }}">
                        <option value="">Select schedule</option>
                        <option value="H" {{ old('drug_schedule') == 'H' ? 'selected' : '' }}>H (Habit-forming)</option>
                        <option value="H1" {{ old('drug_schedule') == 'H1' ? 'selected' : '' }}>H1 (Severe habit-forming)</option>
                        <option value="X" {{ old('drug_schedule') == 'X' ? 'selected' : '' }}>X (Very severe habit-forming)</option>
                        <option value="G" {{ old('drug_schedule') == 'G' ? 'selected' : '' }}>G (General sale)</option>
                        <option value="OTC" {{ old('drug_schedule') == 'OTC' ? 'selected' : '' }}>OTC (Over-the-counter)</option>
                    </select>
                    @error('drug_schedule')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="form">Form</label>
                    <input type="text" name="form" id="form" value="{{ old('form') }}" class="field-input {{ $errors->has('form') ? 'error' : '' }}" placeholder="e.g., Tablet, Capsule">
                    @error('form')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="strength">Strength</label>
                    <input type="text" name="strength" id="strength" value="{{ old('strength') }}" class="field-input {{ $errors->has('strength') ? 'error' : '' }}" placeholder="e.g., 500mg">
                    @error('strength')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="shelf_life">Shelf Life</label>
                    <input type="text" name="shelf_life" id="shelf_life" value="{{ old('shelf_life') }}" class="field-input {{ $errors->has('shelf_life') ? 'error' : '' }}" placeholder="e.g., 24 months">
                    @error('shelf_life')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="field-group">
                <label class="field-label" for="composition">Composition</label>
                <textarea name="composition" id="composition" class="field-textarea {{ $errors->has('composition') ? 'error' : '' }}" rows="2" placeholder="Active ingredients and their quantities">{{ old('composition') }}</textarea>
                @error('composition')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="grid-cols-2">
                <div class="field-group">
                    <label class="field-label" for="storage_conditions">Storage Conditions</label>
                    <input type="text" name="storage_conditions" id="storage_conditions" value="{{ old('storage_conditions') }}" class="field-input {{ $errors->has('storage_conditions') ? 'error' : '' }}" placeholder="e.g., Store below 25°C">
                    @error('storage_conditions')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="checkbox-label" style="margin-top:0;">
                        <input type="hidden" name="requires_prescription" value="0">
                        <input type="checkbox" name="requires_prescription" value="1" {{ old('requires_prescription') ? 'checked' : '' }}>
                        Requires Prescription
                    </label>
                </div>
            </div>

            <div class="field-group">
                <label class="field-label" for="side_effects">Side Effects</label>
                <textarea name="side_effects" id="side_effects" class="field-textarea {{ $errors->has('side_effects') ? 'error' : '' }}" rows="2">{{ old('side_effects') }}</textarea>
                @error('side_effects')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="field-group">
                <label class="field-label" for="contraindications">Contraindications</label>
                <textarea name="contraindications" id="contraindications" class="field-textarea {{ $errors->has('contraindications') ? 'error' : '' }}" rows="2">{{ old('contraindications') }}</textarea>
                @error('contraindications')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <!-- Pricing & Wholesale -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-tags"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Pricing & Wholesale</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">MRP, PTR, PTS and selling prices</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="grid-cols-4">
                <div class="field-group">
                    <label class="field-label" for="mrp">MRP</label>
                    <input type="number" step="0.01" name="mrp" id="mrp" value="{{ old('mrp') }}" class="field-input {{ $errors->has('mrp') ? 'error' : '' }}">
                    @error('mrp')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="ptr">PTR</label>
                    <input type="number" step="0.01" name="ptr" id="ptr" value="{{ old('ptr') }}" class="field-input {{ $errors->has('ptr') ? 'error' : '' }}">
                    @error('ptr')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="pts">PTS</label>
                    <input type="number" step="0.01" name="pts" id="pts" value="{{ old('pts') }}" class="field-input {{ $errors->has('pts') ? 'error' : '' }}">
                    @error('pts')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="price">Selling Price <span class="req">*</span></label>
                    <input type="number" step="0.01" name="price" id="price" value="{{ old('price') }}" class="field-input {{ $errors->has('price') ? 'error' : '' }}" required>
                    @error('price')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid-cols-2">
                <div class="field-group">
                    <label class="field-label" for="sale_price">Sale Price</label>
                    <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price') }}" class="field-input {{ $errors->has('sale_price') ? 'error' : '' }}">
                    @error('sale_price')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="gst_rate">GST Rate <span class="req">*</span></label>
                    <select name="gst_rate" id="gst_rate" class="field-select {{ $errors->has('gst_rate') ? 'error' : '' }}" required>
                        <option value="">Select GST rate</option>
                        <option value="0" {{ old('gst_rate') === '0' ? 'selected' : '' }}>0%</option>
                        <option value="5" {{ old('gst_rate') == 5 ? 'selected' : '' }}>5%</option>
                        <option value="12" {{ old('gst_rate') == 12 ? 'selected' : '' }}>12%</option>
                        <option value="18" {{ old('gst_rate') == 18 ? 'selected' : '' }}>18%</option>
                    </select>
                    @error('gst_rate')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Packaging & Stock -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-boxes"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Packaging & Stock</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Package details and inventory management</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="grid-cols-4">
                <div class="field-group">
                    <label class="field-label" for="pack_size">Pack Size</label>
                    <input type="text" name="pack_size" id="pack_size" value="{{ old('pack_size') }}" class="field-input {{ $errors->has('pack_size') ? 'error' : '' }}" placeholder="e.g., 10 Tablets">
                    @error('pack_size')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="pack_type">Pack Type</label>
                    <input type="text" name="pack_type" id="pack_type" value="{{ old('pack_type') }}" class="field-input {{ $errors->has('pack_type') ? 'error' : '' }}" placeholder="e.g., Strip, Bottle">
                    @error('pack_type')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="units_per_pack">Units per Pack <span class="req">*</span></label>
                    <input type="number" name="units_per_pack" id="units_per_pack" value="{{ old('units_per_pack', 1) }}" class="field-input {{ $errors->has('units_per_pack') ? 'error' : '' }}" required>
                    @error('units_per_pack')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="stock">Current Stock <span class="req">*</span></label>
                    <input type="number" name="stock" id="stock" value="{{ old('stock', 0) }}" class="field-input {{ $errors->has('stock') ? 'error' : '' }}" required>
                    @error('stock')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid-cols-2">
                <div class="field-group">
                    <label class="field-label" for="min_qty">Minimum Order Qty</label>
                    <input type="number" name="min_qty" id="min_qty" value="{{ old('min_qty', 1) }}" class="field-input {{ $errors->has('min_qty') ? 'error' : '' }}">
                    @error('min_qty')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="max_qty">Maximum Order Qty</label>
                    <input type="number" name="max_qty" id="max_qty" value="{{ old('max_qty') }}" class="field-input {{ $errors->has('max_qty') ? 'error' : '' }}">
                    @error('max_qty')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid-cols-3">
                <label class="checkbox-label">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    Active Product
                </label>
                <label class="checkbox-label">
                    <input type="hidden" name="is_featured" value="0">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                    Featured Product
                </label>
                <label class="checkbox-label">
                    <input type="hidden" name="requires_prescription" value="0">
                    <input type="checkbox" name="requires_prescription" value="1" {{ old('requires_prescription') ? 'checked' : '' }}>
                    Requires Prescription
                </label>
            </div>
        </div>
    </div>

    <!-- Media Upload -->
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-images"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Media & Documents</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Product images and supporting documents</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="images">Product Images</label>
                <input type="file" name="images[]" id="images" multiple accept="image/*" class="field-input" style="padding:8px 12px;">
                <small style="color:#64748B; font-size:12px; margin-top:4px; display:block;">Select multiple images (JPEG, PNG, WebP). Max 2MB each. First image will be set as primary.</small>
                @error('images')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                @error('images.*')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>

            <div class="field-group">
                <label class="field-label" for="documents">Documents (PDF)</label>
                <input type="file" name="documents[]" id="documents" multiple accept=".pdf" class="field-input" style="padding:8px 12px;">
                <small style="color:#64748B; font-size:12px; margin-top:4px; display:block;">Upload brochures, certificates, or prescription samples (PDF only). Max 5MB each.</small>
                @error('documents')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                @error('documents.*')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:18px;">
        <a href="{{ route('admin.products.index') }}" class="btn-ghost">Cancel</a>
        <button type="submit" class="btn-primary">Create Product</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const categorySelect = document.getElementById('category_id');
    const subcategorySelect = document.getElementById('subcategory_id');

    categorySelect?.addEventListener('change', function() {
        const categoryId = this.value;
        subcategorySelect.innerHTML = '<option value="">Loading...</option>';

        if (!categoryId) {
            subcategorySelect.innerHTML = '<option value="">Select subcategory</option>';
            return;
        }

        fetch(`{{ url('admin/products') }}/${categoryId}/subcategories`)
            .then(response => response.json())
            .then(data => {
                subcategorySelect.innerHTML = '<option value="">Select subcategory</option>';
                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    subcategorySelect.appendChild(option);
                });
            })
            .catch(() => {
                subcategorySelect.innerHTML = '<option value="">Select subcategory</option>';
            });
    });
});
</script>

@endsection@ i n c l u d e ( " a d m i n . p r o d u c t s . m e d i a _ m o d a l " )  
 