@extends('layouts.admin')
@section('page-title', 'Categories')

@section('styles')
<style>
.page-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
}
.btn-primary {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 10px;
    background: var(--accent); color: #fff;
    font-size: 13px; font-weight: 600; text-decoration: none;
    border: none; cursor: pointer; transition: opacity .2s;
}
.btn-primary:hover { opacity: .88; color: #fff; }
.btn-outline {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 8px;
    font-size: 12px; font-weight: 600; text-decoration: none;
    border: 1.5px solid; cursor: pointer; transition: background .15s;
}
.btn-outline:hover { background: #F8FAFC; }
</style>
@endsection

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Categories</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Manage product categories</p>
    </div>
    @can('category_create')
    <a href="{{ route('admin.categories.create') }}" class="btn-primary">
        <i class="fas fa-plus" style="font-size:11px;"></i>
        Add Category
    </a>
    @endcan
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:24px;">
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; margin:0 0 6px;">Total Categories</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $categories->total() }}</p>
    </div>
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; margin:0 0 6px;">Active</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $categories->where('is_active', true)->count() }}</p>
    </div>
</div>

<div class="page-card">
    <div style="padding:16px 20px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">All Categories</p>
        <span style="font-size:12px; color:#94A3B8;">Showing {{ $categories->firstItem() ?? 0 }}–{{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }}</span>
    </div>
    <div style="overflow-x:auto; padding:4px 10px;">
        <table class="min-w-full" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#F8FAFC; color:#64748B; font-size:11px; text-transform:uppercase; letter-spacing:.06em;">
                    <th style="padding:12px 16px; text-align:left;">ID</th>
                    <th style="padding:12px 16px; text-align:left;">Name</th>
                    <th style="padding:12px 16px; text-align:left;">Slug</th>
                    <th style="padding:12px 16px; text-align:left;">Created</th>
                    <th style="padding:12px 16px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr style="border-bottom:1px solid #F1F5F9;">
                    <td style="padding:14px 16px; color:#475569;">#{{ $category->id }}</td>
                    <td style="padding:14px 16px; color:#0F172A; font-weight:600;">{{ $category->name }}</td>
                    <td style="padding:14px 16px; color:#475569; font-family:monospace;">{{ $category->slug }}</td>
                    <td style="padding:14px 16px; color:#475569;">{{ $category->created_at->format('d M Y') }}</td>
                    <td style="padding:14px 16px; text-align:right;">
                        <div style="display:flex; justify-content:flex-end; gap:6px; flex-wrap:wrap;">
                            @can('category_edit')
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-outline" style="border-color:color-mix(in srgb, var(--accent) 40%, transparent); color:var(--accent);">Edit</a>
                            @endcan
                            @can('category_delete')
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                @method('DELETE') @csrf
                                <button type="submit" class="btn-outline" style="border-color:#FECDD3; color:#BE123C;">Delete</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:24px 16px; color:#64748B; text-align:center;">No categories found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 20px; display:flex; justify-content:flex-end;">
        {{ $categories->links() }}
    </div>
</div>

@endsection