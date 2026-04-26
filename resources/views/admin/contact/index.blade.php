@extends('layouts.admin')
@section('page-title', 'Contacts')

@section('styles')
<style>
.table-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden; }
.table-card-header { padding:18px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; }
.table-card-body { padding:0; }
.table-list { width:100%; border-collapse:collapse; }
.table-list th, .table-list td { padding:14px 18px; text-align:left; border-bottom:1px solid #F1F5F9; font-size:13px; color:#334155; }
.table-list th { background:#F8FAFC; text-transform:uppercase; letter-spacing:.05em; font-size:12px; color:#64748B; }
.status-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:700; }
.status-unread { background:#E0F2FE; color:#0369A1; }
.status-read { background:#D1FAE5; color:#065F46; }
.action-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:9px; font-size:12px; font-weight:600; border:1px solid #E2E8F0; text-decoration:none; color:#475569; background:#fff; }
.action-btn:hover { background:#F8FAFC; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; margin-bottom:24px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Contacts</h2>
        <p style="font-size:13px; color:#64748B; margin:6px 0 0;">View messages submitted from the contact form.</p>
    </div>
</div>

<div class="table-card">
    <div class="table-card-header">
        <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">All Contact Messages</p>
    </div>
    <div class="table-card-body">
        <table class="table-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                <tr>
                    <td>#{{ $contact->id }}</td>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->subject ?? '—' }}</td>
                    <td>
                        <span class="status-badge {{ $contact->is_read ? 'status-read' : 'status-unread' }}">
                            {{ $contact->is_read ? 'Read' : 'Unread' }}
                        </span>
                    </td>
                    <td>{{ $contact->created_at->format('d M Y') }}</td>
                    <td style="white-space:nowrap;">
                        <a href="{{ route('admin.contacts.show', $contact) }}" class="action-btn">View</a>
                        @can('contact_delete')
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" style="display:inline-block; margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn" style="border-color:#FECACA; color:#B91C1C;">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding:18px 18px; color:#64748B;">No contact messages found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection