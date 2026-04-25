@extends('layouts.admin')
@section('page-title', 'Edit Product')

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
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Edit Product</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Update record details for {{ $product->name }}.</p>
    </div>
    <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:#fff; border:1px solid #E2E8F0; border-radius:12px;">
        <div style="width:38px; height:38px; border-radius:12px; background:#E0E7FF; color:#3730A3; display:flex; align-items:center; justify-content:center; font-size:16px; font-weight:700;">{{ strtoupper(substr($product->name, 0, 1)) }}</div>
        <div>
            <p style="font-size:14px; font-weight:700; margin:0; color:#0F172A;">{{ $product->name }}</p>
            <p style="font-size:12px; color:#64748B; margin:0;">ID #{{ $product->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-box-open"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Product details</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Prepare the product data.</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="name">Name <span class="req">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" class="field-input {{ $errors->has('name') ? 'error' : '' }}" required>
                    @error('name')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="grid-cols-2">
                    <div class="field-group">
                        <label class="field-label" for="sku">SKU</label>
                        <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}" class="field-input {{ $errors->has('sku') ? 'error' : '' }}">
                        @error('sku')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="generic_name">Generic name</label>
                        <input type="text" name="generic_name" id="generic_name" value="{{ old('generic_name', $product->generic_name) }}" class="field-input {{ $errors->has('generic_name') ? 'error' : '' }}">
                        @error('generic_name')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid-cols-2">
                    <div class="field-group">
                        <label class="field-label" for="category_id">Category <span class="req">*</span></label>
                        <select name="category_id" id="category_id" class="field-select {{ $errors->has('category_id') ? 'error' : '' }}" required>
                            <option value="">Select category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="subcategory_id">Subcategory</label>
                        <select name="subcategory_id" id="subcategory_id" class="field-select {{ $errors->has('subcategory_id') ? 'error' : '' }}">
                            <option value="">Select subcategory</option>
                            @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ old('subcategory_id', $product->subcategory_id) == $subcategory->id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                            @endforeach
                        </select>
                        @error('subcategory_id')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="field-group">
                    <label class="field-label" for="brand_id">Brand</label>
                    <select name="brand_id" id="brand_id" class="field-select {{ $errors->has('brand_id') ? 'error' : '' }}">
                        <option value="">Select brand</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                        @endforeach
                    </select>
                    @error('brand_id')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="short_description">Short description</label>
                    <textarea name="short_description" id="short_description" class="field-textarea {{ $errors->has('short_description') ? 'error' : '' }}">{{ old('short_description', $product->short_description) }}</textarea>
                    @error('short_description')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                <div class="field-group">
                    <label class="field-label" for="description">Description</label>
                    <textarea name="description" id="description" class="field-textarea {{ $errors->has('description') ? 'error' : '' }}">{{ old('description', $product->description) }}</textarea>
                    @error('description')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-cog"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Pricing & stock</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Keep inventory and pricing updated.</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="grid-cols-2">
                    <div class="field-group">
                        <label class="field-label" for="price">Price <span class="req">*</span></label>
                        <input type="number" step="0.01" name="price" id="price" value="{{ old('price', $product->price) }}" class="field-input {{ $errors->has('price') ? 'error' : '' }}" required>
                        @error('price')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="sale_price">Sale price</label>
                        <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" class="field-input {{ $errors->has('sale_price') ? 'error' : '' }}">
                        @error('sale_price')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid-cols-2">
                    <div class="field-group">
                        <label class="field-label" for="gst_rate">GST rate</label>
                        <select name="gst_rate" id="gst_rate" class="field-select {{ $errors->has('gst_rate') ? 'error' : '' }}">
                            <option value="0" {{ old('gst_rate', $product->gst_rate) === 0 || old('gst_rate', $product->gst_rate) === '0' ? 'selected' : '' }}>0%</option>
                            <option value="5" {{ old('gst_rate', $product->gst_rate) == 5 ? 'selected' : '' }}>5%</option>
                            <option value="12" {{ old('gst_rate', $product->gst_rate) == 12 ? 'selected' : '' }}>12%</option>
                            <option value="18" {{ old('gst_rate', $product->gst_rate) == 18 ? 'selected' : '' }}>18%</option>
                        </select>
                        @error('gst_rate')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="stock">Stock</label>
                        <input type="number" name="stock" id="stock" value="{{ old('stock', $product->stock) }}" class="field-input {{ $errors->has('stock') ? 'error' : '' }}">
                        @error('stock')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid-cols-2">
                    <div class="field-group">
                        <label class="field-label" for="min_qty">Min qty</label>
                        <input type="number" name="min_qty" id="min_qty" value="{{ old('min_qty', $product->min_qty) }}" class="field-input {{ $errors->has('min_qty') ? 'error' : '' }}">
                        @error('min_qty')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="max_qty">Max qty</label>
                        <input type="number" name="max_qty" id="max_qty" value="{{ old('max_qty', $product->max_qty) }}" class="field-input {{ $errors->has('max_qty') ? 'error' : '' }}">
                        @error('max_qty')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="grid-cols-2">
                    <label class="checkbox-label">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                        Active
                    </label>
                    <label class="checkbox-label">
                        <input type="hidden" name="requires_prescription" value="0">
                        <input type="checkbox" name="requires_prescription" value="1" {{ old('requires_prescription', $product->requires_prescription) ? 'checked' : '' }}>
                        Requires prescription
                    </label>
                </div>
                <div class="field-group">
                    <label class="field-label" for="images">Upload images</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" class="field-input" style="padding:8px 12px;">
                    @error('images')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    @error('images.*')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
                @if($product->media->count())
                <div class="field-group">
                    <label class="field-label">Existing media</label>
                    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:10px;">
                        @foreach($product->media as $media)
                            @if($media->type === 'image')
                                <div style="border:1px solid #E2E8F0; border-radius:12px; overflow:hidden; background:#F8FAFC;">
                                    <img src="{{ asset('storage/'.$media->file_path) }}" alt="{{ $product->name }}" style="width:100%; height:120px; object-fit:cover;">
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:18px;">
        <a href="{{ route('admin.products.index') }}" class="btn-ghost">Cancel</a>
        <button type="submit" class="btn-primary">Update Product</button>
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

@endsection