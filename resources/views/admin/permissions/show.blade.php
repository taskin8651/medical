@extends('layouts.admin')
@section('page-title', trans('global.show') . ' ' . trans('cruds.permission.title_singular'))

@section('styles')
<style>
.detail-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
}
.detail-row {
    display: flex; gap: 12px; padding: 14px 0;
    border-bottom: 1px solid #F1F5F9;
    align-items: flex-start;
}
.detail-row:last-child { border-bottom: none; }
.detail-label {
    width: 160px; flex-shrink: 0;
    font-size: 12px; font-weight: 700; color: #94A3B8;
    text-transform: uppercase; letter-spacing: .05em;
    padding-top: 2px;
}
.detail-value { font-size: 14px; color: #1E293B; font-weight: 500; }
.btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 9px 18px; border-radius: 10px;
    background: var(--accent); color: #fff;
    font-size: 13px; font-weight: 600; text-decoration: none; border: none;
    cursor: pointer; transition: opacity .2s;
}
.btn-primary:hover { opacity:.88; color:#fff; }
.btn-outline {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 9px 18px; border-radius: 10px;
    background: #fff; color: #475569;
    font-size: 13px; font-weight: 600; text-decoration: none;
    border: 1.5px solid #E2E8F0; transition: background .15s;
}
.btn-outline:hover { background: #F8FAFC; color: #374151; }
.stat-mini {
    background: #F8FAFC; border-radius: 10px; border: 1px solid #F1F5F9;
    padding: 12px 16px; text-align: center;
}
</style>
@endsection

@section('content')

{{-- ── HEADER ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <a href="{{ route('admin.permissions.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">
            ← {{ trans('global.back_to_list') }}
        </a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">Permission Details</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Full details for this permission</p>
    </div>

    <div style="display:flex; gap:8px;">
        @can('permission_edit')
        <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="btn-primary">
            <i class="fas fa-pencil-alt" style="font-size:11px;"></i> Edit Permission
        </a>
        @endcan
        @can('permission_delete')
        <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST"
              onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
            @method('DELETE') @csrf
            <button type="submit"
                style="display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:10px; background:#FFF1F2; color:#BE123C; font-size:13px; font-weight:600; border:1.5px solid #FECDD3; cursor:pointer; transition:background .15s;"
                onmouseover="this.style.background='#FFE4E6'" onmouseout="this.style.background='#FFF1F2'">
                <i class="fas fa-trash-alt" style="font-size:11px;"></i> Delete
            </button>
        </form>
        @endcan
    </div>
</div>

<div style="display:grid; grid-template-columns:1fr 2fr; gap:20px; align-items:start;">

    {{-- ── LEFT: Permission Card ── --}}
    <div>
        <div class="detail-card" style="margin-bottom:16px;">
            <div style="padding:28px 24px; text-align:center; background:linear-gradient(135deg, var(--accent-light) 0%, #fff 60%); border-bottom:1px solid #F1F5F9;">
                @php $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6']; @endphp
                <div style="width:72px; height:72px; border-radius:18px; background:{{ $colors[$permission->id % count($colors)] }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:700; margin:0 auto 14px;">
                    <i class="fas fa-key"></i>
                </div>
                <p style="font-size:17px; font-weight:700; color:#0F172A; margin:0 0 4px;">{{ $permission->title }}</p>
                <p style="font-size:13px; color:#64748B; margin:0 0 12px;">Permission</p>
                <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; background:#DCFCE7; color:#15803D; font-size:12px; font-weight:600;">
                    <i class="fas fa-check-circle" style="font-size:11px;"></i> Active
                </span>
            </div>

            <div style="padding:16px 20px;">
                <div style="display:grid; grid-template-columns:1fr; gap:10px;">
                    <div class="stat-mini">
                        <p style="font-size:11px; color:#94A3B8; margin:0 0 4px;">Permission ID</p>
                        <p style="font-size:16px; font-weight:700; color:#0F172A; margin:0;">#{{ $permission->id }}</p>
                    </div>
                    <div class="stat-mini">
                        <p style="font-size:11px; color:#94A3B8; margin:0 0 4px;">Created</p>
                        <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">{{ optional($permission->created_at)->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="detail-card" style="padding:16px;">
            <p style="font-size:12px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 12px;">Quick Actions</p>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @can('permission_edit')
                <a href="{{ route('admin.permissions.edit', $permission->id) }}"
                   style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; background:var(--accent-light); color:var(--accent); text-decoration:none; font-size:13px; font-weight:600; transition:opacity .2s;"
                   onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                    <i class="fas fa-edit" style="width:16px; text-align:center;"></i> Edit Permission
                </a>
                @endcan
                <a href="{{ route('admin.permissions.index') }}"
                   style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; background:#F8FAFC; color:#475569; text-decoration:none; font-size:13px; font-weight:600; border:1px solid #F1F5F9; transition:background .15s;"
                   onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#F8FAFC'">
                    <i class="fas fa-list" style="width:16px; text-align:center;"></i> All Permissions
                </a>
                @can('permission_create')
                <a href="{{ route('admin.permissions.create') }}"
                   style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; background:#F8FAFC; color:#475569; text-decoration:none; font-size:13px; font-weight:600; border:1px solid #F1F5F9; transition:background .15s;"
                   onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#F8FAFC'">
                    <i class="fas fa-plus" style="width:16px; text-align:center;"></i> Add New Permission
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Details ── --}}
    <div>
        {{-- Permission Details --}}
        <div class="detail-card">
            <div style="padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; border-radius:8px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center; font-size:13px;">
                    <i class="fas fa-info-circle"></i>
                </div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Permission Details</p>
            </div>
            <div style="padding:0 22px;">

                <div class="detail-row">
                    <span class="detail-label">{{ trans('cruds.permission.fields.id') }}</span>
                    <span class="detail-value" style="font-family:monospace; background:#F8FAFC; padding:3px 8px; border-radius:6px; font-size:13px;">#{{ $permission->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">{{ trans('cruds.permission.fields.title') }}</span>
                    <span class="detail-value">{{ $permission->title }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">{{ optional($permission->created_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Updated At</span>
                    <span class="detail-value">{{ optional($permission->updated_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>

            </div>
        </div>
    </div>

</div>

@endsection
