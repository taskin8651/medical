@extends('layouts.admin')
@section('page-title', 'Settings')

@section('styles')
<style>
.settings-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.settings-card-span { grid-column: span 2; }
.settings-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 10px;
    padding: 10px;
    border: 1px solid #F1F5F9;
    border-radius: 10px;
    background: #F8FAFC;
}
.settings-preview img {
    width: 82px;
    height: 58px;
    border-radius: 8px;
    object-fit: contain;
    background: #fff;
    border: 1px solid #E2E8F0;
}
.settings-toggle {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
}
.settings-toggle label {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 10px 12px;
    border: 1.5px solid #E2E8F0;
    border-radius: 9px;
    background: #fff;
    cursor: pointer;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}
.settings-toggle input {
    width: 15px;
    height: 15px;
    accent-color: var(--accent);
}
@media (max-width: 1100px) {
    .settings-grid { grid-template-columns: 1fr; }
    .settings-card-span { grid-column: auto; }
}
</style>
@endsection

@section('content')
@php
    $setting = $setting ?? null;
    $mediaFields = [
        'logo' => ['Logo', 'fas fa-image', 'Main site logo'],
        'favicon' => ['Favicon', 'fas fa-star', 'Browser tab icon'],
        'loader' => ['Loader Image', 'fas fa-spinner', 'Preloader image'],
        'og_image' => ['Open Graph Image', 'fas fa-share-nodes', 'Social sharing image'],
    ];
    $socialFields = [
        'facebook' => ['Facebook', 'fab fa-facebook-f', 'https://facebook.com/...'],
        'instagram' => ['Instagram', 'fab fa-instagram', 'https://instagram.com/...'],
        'twitter' => ['Twitter / X', 'fab fa-x-twitter', 'https://x.com/...'],
        'linkedin' => ['LinkedIn', 'fab fa-linkedin-in', 'https://linkedin.com/...'],
        'youtube' => ['YouTube', 'fab fa-youtube', 'https://youtube.com/...'],
        'whatsapp' => ['WhatsApp', 'fab fa-whatsapp', 'https://wa.me/...'],
    ];
@endphp

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Settings</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Update frontend identity, media, SEO, popup and contact details.</p>
    </div>
    <div style="display:flex; align-items:center; gap:10px; padding:10px 14px; background:#fff; border:1px solid #E2E8F0; border-radius:11px;">
        <div class="form-card-icon"><i class="fas fa-cog"></i></div>
        <div>
            <p style="font-size:13px; font-weight:700; color:#0F172A; margin:0;">Site Configuration</p>
            <p style="font-size:11px; color:#94A3B8; margin:0;">Admin settings</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="settings-grid">
        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-building"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">General Settings</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Company name and contact information</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="site_name">Site Name</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-store icon"></i>
                        <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $setting->site_name ?? '') }}" class="field-input {{ $errors->has('site_name') ? 'error' : '' }}" placeholder="S K Surgical">
                    </div>
                    @error('site_name')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="site_title">Site Title</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-heading icon"></i>
                        <input type="text" name="site_title" id="site_title" value="{{ old('site_title', $setting->site_title ?? '') }}" class="field-input {{ $errors->has('site_title') ? 'error' : '' }}" placeholder="Wholesale medical store">
                    </div>
                    @error('site_title')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="email">Email</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-envelope icon"></i>
                        <input type="email" name="email" id="email" value="{{ old('email', $setting->email ?? '') }}" class="field-input {{ $errors->has('email') ? 'error' : '' }}" placeholder="info@example.com">
                    </div>
                    @error('email')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="phone">Phone</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-phone icon"></i>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $setting->phone ?? '') }}" class="field-input {{ $errors->has('phone') ? 'error' : '' }}" placeholder="Customer support number">
                    </div>
                    @error('phone')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="address">Address</label>
                    <textarea name="address" id="address" rows="4" class="field-textarea {{ $errors->has('address') ? 'error' : '' }}">{{ old('address', $setting->address ?? '') }}</textarea>
                    @error('address')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-images"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Media Settings</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Logo, favicon and sharing images</p>
                </div>
            </div>
            <div class="form-card-body">
                @foreach($mediaFields as $field => [$label, $icon, $hint])
                    <div class="field-group">
                        <label class="field-label" for="{{ $field }}">{{ $label }}</label>
                        <input type="file" name="{{ $field }}" id="{{ $field }}" class="field-input {{ $errors->has($field) ? 'error' : '' }}" accept="image/*">
                        <p class="field-hint">{{ $hint }}</p>
                        @error($field)<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                        @if(!empty($setting?->{$field}))
                            <div class="settings-preview">
                                <img src="{{ asset('storage/' . $setting->{$field}) }}" alt="{{ $label }}">
                                <div>
                                    <p style="font-size:13px; font-weight:700; color:#0F172A; margin:0;">Current {{ $label }}</p>
                                    <p style="font-size:12px; color:#94A3B8; margin:2px 0 0;">New upload will replace this file.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-share-alt"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Social Profiles</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Footer and header social links</p>
                </div>
            </div>
            <div class="form-card-body">
                @foreach($socialFields as $field => [$label, $icon, $placeholder])
                    <div class="field-group">
                        <label class="field-label" for="{{ $field }}">{{ $label }}</label>
                        <div class="input-icon-wrap">
                            <i class="{{ $icon }} icon"></i>
                            <input type="url" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $setting->{$field} ?? '') }}" class="field-input {{ $errors->has($field) ? 'error' : '' }}" placeholder="{{ $placeholder }}">
                        </div>
                        @error($field)<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-search"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">SEO Settings</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Default search engine metadata</p>
                </div>
            </div>
            <div class="form-card-body">
                <div class="field-group">
                    <label class="field-label" for="meta_title">Meta Title</label>
                    <div class="input-icon-wrap">
                        <i class="fas fa-heading icon"></i>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $setting->meta_title ?? '') }}" class="field-input {{ $errors->has('meta_title') ? 'error' : '' }}">
                    </div>
                    @error('meta_title')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="meta_description">Meta Description</label>
                    <textarea name="meta_description" id="meta_description" rows="4" class="field-textarea {{ $errors->has('meta_description') ? 'error' : '' }}">{{ old('meta_description', $setting->meta_description ?? '') }}</textarea>
                    @error('meta_description')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="meta_keywords">Meta Keywords</label>
                    <textarea name="meta_keywords" id="meta_keywords" rows="4" class="field-textarea {{ $errors->has('meta_keywords') ? 'error' : '' }}">{{ old('meta_keywords', $setting->meta_keywords ?? '') }}</textarea>
                    @error('meta_keywords')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>

                <div class="field-group">
                    <label class="field-label" for="google_map">Google Map Embed</label>
                    <textarea name="google_map" id="google_map" rows="4" class="field-textarea {{ $errors->has('google_map') ? 'error' : '' }}">{{ old('google_map', $setting->google_map ?? '') }}</textarea>
                    @error('google_map')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="form-card settings-card-span">
            <div class="form-card-header">
                <div class="form-card-icon"><i class="fas fa-window-restore"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Popup Settings</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Frontend promotional popup content</p>
                </div>
            </div>
            <div class="form-card-body">
                <div style="display:grid; grid-template-columns:280px 1fr 1fr; gap:18px;">
                    <div class="field-group">
                        <label class="field-label">Popup Status</label>
                        <div class="settings-toggle">
                            <label>
                                <input type="radio" name="popup_status" value="1" {{ old('popup_status', $setting->popup_status ?? 0) == 1 ? 'checked' : '' }}>
                                Enabled
                            </label>
                            <label>
                                <input type="radio" name="popup_status" value="0" {{ old('popup_status', $setting->popup_status ?? 0) == 0 ? 'checked' : '' }}>
                                Disabled
                            </label>
                        </div>
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="popup_text">Popup Text</label>
                        <textarea name="popup_text" id="popup_text" rows="4" class="field-textarea {{ $errors->has('popup_text') ? 'error' : '' }}">{{ old('popup_text', $setting->popup_text ?? '') }}</textarea>
                        @error('popup_text')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                    </div>

                    <div class="field-group">
                        <label class="field-label" for="popup_image">Popup Image</label>
                        <input type="file" name="popup_image" id="popup_image" class="field-input {{ $errors->has('popup_image') ? 'error' : '' }}" accept="image/*">
                        @error('popup_image')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
                        @if(!empty($setting?->popup_image))
                            <div class="settings-preview">
                                <img src="{{ asset('storage/' . $setting->popup_image) }}" alt="Popup image">
                                <div>
                                    <p style="font-size:13px; font-weight:700; color:#0F172A; margin:0;">Current Popup Image</p>
                                    <p style="font-size:12px; color:#94A3B8; margin:2px 0 0;">Upload to replace.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> Save Settings
        </button>
    </div>
</form>
@endsection
