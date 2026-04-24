@extends('layouts.admin')
@section('page-title', trans('global.show') . ' ' . trans('cruds.role.title'))

@section('styles')
<style>
.detail-row {
    display: flex; align-items: flex-start; gap: 16px;
    padding: 16px 0; border-bottom: 1px solid #F1F5F9;
}
.detail-row:last-child { border-bottom: none; }
.detail-icon {
    width: 36px; height: 36px; border-radius: 9px;
    background: #F8FAFC; color: #64748B;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0; border: 1px solid #E2E8F0;
}
.detail-content { flex: 1; }
.detail-label {
    font-size: 11px; font-weight: 700; color: #94A3B8;
    text-transform: uppercase; letter-spacing: .05em; margin-bottom: 4px;
}
.detail-value {
    font-size: 14px; font-weight: 500; color: #0F172A;
    line-height: 1.4;
}
.permission-tag {
    display: inline-flex; align-items: center;
    padding: 4px 10px; border-radius: 7px;
    font-size: 12px; font-weight: 600;
    background: var(--accent-light); color: var(--accent);
    border: 1px solid; border-color: color-mix(in srgb, var(--accent) 20%, transparent);
    margin: 2px;
}
.user-avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: var(--accent); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
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

{{-- ── HEADER ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
            <a href="{{ route('admin.roles.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600;">
                ← {{ trans('global.back_to_list') }}
            </a>
        </div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">
            {{ trans('global.show') }} {{ trans('cruds.role.title') }}
        </h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">View role details and assigned permissions</p>
    </div>
    @can('role_edit')
    <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn-primary">
        <i class="fas fa-pencil-alt" style="font-size:11px;"></i>
        {{ trans('global.edit') }}
    </a>
    @endcan
</div>

{{-- ── MAIN CONTENT ── --}}
<div style="display:grid; grid-template-columns:320px 1fr; gap:24px;">

    {{-- ── PROFILE CARD ── --}}
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:14px; padding:24px; height:fit-content;">
        <div style="text-align:center; margin-bottom:20px;">
            @php
                $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6'];
                $color  = $colors[$role->id % count($colors)];
            @endphp
            <div style="width:64px; height:64px; border-radius:16px; background:{{ $color }}; margin:0 auto 12px; display:flex; align-items:center; justify-content:center;">
                <i class="fas fa-shield-alt" style="font-size:24px; color:#fff;"></i>
            </div>
            <h3 style="font-size:18px; font-weight:700; color:#0F172A; margin:0 0 4px;">{{ $role->title }}</h3>
            <p style="font-size:13px; color:#64748B; margin:0;">Role</p>
        </div>

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px;">
            <div style="text-align:center;">
                <p style="font-size:20px; font-weight:700; color:#0F172A; margin:0;">{{ $role->permissions->count() }}</p>
                <p style="font-size:11px; color:#94A3B8; margin:2px 0 0; text-transform:uppercase; letter-spacing:.05em;">Permissions</p>
            </div>
            <div style="text-align:center;">
                <p style="font-size:20px; font-weight:700; color:#0F172A; margin:0;">{{ optional($role->users)->count() ?? 0 }}</p>
                <p style="font-size:11px; color:#94A3B8; margin:2px 0 0; text-transform:uppercase; letter-spacing:.05em;">Users</p>
            </div>
        </div>

        <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:16px;">
            <p style="font-size:12px; color:#64748B; margin:0; line-height:1.5;">
                <i class="fas fa-info-circle" style="color:var(--accent); margin-right:6px;"></i>
                This role defines access permissions for assigned users in the system.
            </p>
        </div>
    </div>

    {{-- ── DETAILS SECTION ── --}}
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:14px; overflow:hidden;">

        {{-- Basic Info --}}
        <div style="padding:24px; border-bottom:1px solid #F1F5F9;">
            <h4 style="font-size:16px; font-weight:700; color:#0F172A; margin:0 0 16px;">Basic Information</h4>
            <div class="detail-row">
                <div class="detail-icon"><i class="fas fa-hashtag"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Role ID</div>
                    <div class="detail-value">#{{ $role->id }}</div>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="fas fa-tag"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Role Title</div>
                    <div class="detail-value">{{ $role->title }}</div>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="fas fa-calendar"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Created</div>
                    <div class="detail-value">{{  optional($role->created_at)->format('M j, Y \a\t g:i A') }}</div>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-icon"><i class="fas fa-clock"></i></div>
                <div class="detail-content">
                    <div class="detail-label">Last Updated</div>
                    <div class="detail-value">{{ optional($role->updated_at)->format('M j, Y \a\t g:i A') }}</div>
                </div>
            </div>
        </div>

        {{-- Permissions --}}
        <div style="padding:24px; border-bottom:1px solid #F1F5F9;">
            <h4 style="font-size:16px; font-weight:700; color:#0F172A; margin:0 0 16px;">Assigned Permissions</h4>
            @if($role->permissions->count())
                <div style="display:flex; flex-wrap:wrap;">
                    @foreach($role->permissions as $permission)
                        <span class="permission-tag">{{ $permission->title }}</span>
                    @endforeach
                </div>
            @else
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:16px; text-align:center;">
                    <i class="fas fa-key" style="font-size:20px; color:#CBD5E1; margin-bottom:8px;"></i>
                    <p style="font-size:14px; color:#64748B; margin:0;">No permissions assigned to this role</p>
                </div>
            @endif
        </div>

        {{-- Assigned Users --}}
        <div style="padding:24px;">
            <h4 style="font-size:16px; font-weight:700; color:#0F172A; margin:0 0 16px;">Assigned Users ({{ optional($role->users)->count() ?? 0 }})</h4>
            @if( optional($role->users)->count() )
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px,1fr)); gap:12px;">
                    @foreach($role->users as $user)
                        <div style="display:flex; align-items:center; gap:10px; padding:12px; border:1px solid #E2E8F0; border-radius:10px; background:#F8FAFC;">
                            <div class="user-avatar" style="background:{{ $color }};">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size:13px; font-weight:600; color:#0F172A; margin:0;">{{ $user->name }}</p>
                                <p style="font-size:11px; color:#64748B; margin:1px 0 0;">{{ $user->email }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:10px; padding:16px; text-align:center;">
                    <i class="fas fa-users" style="font-size:20px; color:#CBD5E1; margin-bottom:8px;"></i>
                    <p style="font-size:14px; color:#64748B; margin:0;">No users assigned to this role</p>
                </div>
            @endif
        </div>

    </div>

</div>

@endsection
