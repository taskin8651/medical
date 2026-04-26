@extends('layouts.admin')
@section('page-title', 'Settings')

@section('styles')
<style>
.form-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden; margin-bottom:22px; }
.form-card-header { padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px; }
.form-card-body { padding:22px; }
.field-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
.field-input, .field-textarea, .field-select { width:100%; padding:12px 14px; border:1.5px solid #E2E8F0; border-radius:10px; font-size:13.5px; color:#1E293B; outline:none; background:#fff; font-family:inherit; }
.field-input:focus, .field-textarea:focus, .field-select:focus { border-color: var(--accent); box-shadow:0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent); }
.field-textarea { min-height:100px; resize:vertical; }
.field-error { font-size:12px; color:#EF4444; margin-top:6px; display:flex; align-items:center; gap:5px; }
.field-group { margin-bottom:18px; }
.section-title { font-size:14px; font-weight:700; color:#0F172A; margin-bottom:14px; }
.media-preview { display:grid; grid-template-columns:repeat(auto-fit,minmax(120px,1fr)); gap:12px; margin-top:12px; }
.media-preview img { width:100%; border-radius:12px; object-fit:cover; height:100px; }
.btn-primary, .btn-ghost { display:inline-flex; align-items:center; gap:8px; padding:10px 22px; border-radius:10px; font-size:13.5px; font-weight:600; text-decoration:none; }
.btn-primary { background:var(--accent); color:#fff; border:none; }
.btn-primary:hover { opacity:.92; }
.btn-ghost { background:#F8FAFC; color:#475569; border:1.5px solid #E2E8F0; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:24px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Settings</h2>
        <p style="font-size:13px; color:#64748B; margin:6px 0 0;">Update general site settings, media, SEO and popup options.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-card">
        <div class="form-card-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-cogs"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">General Settings</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Site title, contact details and address.</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="site_name">Site Name</label>
                <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $setting->site_name ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="site_title">Site Title</label>
                <input type="text" name="site_title" id="site_title" value="{{ old('site_title', $setting->site_title ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="email">Email</label>
                <input type="email" name="email" id="email" value="{{ old('email', $setting->email ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="phone">Phone</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $setting->phone ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="address">Address</label>
                <textarea name="address" id="address" class="field-textarea">{{ old('address', $setting->address ?? '') }}</textarea>
            </div>
            <div class="field-group">
                <label class="field-label" for="google_map">Google Map Embed</label>
                <textarea name="google_map" id="google_map" class="field-textarea">{{ old('google_map', $setting->google_map ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-photo-video"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Media Settings</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Upload logo, favicon, loader and open graph images.</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="logo">Logo</label>
                <input type="file" name="logo" id="logo" class="field-input">
                @if(!empty($setting->logo))
                <div class="media-preview"><img src="{{ asset('storage/' . $setting->logo) }}" alt="Logo"></div>
                @endif
            </div>
            <div class="field-group">
                <label class="field-label" for="favicon">Favicon</label>
                <input type="file" name="favicon" id="favicon" class="field-input">
                @if(!empty($setting->favicon))
                <div class="media-preview"><img src="{{ asset('storage/' . $setting->favicon) }}" alt="Favicon"></div>
                @endif
            </div>
            <div class="field-group">
                <label class="field-label" for="loader">Loader Image</label>
                <input type="file" name="loader" id="loader" class="field-input">
                @if(!empty($setting->loader))
                <div class="media-preview"><img src="{{ asset('storage/' . $setting->loader) }}" alt="Loader"></div>
                @endif
            </div>
            <div class="field-group">
                <label class="field-label" for="og_image">Open Graph Image</label>
                <input type="file" name="og_image" id="og_image" class="field-input">
                @if(!empty($setting->og_image))
                <div class="media-preview"><img src="{{ asset('storage/' . $setting->og_image) }}" alt="OG Image"></div>
                @endif
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-share-alt"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Social Profiles</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Add social media links for the footer.</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="facebook">Facebook</label>
                <input type="text" name="facebook" id="facebook" value="{{ old('facebook', $setting->facebook ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="instagram">Instagram</label>
                <input type="text" name="instagram" id="instagram" value="{{ old('instagram', $setting->instagram ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="twitter">Twitter</label>
                <input type="text" name="twitter" id="twitter" value="{{ old('twitter', $setting->twitter ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="linkedin">LinkedIn</label>
                <input type="text" name="linkedin" id="linkedin" value="{{ old('linkedin', $setting->linkedin ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="youtube">YouTube</label>
                <input type="text" name="youtube" id="youtube" value="{{ old('youtube', $setting->youtube ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="whatsapp">WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" value="{{ old('whatsapp', $setting->whatsapp ?? '') }}" class="field-input">
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-search"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">SEO Settings</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Meta title, description and keywords.</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="meta_title">Meta Title</label>
                <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $setting->meta_title ?? '') }}" class="field-input">
            </div>
            <div class="field-group">
                <label class="field-label" for="meta_description">Meta Description</label>
                <textarea name="meta_description" id="meta_description" class="field-textarea">{{ old('meta_description', $setting->meta_description ?? '') }}</textarea>
            </div>
            <div class="field-group">
                <label class="field-label" for="meta_keywords">Meta Keywords</label>
                <textarea name="meta_keywords" id="meta_keywords" class="field-textarea">{{ old('meta_keywords', $setting->meta_keywords ?? '') }}</textarea>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-card-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-window-restore"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Popup Settings</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Toggle popup message and image.</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="popup_status">Popup Status</label>
                <select name="popup_status" id="popup_status" class="field-select">
                    <option value="0" {{ old('popup_status', $setting->popup_status ?? 0) == 0 ? 'selected' : '' }}>Disabled</option>
                    <option value="1" {{ old('popup_status', $setting->popup_status ?? 0) == 1 ? 'selected' : '' }}>Enabled</option>
                </select>
            </div>
            <div class="field-group">
                <label class="field-label" for="popup_text">Popup Text</label>
                <textarea name="popup_text" id="popup_text" class="field-textarea">{{ old('popup_text', $setting->popup_text ?? '') }}</textarea>
            </div>
            <div class="field-group">
                <label class="field-label" for="popup_image">Popup Image</label>
                <input type="file" name="popup_image" id="popup_image" class="field-input">
                @if(!empty($setting->popup_image))
                <div class="media-preview"><img src="{{ asset('storage/' . $setting->popup_image) }}" alt="Popup image"></div>
                @endif
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:18px;">
        <button type="submit" class="btn-primary">Save Settings</button>
    </div>
</form>
@endsection