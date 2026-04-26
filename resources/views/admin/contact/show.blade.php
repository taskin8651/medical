@extends('layouts.admin')
@section('page-title', 'Contact Message')

@section('styles')
<style>
.detail-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden; }
.detail-header { padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px; }
.detail-body { padding:22px; }
.detail-item { margin-bottom:18px; }
.detail-label { font-size:12px; font-weight:700; color:#94A3B8; margin-bottom:6px; text-transform:uppercase; }
.detail-value { font-size:14px; color:#0F172A; line-height:1.7; }
.status-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:700; }
.status-read { background:#D1FAE5; color:#065F46; }
.status-unread { background:#E0F2FE; color:#0369A1; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:24px;">
    <div>
        <a href="{{ route('admin.contacts.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600;">← Back to contacts</a>
        <h2 style="font-size:22px; font-weight:700; margin:8px 0 0; color:#0F172A;">Message from {{ $contact->name }}</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Contact form entry details.</p>
    </div>
    @can('contact_delete')
    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" style="margin:0;">
        @csrf
        @method('DELETE')
        <button type="submit" style="font-size:13px; padding:10px 16px; border-radius:10px; background:#FECACA; color:#B91C1C; border:none; cursor:pointer;">Delete</button>
    </form>
    @endcan
</div>

<div class="detail-card">
    <div class="detail-header">
        <div style="width:34px; height:34px; border-radius:9px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center;"><i class="fas fa-envelope"></i></div>
        <div>
            <p style="font-size:14px; font-weight:700; margin:0;">Contact Details</p>
            <p style="font-size:12px; color:#94A3B8; margin:0;">Information submitted by the visitor.</p>
        </div>
    </div>
    <div class="detail-body">
        <div class="detail-item">
            <div class="detail-label">Name</div>
            <div class="detail-value">{{ $contact->name }}</div>
        </div>
        <div class="detail-item">
            <div class="detail-label">Email</div>
            <div class="detail-value">{{ $contact->email }}</div>
        </div>
        @if($contact->phone)
        <div class="detail-item">
            <div class="detail-label">Phone</div>
            <div class="detail-value">{{ $contact->phone }}</div>
        </div>
        @endif
        @if($contact->subject)
        <div class="detail-item">
            <div class="detail-label">Subject</div>
            <div class="detail-value">{{ $contact->subject }}</div>
        </div>
        @endif
        <div class="detail-item">
            <div class="detail-label">Message</div>
            <div class="detail-value">{!! nl2br(e($contact->message)) !!}</div>
        </div>
        <div class="detail-item" style="display:flex; align-items:center; justify-content:space-between; gap:14px; flex-wrap:wrap;">
            <div>
                <div class="detail-label">Received</div>
                <div class="detail-value">{{ $contact->created_at->format('d M Y, H:i') }}</div>
            </div>
            <div>
                <span class="status-badge {{ $contact->is_read ? 'status-read' : 'status-unread' }}">
                    {{ $contact->is_read ? 'Read' : 'Unread' }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection