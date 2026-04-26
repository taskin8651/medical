@extends('layouts.admin')
@section('page-title', 'Brands')

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
.status-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: 600;
    text-transform: uppercase; letter-spacing: .02em;
}
.status-active { background: #D1FAE5; color: #065F46; }
.status-inactive { background: #FEE2E2; color: #991B1B; }
</style>
@endsection

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Brands</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Manage product brands</p>
    </div>
    @can('brand_create')
    <a href="{{ route('admin.brands.create') }}" class="btn-primary">
        <i class="fas fa-plus" style="font-size:11px;"></i>
        Add Brand
    </a>
    @endcan
</div>

<div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:24px;">
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; margin:0 0 6px;">Total Brands</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $brands->total() }}</p>
    </div>
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; margin:0 0 6px;">Active</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $brands->where('is_active', true)->count() }}</p>
    </div>
</div>

<div class="page-card">
    <div style="padding:16px 20px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">All Brands</p>
        <span style="font-size:12px; color:#94A3B8;">Showing {{ $brands->firstItem() ?? 0 }}–{{ $brands->lastItem() ?? 0 }} of {{ $brands->total() }}</span>
    </div>
    <div style="overflow-x:auto; padding:4px 10px;">
        <table class="min-w-full" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#F8FAFC; color:#64748B; font-size:11px; text-transform:uppercase; letter-spacing:.06em;">
                    <th style="padding:12px 16px; text-align:left;">ID</th>
                    <th style="padding:12px 16px; text-align:left;">Name</th>
                    <th style="padding:12px 16px; text-align:left;">Manufacturer</th>
                    <th style="padding:12px 16px; text-align:left;">Status</th>
                    <th style="padding:12px 16px; text-align:left;">Created</th>
                    <th style="padding:12px 16px; text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($brands as $brand)
                <tr style="border-bottom:1px solid #F1F5F9;">
                    <td style="padding:14px 16px; color:#475569;">#{{ $brand->id }}</td>
                    <td style="padding:14px 16px; color:#0F172A; font-weight:600;">{{ $brand->name }}</td>
                    <td style="padding:14px 16px; color:#475569;">{{ $brand->manufacturer_name ?? '-' }}</td>
                    <td style="padding:14px 16px;">
                        <span class="status-badge status-{{ $brand->is_active ? 'active' : 'inactive' }}">
                            {{ $brand->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td style="padding:14px 16px; color:#475569;">{{ $brand->created_at->format('d M Y') }}</td>
                    <td style="padding:14px 16px; text-align:right;">
                        <div style="display:flex; justify-content:flex-end; gap:6px; flex-wrap:wrap;">
                            @can('brand_show')
                            <a href="{{ route('admin.brands.show', $brand) }}" class="btn-outline" style="border-color:color-mix(in srgb, var(--accent) 40%, transparent); color:var(--accent);">View</a>
                            @endcan
                            @can('brand_edit')
                            <a href="{{ route('admin.brands.edit', $brand) }}" class="btn-outline" style="border-color:color-mix(in srgb, var(--accent) 40%, transparent); color:var(--accent);">Edit</a>
                            @endcan
                            @can('brand_delete')
                            <form action="{{ route('admin.brands.destroy', $brand) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                @method('DELETE') @csrf
                                <button type="submit" class="btn-outline" style="border-color:#FECDD3; color:#BE123C;">Delete</button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:24px 16px; color:#64748B; text-align:center;">No brands found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div style="padding:16px 20px; display:flex; justify-content:flex-end;">
        {{ $brands->links() }}
    </div>
</div>

@endsection