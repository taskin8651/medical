@extends('layouts.admin')
@section('page-title', 'Blog Posts')

@section('styles')
<style>
.table-card { background:#fff; border-radius:14px; border:1px solid #E2E8F0; overflow:hidden; }
.table-card-header { padding:18px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; }
.table-card-body { padding:0; }
.table-list { width:100%; border-collapse:collapse; }
.table-list th, .table-list td { padding:14px 18px; text-align:left; border-bottom:1px solid #F1F5F9; font-size:13px; color:#334155; }
.table-list th { background:#F8FAFC; text-transform:uppercase; letter-spacing:.05em; font-size:12px; color:#64748B; }
.status-badge { display:inline-flex; align-items:center; gap:6px; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:700; }
.status-active { background:#D1FAE5; color:#065F46; }
.status-inactive { background:#FEE2E2; color:#991B1B; }
.action-btn { display:inline-flex; align-items:center; gap:8px; padding:8px 12px; border-radius:9px; font-size:12px; font-weight:600; border:1px solid #E2E8F0; text-decoration:none; color:#475569; background:#fff; }
.action-btn:hover { background:#F8FAFC; }
</style>
@endsection

@section('content')
<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; margin-bottom:24px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Blog Posts</h2>
        <p style="font-size:13px; color:#64748B; margin:6px 0 0;">Manage blog content, featured images and galleries.</p>
    </div>
    @can('blog_create')
    <a href="{{ route('admin.blog.create') }}" class="action-btn" style="border-color:var(--accent); color:var(--accent);">+ Add Post</a>
    @endcan
</div>

<div class="table-card">
    <div class="table-card-header">
        <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">All Blog Posts</p>
    </div>
    <div class="table-card-body">
        <table class="table-list">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($blogs as $blog)
                <tr>
                    <td>#{{ $blog->id }}</td>
                    <td>{{ Str::limit($blog->title, 50) }}</td>
                    <td>
                        <span class="status-badge {{ $blog->status ? 'status-active' : 'status-inactive' }}">
                            {{ $blog->status ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td>{{ $blog->created_at->format('d M Y') }}</td>
                    <td style="white-space:nowrap;">
                        <a href="{{ route('admin.blog.show', $blog) }}" class="action-btn">View</a>
                        @can('blog_edit')<a href="{{ route('admin.blog.edit', $blog) }}" class="action-btn">Edit</a>@endcan
                        @can('blog_delete')
                        <form action="{{ route('admin.blog.destroy', $blog) }}" method="POST" style="display:inline-block; margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn" style="border-color:#FECACA; color:#B91C1C;">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:18px 18px; color:#64748B;">No blog posts found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top:18px;">{{ $blogs->links() }}</div>
@endsection