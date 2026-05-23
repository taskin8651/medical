@php
    $product = $product ?? null;
    $oldOrProduct = function (string $field, $default = null) use ($product) {
        return old($field, $product?->{$field} ?? $default);
    };
@endphp

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon"><i class="fas fa-box-open"></i></div>
        <div>
            <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0">Basic Information</p>
            <p style="font-size:12px;color:#94a3b8;margin:0">Name, category, brand and descriptions</p>
        </div>
    </div>
    <div class="form-card-body">
        <div class="field-group">
            <label class="field-label" for="name">Product Name <span class="req">*</span></label>
            <input type="text" name="name" id="name" value="{{ $oldOrProduct('name') }}" class="field-input" required>
            @error('name')<p class="field-error">{{ $message }}</p>@enderror
        </div>

        <div class="grid-cols-3">
            <div class="field-group">
                <label class="field-label" for="sku">SKU</label>
                <input type="text" name="sku" id="sku" value="{{ $oldOrProduct('sku') }}" class="field-input">
                @error('sku')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="generic_name">Generic Name</label>
                <input type="text" name="generic_name" id="generic_name" value="{{ $oldOrProduct('generic_name') }}" class="field-input">
                @error('generic_name')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="hsn_code">HSN Code</label>
                <input type="text" name="hsn_code" id="hsn_code" value="{{ $oldOrProduct('hsn_code') }}" class="field-input">
                @error('hsn_code')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid-cols-3">
            <div class="field-group">
                <label class="field-label" for="category_id">Category <span class="req">*</span></label>
                <select name="category_id" id="category_id" class="field-select" required>
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected($oldOrProduct('category_id') == $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="subcategory_id">Subcategory</label>
                <select name="subcategory_id" id="subcategory_id" class="field-select" data-selected="{{ $oldOrProduct('subcategory_id') }}">
                    <option value="">Select subcategory</option>
                    @foreach($subcategories ?? [] as $subcategory)
                        <option value="{{ $subcategory->id }}" @selected($oldOrProduct('subcategory_id') == $subcategory->id)>{{ $subcategory->name }}</option>
                    @endforeach
                </select>
                @error('subcategory_id')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="brand_id">Brand</label>
                <select name="brand_id" id="brand_id" class="field-select">
                    <option value="">Select brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" @selected($oldOrProduct('brand_id') == $brand->id)>{{ $brand->name }}</option>
                    @endforeach
                </select>
                @error('brand_id')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid-cols-2">
            <div class="field-group">
                <label class="field-label" for="short_description">Short Description</label>
                <textarea name="short_description" id="short_description" class="field-textarea">{{ $oldOrProduct('short_description') }}</textarea>
                @error('short_description')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="description">Full Description</label>
                <textarea name="description" id="description" class="field-textarea">{{ $oldOrProduct('description') }}</textarea>
                @error('description')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon"><i class="fas fa-pills"></i></div>
        <div>
            <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0">Medical Information</p>
            <p style="font-size:12px;color:#94a3b8;margin:0">Drug, composition and safety details</p>
        </div>
    </div>
    <div class="form-card-body">
        <div class="grid-cols-4">
            <div class="field-group">
                <label class="field-label" for="drug_schedule">Drug Schedule</label>
                <select name="drug_schedule" id="drug_schedule" class="field-select">
                    <option value="">Select schedule</option>
                    @foreach(['H','H1','X','G','OTC'] as $schedule)
                        <option value="{{ $schedule }}" @selected($oldOrProduct('drug_schedule') === $schedule)>{{ $schedule }}</option>
                    @endforeach
                </select>
                @error('drug_schedule')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="form">Form</label>
                <input type="text" name="form" id="form" value="{{ $oldOrProduct('form') }}" class="field-input" placeholder="Tablet, Capsule">
                @error('form')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="strength">Strength</label>
                <input type="text" name="strength" id="strength" value="{{ $oldOrProduct('strength') }}" class="field-input" placeholder="500mg">
                @error('strength')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="shelf_life">Shelf Life</label>
                <input type="text" name="shelf_life" id="shelf_life" value="{{ $oldOrProduct('shelf_life') }}" class="field-input" placeholder="24 months">
                @error('shelf_life')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid-cols-2">
            <div class="field-group">
                <label class="field-label" for="composition">Composition</label>
                <textarea name="composition" id="composition" class="field-textarea">{{ $oldOrProduct('composition') }}</textarea>
                @error('composition')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="storage_conditions">Storage Conditions</label>
                <textarea name="storage_conditions" id="storage_conditions" class="field-textarea">{{ $oldOrProduct('storage_conditions') }}</textarea>
                @error('storage_conditions')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid-cols-2">
            <div class="field-group">
                <label class="field-label" for="side_effects">Side Effects</label>
                <textarea name="side_effects" id="side_effects" class="field-textarea">{{ $oldOrProduct('side_effects') }}</textarea>
                @error('side_effects')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="contraindications">Contraindications</label>
                <textarea name="contraindications" id="contraindications" class="field-textarea">{{ $oldOrProduct('contraindications') }}</textarea>
                @error('contraindications')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon"><i class="fas fa-tags"></i></div>
        <div>
            <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0">Pricing, Pack and Stock</p>
            <p style="font-size:12px;color:#94a3b8;margin:0">Rates, GST, packaging and availability</p>
        </div>
    </div>
    <div class="form-card-body">
        <div class="grid-cols-4">
            <div class="field-group">
                <label class="field-label" for="mrp">MRP</label>
                <input type="number" step="0.01" name="mrp" id="mrp" value="{{ $oldOrProduct('mrp') }}" class="field-input">
                @error('mrp')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="ptr">PTR</label>
                <input type="number" step="0.01" name="ptr" id="ptr" value="{{ $oldOrProduct('ptr') }}" class="field-input">
                @error('ptr')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="pts">PTS</label>
                <input type="number" step="0.01" name="pts" id="pts" value="{{ $oldOrProduct('pts') }}" class="field-input">
                @error('pts')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="price">Selling Price <span class="req">*</span></label>
                <input type="number" step="0.01" name="price" id="price" value="{{ $oldOrProduct('price') }}" class="field-input" required>
                @error('price')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid-cols-4">
            <div class="field-group">
                <label class="field-label" for="sale_price">Sale Price</label>
                <input type="number" step="0.01" name="sale_price" id="sale_price" value="{{ $oldOrProduct('sale_price') }}" class="field-input">
                @error('sale_price')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="gst_rate">GST Rate <span class="req">*</span></label>
                <select name="gst_rate" id="gst_rate" class="field-select" required>
                    @foreach([0,5,12,18] as $rate)
                        <option value="{{ $rate }}" @selected((string)$oldOrProduct('gst_rate', 12) === (string)$rate)>{{ $rate }}%</option>
                    @endforeach
                </select>
                @error('gst_rate')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="stock">Stock <span class="req">*</span></label>
                <input type="number" name="stock" id="stock" value="{{ $oldOrProduct('stock', 0) }}" class="field-input" required>
                @error('stock')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="units_per_pack">Units Per Pack</label>
                <input type="number" name="units_per_pack" id="units_per_pack" value="{{ $oldOrProduct('units_per_pack', 1) }}" class="field-input">
                @error('units_per_pack')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="grid-cols-4">
            <div class="field-group">
                <label class="field-label" for="pack_size">Pack Size</label>
                <input type="text" name="pack_size" id="pack_size" value="{{ $oldOrProduct('pack_size') }}" class="field-input">
                @error('pack_size')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="pack_type">Pack Type</label>
                <input type="text" name="pack_type" id="pack_type" value="{{ $oldOrProduct('pack_type') }}" class="field-input">
                @error('pack_type')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="min_qty">Min Order Qty</label>
                <input type="number" name="min_qty" id="min_qty" value="{{ $oldOrProduct('min_qty', 1) }}" class="field-input">
                @error('min_qty')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="max_qty">Max Order Qty</label>
                <input type="number" name="max_qty" id="max_qty" value="{{ $oldOrProduct('max_qty') }}" class="field-input">
                @error('max_qty')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="checkbox-row">
            <label class="checkbox-label">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked($oldOrProduct('is_active', 1))>
                Active Product
            </label>
            <label class="checkbox-label">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" value="1" @checked($oldOrProduct('is_featured', 0))>
                Featured Product
            </label>
            <label class="checkbox-label">
                <input type="hidden" name="requires_prescription" value="0">
                <input type="checkbox" name="requires_prescription" value="1" @checked($oldOrProduct('requires_prescription', 0))>
                Requires Prescription
            </label>
        </div>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-icon"><i class="fas fa-images"></i></div>
        <div>
            <p style="font-size:14px;font-weight:800;color:#0f172a;margin:0">Images and PDF Documents</p>
            <p style="font-size:12px;color:#94a3b8;margin:0">Images and PDF documents up to 20MB each</p>
        </div>
    </div>
    <div class="form-card-body">
        <div class="grid-cols-2">
            <div class="field-group">
                <label class="field-label" for="images">Product Images</label>
                <input type="file" name="images[]" id="images" multiple accept="image/jpeg,image/png,image/webp" class="field-input" style="padding:8px 12px">
                @error('images')<p class="field-error">{{ $message }}</p>@enderror
                @error('images.*')<p class="field-error">{{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="documents">Documents (PDF)</label>
                <input type="file" name="documents[]" id="documents" multiple accept="application/pdf,.pdf" class="field-input" style="padding:8px 12px">
                @error('documents')<p class="field-error">{{ $message }}</p>@enderror
                @error('documents.*')<p class="field-error">{{ $message }}</p>@enderror
            </div>
        </div>

        @if($product && $product->getMedia('images')->count())
            <div style="margin-top:16px">
                <p style="font-size:13px;font-weight:800;color:#0f172a;margin:0 0 10px">Existing Product Images</p>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:12px">
                    @foreach($product->getMedia('images') as $media)
                        @php
                            $mediaUrl = $media->getUrl();
                            $publicPath = $media->id . '/' . $media->file_name;
                            if ($media->disk !== 'public' && \Illuminate\Support\Facades\Storage::disk('public')->exists($publicPath)) {
                                $mediaUrl = \Illuminate\Support\Facades\Storage::disk('public')->url($publicPath);
                            }
                        @endphp
                        <div data-media-card="{{ $media->id }}" style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;background:#f8fafc">
                            <img src="{{ $mediaUrl }}" alt="{{ $product->name }}" style="width:100%;height:120px;object-fit:cover;display:block">
                            <div style="padding:9px;display:grid;gap:8px">
                                <span style="font-size:12px;color:#334155;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $media->file_name }}">{{ $media->file_name }}</span>
                                <button type="button" class="btn-ghost" style="justify-content:center;padding:8px 10px;color:#b91c1c;border-color:#fecaca" onclick="deleteMediaFile({{ $media->id }}, {{ $product->id }})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
