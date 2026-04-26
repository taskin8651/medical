@extends('layouts.admin')
@section('page-title', 'Gallery Details')

@section('styles')
<style>
.detail-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden; }
.detail-header { padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px; }
.detail-body { padding:22px; }
.detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.detail-label { font-size:12px; font-weight:700; color:#94A3B8; margin-bottom:4px; text-transform:uppercase; }
.detail-value { font-size:14px; color:#0F172A; }
.gallery-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:14px; }
.gallery-item img { width:100%; height:120px; object-fit:cover; border-radius:12px; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:24px;">
    <div>
        <a href="{{ route('admin.gallery.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600;">← Back to galleries</a>
        <h2 style="font-size:22px; font-weight:700; margin:8px 0 0; color:#0F172A;">{{ $gallery->title ?? 'Gallery' }}</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">View gallery images and metadata.</p>
    </div>
    @can('gallery_edit')
    <a href="{{ route('admin.gallery.edit', $gallery) }}" style="font-size:13px; padding:10px 16px; border-radius:10px; background:var(--accent); color:#fff; text-decoration:none;">Edit Gallery</a>
    @endcan
</div>

<div class="detail-grid">
    <div class="detail-card">
        <div class="detail-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-file-alt"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Gallery Info</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Title and timestamps</p>
            </div>
        </div>
        <div class="detail-body">
            <div style="margin-bottom:16px;">
                <div class="detail-label">Title</div>
                <div class="detail-value">{{ $gallery->title ?? 'Untitled' }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <div class="detail-label">Images</div>
                <div class="detail-value">{{ $gallery->getMedia('gallery')->count() }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <div class="detail-label">Created At</div>
                <div class="detail-value">{{ $gallery->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div>
                <div class="detail-label">Updated At</div>
                <div class="detail-value">{{ $gallery->updated_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-images"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Gallery Images</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Uploaded images in this gallery</p>
            </div>
        </div>
        <div class="detail-body">
            <div class="gallery-grid">
                @forelse($gallery->getMedia('gallery') as $media)
                <div class="gallery-item"><img src="{{ $media->getUrl() }}" alt="Gallery item"></div>
                @empty
                <p style="font-size:14px; color:#64748B;">No images uploaded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection