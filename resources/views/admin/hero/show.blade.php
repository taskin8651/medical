@extends('layouts.admin')
@section('page-title', 'Hero Details')

@section('styles')
<style>
.detail-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden; }
.detail-header { padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px; }
.detail-body { padding:22px; }
.detail-grid { display:grid; grid-template-columns:1fr 1fr; gap:20px; }
.detail-label { font-size:12px; font-weight:700; color:#94A3B8; margin-bottom:4px; text-transform:uppercase; }
.detail-value { font-size:14px; color:#0F172A; }
.hero-image { width:100%; border-radius:14px; object-fit:cover; max-height:260px; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:24px;">
    <div>
        <a href="{{ route('admin.hero.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600;">← Back to hero slides</a>
        <h2 style="font-size:22px; font-weight:700; margin:8px 0 0; color:#0F172A;">{{ $hero->title }}</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Hero slide details and preview.</p>
    </div>
    @can('hero_edit')
    <a href="{{ route('admin.hero.edit', $hero) }}" style="font-size:13px; padding:10px 16px; border-radius:10px; background:var(--accent); color:#fff; text-decoration:none;">Edit Hero</a>
    @endcan
</div>

<div class="detail-grid">
    <div class="detail-card">
        <div class="detail-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-file-alt"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Hero Content</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Text, button and link details.</p>
            </div>
        </div>
        <div class="detail-body">
            <div style="margin-bottom:16px;">
                <div class="detail-label">Subtitle</div>
                <div class="detail-value">{{ $hero->subtitle ?? '—' }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <div class="detail-label">Description</div>
                <div class="detail-value">{!! nl2br(e($hero->description)) !!}</div>
            </div>
            <div style="margin-bottom:16px;">
                <div class="detail-label">Button Text</div>
                <div class="detail-value">{{ $hero->button_text ?? '—' }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <div class="detail-label">Button Link</div>
                <div class="detail-value">{{ $hero->button_link ?? '—' }}</div>
            </div>
            <div style="margin-bottom:16px;">
                <div class="detail-label">Status</div>
                <div class="detail-value">{{ $hero->status ? 'Active' : 'Inactive' }}</div>
            </div>
            <div>
                <div class="detail-label">Updated At</div>
                <div class="detail-value">{{ $hero->updated_at->format('d M Y, H:i') }}</div>
            </div>
        </div>
    </div>

    <div class="detail-card">
        <div class="detail-header">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-image"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; margin:0;">Image Preview</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Current hero slide image.</p>
            </div>
        </div>
        <div class="detail-body">
            @if($hero->image)
            <img src="{{ asset('storage/' . $hero->image) }}" alt="Hero image" class="hero-image">
            @else
            <p style="font-size:14px; color:#64748B;">No image uploaded.</p>
            @endif
        </div>
    </div>
</div>
@endsection