@extends('layouts.admin')
@section('page-title', trans('global.show') . ' ' . trans('cruds.user.title_singular'))

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
.role-tag {
    display: inline-flex; align-items: center;
    padding: 4px 12px; border-radius: 7px;
    font-size: 12px; font-weight: 600;
    background: var(--accent-light); color: var(--accent);
    border: 1px solid color-mix(in srgb, var(--accent) 20%, transparent);
}
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
        <a href="{{ route('admin.users.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">
            ← {{ trans('global.back_to_list') }}
        </a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">User Profile</h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Full details for this user account</p>
    </div>

    <div style="display:flex; gap:8px;">
        @can('user_edit')
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn-primary">
            <i class="fas fa-pencil-alt" style="font-size:11px;"></i> Edit User
        </a>
        @endcan
        @can('user_delete')
        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
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

    {{-- ── LEFT: Profile Card ── --}}
    <div>
        <div class="detail-card" style="margin-bottom:16px;">
            <div style="padding:28px 24px; text-align:center; background:linear-gradient(135deg, var(--accent-light) 0%, #fff 60%); border-bottom:1px solid #F1F5F9;">
                @php $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6']; @endphp
                <div style="width:72px; height:72px; border-radius:18px; background:{{ $colors[$user->id % count($colors)] }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:26px; font-weight:700; margin:0 auto 14px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <p style="font-size:17px; font-weight:700; color:#0F172A; margin:0 0 4px;">{{ $user->name }}</p>
                <p style="font-size:13px; color:#64748B; margin:0 0 12px;">{{ $user->email }}</p>
                @if($user->email_verified_at)
                    <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; background:#DCFCE7; color:#15803D; font-size:12px; font-weight:600;">
                        <i class="fas fa-check-circle" style="font-size:11px;"></i> Verified
                    </span>
                @else
                    <span style="display:inline-flex; align-items:center; gap:5px; padding:4px 12px; border-radius:20px; background:#FEF9C3; color:#92400E; font-size:12px; font-weight:600;">
                        <i class="fas fa-clock" style="font-size:11px;"></i> Unverified
                    </span>
                @endif
            </div>

            <div style="padding:16px 20px;">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                    <div class="stat-mini">
                        <p style="font-size:11px; color:#94A3B8; margin:0 0 4px;">User ID</p>
                        <p style="font-size:16px; font-weight:700; color:#0F172A; margin:0;">#{{ $user->id }}</p>
                    </div>
                    <div class="stat-mini">
                        <p style="font-size:11px; color:#94A3B8; margin:0 0 4px;">Roles</p>
                        <p style="font-size:16px; font-weight:700; color:#0F172A; margin:0;">{{ $user->roles->count() }}</p>
                    </div>
                    <div class="stat-mini" style="grid-column:span 2;">
                        <p style="font-size:11px; color:#94A3B8; margin:0 0 4px;">Member Since</p>
                        <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">{{ optional($user->created_at)->format('d M Y') ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="detail-card" style="padding:16px;">
            <p style="font-size:12px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 12px;">Quick Actions</p>
            <div style="display:flex; flex-direction:column; gap:8px;">
                @can('user_edit')
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; background:var(--accent-light); color:var(--accent); text-decoration:none; font-size:13px; font-weight:600; transition:opacity .2s;"
                   onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                    <i class="fas fa-user-edit" style="width:16px; text-align:center;"></i> Edit Profile
                </a>
                @endcan
                <a href="{{ route('admin.users.index') }}"
                   style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; background:#F8FAFC; color:#475569; text-decoration:none; font-size:13px; font-weight:600; border:1px solid #F1F5F9; transition:background .15s;"
                   onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#F8FAFC'">
                    <i class="fas fa-list" style="width:16px; text-align:center;"></i> All Users
                </a>
                @can('user_create')
                <a href="{{ route('admin.users.create') }}"
                   style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:9px; background:#F8FAFC; color:#475569; text-decoration:none; font-size:13px; font-weight:600; border:1px solid #F1F5F9; transition:background .15s;"
                   onmouseover="this.style.background='#F1F5F9'" onmouseout="this.style.background='#F8FAFC'">
                    <i class="fas fa-user-plus" style="width:16px; text-align:center;"></i> Add New User
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Details ── --}}
    <div>
        {{-- Account Details --}}
        <div class="detail-card" style="margin-bottom:16px;">
            <div style="padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; gap:10px;">
                <div style="width:32px; height:32px; border-radius:8px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center; font-size:13px;">
                    <i class="fas fa-id-card"></i>
                </div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Account Details</p>
            </div>
            <div style="padding:0 22px;">

                <div class="detail-row">
                    <span class="detail-label">{{ trans('cruds.user.fields.id') }}</span>
                    <span class="detail-value" style="font-family:monospace; background:#F8FAFC; padding:3px 8px; border-radius:6px; font-size:13px;">#{{ $user->id }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">{{ trans('cruds.user.fields.name') }}</span>
                    <span class="detail-value">{{ $user->name }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">{{ trans('cruds.user.fields.email') }}</span>
                    <div>
                        <span class="detail-value">{{ $user->email }}</span>
                        <a href="mailto:{{ $user->email }}" style="font-size:11px; color:var(--accent); text-decoration:none; margin-left:8px; font-weight:600;">Send Email</a>
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">{{ trans('cruds.user.fields.email_verified_at') }}</span>
                    @if($user->email_verified_at)
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-check-circle" style="color:#10B981; font-size:14px;"></i>
                            <span class="detail-value">{{ $user->email_verified_at->format('d M Y, H:i') }}</span>
                        </div>
                    @else
                        <div style="display:flex; align-items:center; gap:8px;">
                            <i class="fas fa-exclamation-circle" style="color:#F59E0B; font-size:14px;"></i>
                            <span class="detail-value" style="color:#92400E;">Not verified</span>
                        </div>
                    @endif
                </div>

                <div class="detail-row">
                    <span class="detail-label">Created At</span>
                    <span class="detail-value">{{ optional($user->created_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Updated At</span>
                    <span class="detail-value">{{ optional($user->updated_at)->format('d M Y, H:i') ?? '-' }}</span>
                </div>

            </div>
        </div>

        {{-- Roles --}}
        <div class="detail-card">
            <div style="padding:16px 22px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:32px; height:32px; border-radius:8px; background:var(--accent-light); color:var(--accent); display:flex; align-items:center; justify-content:center; font-size:13px;">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">{{ trans('cruds.user.fields.roles') }}</p>
                </div>
                <span style="font-size:12px; font-weight:700; color:var(--accent); background:var(--accent-light); padding:3px 10px; border-radius:20px;">
                    {{ $user->roles->count() }} assigned
                </span>
            </div>
            <div style="padding:18px 22px;">
                @if($user->roles->count())
                    <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:14px;">
                        @foreach($user->roles as $role)
                        <span class="role-tag">
                            <i class="fas fa-circle" style="font-size:6px; margin-right:5px;"></i>
                            {{ $role->title }}
                        </span>
                        @endforeach
                    </div>

                    {{-- Permission summary --}}
                    @if($user->roles->flatMap->permissions->count())
                    <div style="padding:12px 14px; background:#F8FAFC; border-radius:10px; border:1px solid #F1F5F9;">
                        <p style="font-size:12px; font-weight:700; color:#64748B; margin:0 0 8px;">Permissions via roles</p>
                        <div style="display:flex; flex-wrap:wrap; gap:5px;">
                            @foreach($user->roles->flatMap->permissions->unique('id')->take(12) as $perm)
                            <span style="padding:3px 8px; border-radius:5px; background:#E2E8F0; color:#475569; font-size:11px; font-weight:600;">{{ $perm->title }}</span>
                            @endforeach
                            @if($user->roles->flatMap->permissions->unique('id')->count() > 12)
                            <span style="padding:3px 8px; border-radius:5px; background:#E2E8F0; color:#94A3B8; font-size:11px;">+{{ $user->roles->flatMap->permissions->unique('id')->count() - 12 }} more</span>
                            @endif
                        </div>
                    </div>
                    @endif

                @else
                    <div style="text-align:center; padding:24px;">
                        <div style="width:48px; height:48px; border-radius:12px; background:#F8FAFC; display:flex; align-items:center; justify-content:center; margin:0 auto 10px; font-size:20px; color:#CBD5E1;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <p style="font-size:13.5px; font-weight:600; color:#94A3B8; margin:0 0 4px;">No roles assigned</p>
                        <p style="font-size:12px; color:#CBD5E1; margin:0;">This user has no roles yet.</p>
                        @can('user_edit')
                        <a href="{{ route('admin.users.edit', $user->id) }}"
                           style="display:inline-flex; align-items:center; gap:6px; margin-top:12px; padding:8px 16px; border-radius:9px; background:var(--accent); color:#fff; font-size:12px; font-weight:600; text-decoration:none;">
                            <i class="fas fa-plus"></i> Assign Roles
                        </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@endsection