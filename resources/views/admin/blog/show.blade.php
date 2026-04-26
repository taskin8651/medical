@extends('layouts.admin')
@section('page-title', 'Blog Details')

@section('styles')
<style>
.detail-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden; }
.detail-header { padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px; }
.detail-body { padding:22px; }
.detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.detail-item { margin-bottom:16px; }
.detail-label { font-size:12px; font-weight:700; color:#94A3B8; margin-bottom:4px; text-transform:uppercase; }
.detail-value { font-size:14px; color:#0F172A; }
.status-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:700; }
.status-active { background:#D1FAE5; color:#065F46; }
.status-inactive { background:#FEE2E2; color:#991B1B; }
.gallery-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(140px,1fr)); gap:14px; }
.gallery-item img { width:100%; border-radius:12px; object-fit:cover; height:120px; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:24px;">
    <div>
        <a href="{{ route('admin.blog.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600;">← Back to blog</a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:8px 0 0;">{{ $blog->title }}</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Blog post details and media.</p>
    </div>
    @can('blog_edit')
    <a href="{{ route('admin.blog.edit', $blog) }}" style="font-size:13px; padding:10px 16px; border-radius:10px; background:var(--accent); color:#fff; text-decoration:none;">Edit Post</a>
    @endcan
</div>

<div class="detail-grid">
    <div class="detail-card">
        <div class="detail-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-file-alt"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Post Overview</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">All main blog data</p>
            </div>
        </div>
        <div class="detail-body">
            <div class="detail-item">
                <div class="detail-label">Slug</div>
                <div class="detail-value">{{ $blog->slug }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Short Description</div>
                <div class="detail-value">{{ $blog->short_description }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Description</div>
                <div class="detail-value">{!! nl2br(e($blog->description)) !!}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge {{ $blog->status ? 'status-active' : 'status-inactive' }}">
                        {{ $blog->status ? 'Published' : 'Draft' }}
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Created At</div>
                <div class="detail-value">{{ $blog->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Updated At</div>
                <div class="detail-value">{{ $blog->updated_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-image"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Media</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Featured image and gallery</p>
            </div>
        </div>
        <div class="detail-body">
            @if($blog->getFirstMediaUrl('featured'))
            <div style="margin-bottom:16px;">
                <div class="detail-label">Featured Image</div>
                <img src="{{ $blog->getFirstMediaUrl('featured') }}" alt="Featured" style="width:100%; border-radius:14px;">
            </div>
            @endif
            <div class="detail-label" style="margin-bottom:10px;">Gallery Images</div>
            <div class="gallery-grid">
                @forelse($blog->getMedia('gallery') as $image)
                <div class="gallery-item"><img src="{{ $image->getUrl() }}" alt="Gallery image"></div>
                @empty
                <p style="font-size:14px; color:#64748B;">No gallery images uploaded.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection