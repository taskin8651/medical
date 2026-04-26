@extends('layouts.admin')
@section('page-title', 'Add Blog Post')

@section('styles')
<style>
.form-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden; }
.form-card-header { padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px; }
.form-card-body { padding:22px; }
.field-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
.field-input, .field-select, .field-textarea { width:100%; padding:12px 14px; border:1.5px solid #E2E8F0; border-radius:10px; font-size:13.5px; color:#1E293B; outline:none; background:#fff; font-family:inherit; }
.field-input:focus, .field-select:focus, .field-textarea:focus { border-color: var(--accent); box-shadow:0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent); }
.field-textarea { min-height:120px; resize:vertical; }
.field-error { font-size:12px; color:#EF4444; margin-top:6px; display:flex; align-items:center; gap:5px; }
.field-group { margin-bottom:20px; }
.btn-primary, .btn-ghost { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; border-radius:10px; font-size:13.5px; font-weight:600; text-decoration:none; }
.btn-primary { background:var(--accent); color:#fff; border:none; }
.btn-primary:hover { opacity:.92; }
.btn-ghost { background:#F8FAFC; color:#475569; border:1.5px solid #E2E8F0; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom:24px; flex-wrap:wrap;">
    <div>
        <a href="{{ route('admin.blog.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600;">← Back to blog</a>
        <h2 style="font-size:22px; font-weight:700; margin:8px 0 0; color:#0F172A;">Add Blog Post</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Create a new blog post with images and rich content.</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-card">
        <div class="form-card-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-pencil-alt"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Post Details</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Add title, description and images.</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="title">Title <span style="color:#EF4444;">*</span></label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" class="field-input">
                @error('title')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="short_description">Short Description</label>
                <textarea name="short_description" id="short_description" class="field-textarea">{{ old('short_description') }}</textarea>
                @error('short_description')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="description">Description <span style="color:#EF4444;">*</span></label>
                <textarea name="description" id="description" class="field-textarea">{{ old('description') }}</textarea>
                @error('description')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="featured">Featured Image</label>
                <input type="file" name="featured" id="featured" class="field-input">
                @error('featured')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="gallery">Gallery Images</label>
                <input type="file" name="gallery[]" id="gallery" class="field-input" multiple>
                @error('gallery')<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>@enderror
            </div>
            <div class="field-group">
                <label class="field-label" for="status">Status</label>
                <select name="status" id="status" class="field-select">
                    <option value="1" {{ old('status') == 1 ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>
    </div>

    <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:18px;">
        <a href="{{ route('admin.blog.index') }}" class="btn-ghost">Cancel</a>
        <button type="submit" class="btn-primary">Save Post</button>
    </div>
</form>
@endsection