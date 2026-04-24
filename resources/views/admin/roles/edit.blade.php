@extends('layouts.admin')
@section('page-title', trans('global.edit') . ' ' . trans('cruds.role.title_singular'))

@section('styles')
<style>
.form-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #E2E8F0;
    overflow: hidden;
}
.form-card-header {
    padding: 16px 22px;
    border-bottom: 1px solid #F1F5F9;
    display: flex; align-items: center; gap: 10px;
}
.form-card-icon {
    width: 34px; height: 34px; border-radius: 9px;
    background: var(--accent-light); color: var(--accent);
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.form-card-body { padding: 22px; }

.field-label {
    display: block; font-size: 13px; font-weight: 600;
    color: #374151; margin-bottom: 6px;
}
.field-label .req { color: #EF4444; margin-left: 2px; }
.field-input {
    width: 100%; padding: 9px 13px;
    border: 1.5px solid #E2E8F0; border-radius: 9px;
    font-size: 13.5px; color: #1E293B; outline: none;
    transition: border-color .2s, box-shadow .2s;
    background: #fff; font-family: inherit;
}
.field-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
}
.field-input.error { border-color: #EF4444; }
.field-input.error:focus { box-shadow: 0 0 0 3px rgba(239,68,68,.15); }
.field-hint { font-size: 12px; color: #94A3B8; margin-top: 5px; }
.field-error { font-size: 12px; color: #EF4444; margin-top: 5px; display:flex; align-items:center; gap:4px; }
.field-group { margin-bottom: 20px; }
.field-group:last-child { margin-bottom: 0; }

.input-icon-wrap { position: relative; }
.input-icon-wrap .icon {
    position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
    color: #9CA3AF; font-size: 13px; pointer-events: none;
}
.input-icon-wrap .field-input { padding-left: 36px; }

.role-checkbox-item {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 9px; border: 1.5px solid #E2E8F0;
    cursor: pointer; transition: all .2s; background: #fff;
}
.role-checkbox-item:hover { border-color: var(--accent); background: var(--accent-light); }
.role-checkbox-item input[type=checkbox] { display: none; }
.role-checkbox-item .check-icon {
    width: 20px; height: 20px; border-radius: 6px; border: 2px solid #CBD5E1;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    transition: all .2s;
}
.role-checkbox-item.checked { border-color: var(--accent); background: var(--accent-light); }
.role-checkbox-item.checked .check-icon { background: var(--accent); border-color: var(--accent); }
.role-checkbox-item.checked .check-icon::after {
    content: ''; width: 10px; height: 6px;
    border-left: 2px solid #fff; border-bottom: 2px solid #fff;
    transform: rotate(-45deg) translateY(-1px);
}

.btn-primary {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 22px; border-radius: 10px;
    background: var(--accent); color: #fff;
    font-size: 13.5px; font-weight: 600; border: none;
    cursor: pointer; transition: opacity .2s; font-family: inherit;
}
.btn-primary:hover { opacity: .88; }
.btn-ghost {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 18px; border-radius: 10px;
    background: #F8FAFC; color: #475569;
    font-size: 13.5px; font-weight: 600; border: 1.5px solid #E2E8F0;
    cursor: pointer; text-decoration: none; transition: background .15s;
    font-family: inherit;
}
.btn-ghost:hover { background: #F1F5F9; }
.btn-danger {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 10px 18px; border-radius: 10px;
    background: #FFF1F2; color: #BE123C;
    font-size: 13.5px; font-weight: 600; border: 1.5px solid #FECDD3;
    cursor: pointer; text-decoration: none; transition: background .15s;
    font-family: inherit;
}
.btn-danger:hover { background: #FEF2F2; }

.identity-pill {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 6px 12px; border-radius: 20px;
    background: var(--accent-light); color: var(--accent);
    font-size: 12px; font-weight: 600; border: 1px solid;
    border-color: color-mix(in srgb, var(--accent) 20%, transparent);
}
.identity-pill .avatar {
    width: 24px; height: 24px; border-radius: 6px;
    background: var(--accent); color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; flex-shrink: 0;
}
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
            {{ trans('global.edit') }} {{ trans('cruds.role.title_singular') }}
        </h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Update role details and assigned permissions</p>
    </div>
    <div class="identity-pill">
        <div class="avatar">
            <i class="fas fa-shield-alt" style="font-size:10px;"></i>
        </div>
        <span>{{ $role->title }}</span>
    </div>
</div>

{{-- ── META INFO ── --}}
<div style="background:#F8FAFC; border:1px solid #E2E8F0; border-radius:12px; padding:16px 20px; margin-bottom:24px;">
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:16px;">
        <div>
            <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 4px;">Created</p>
            <p style="font-size:13px; color:#374151; margin:0;">{{ optional($role->created_at)->format('M j, Y \a\t g:i A') }}</p>
        </div>
        <div>
            <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 4px;">Last Updated</p>
            <p style="font-size:13px; color:#374151; margin:0;">{{ optional($role->updated_at)->format('M j, Y \a\t g:i A') }}</p>
        </div>
        <div>
            <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 4px;">Users Assigned</p>
            <p style="font-size:13px; color:#374151; margin:0;">{{ optional($role->users)->count() ?? 0 }} users</p>
        </div>
        <div>
            <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 4px;">Permissions</p>
            <p style="font-size:13px; color:#374151; margin:0;">{{ $role->permissions->count() }} permissions</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
@method('PUT')
@csrf

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- ── ROLE INFO CARD ── --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-shield-alt"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Role Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Basic role details</p>
            </div>
        </div>
        <div class="form-card-body">

            {{-- Title --}}
            <div class="field-group">
                <label class="field-label" for="title">
                    {{ trans('cruds.role.fields.title') }} <span class="req">*</span>
                </label>
                <div class="input-icon-wrap">
                    <i class="fas fa-tag icon"></i>
                    <input type="text" name="title" id="title"
                           value="{{ old('title', $role->title) }}" required
                           placeholder="Enter role title"
                           class="field-input {{ $errors->has('title') ? 'error' : '' }}">
                </div>
                @if($errors->has('title'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('title') }}</p>
                @elseif(trans('cruds.role.fields.title_helper'))
                    <p class="field-hint">{{ trans('cruds.role.fields.title_helper') }}</p>
                @endif
            </div>

        </div>
    </div>

    {{-- ── PERMISSIONS CARD ── --}}
    <div class="form-card">
        <div class="form-card-header" style="justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="form-card-icon"><i class="fas fa-key"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">{{ trans('cruds.role.fields.permissions') }}</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Assign access permissions</p>
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="button" id="select-all"
                    style="font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-light); border:none; padding:5px 10px; border-radius:7px; cursor:pointer;">
                    All
                </button>
                <button type="button" id="deselect-all"
                    style="font-size:12px; font-weight:600; color:#64748B; background:#F8FAFC; border:1px solid #E2E8F0; padding:5px 10px; border-radius:7px; cursor:pointer;">
                    None
                </button>
            </div>
        </div>
        <div class="form-card-body">

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; max-height:280px; overflow-y:auto; padding-right:4px;">
                @foreach($permissions as $id => $permission)
                <label class="role-checkbox-item {{ in_array($id, old('permissions', [])) || $role->permissions->contains($id) ? 'checked' : '' }}" data-id="{{ $id }}">
                    <input type="checkbox" name="permissions[]" value="{{ $id }}"
                           class="role-checkbox"
                           {{ in_array($id, old('permissions', [])) || $role->permissions->contains($id) ? 'checked' : '' }}>
                    <div class="check-icon"></div>
                    <span style="font-size:13px; font-weight:500; color:#374151;">{{ $permission }}</span>
                </label>
                @endforeach
            </div>

            @if($errors->has('permissions'))
                <p class="field-error" style="margin-top:10px;"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('permissions') }}</p>
            @endif

            <div style="margin-top:14px; padding:10px 14px; background:#F8FAFC; border-radius:9px; border:1px solid #F1F5F9;">
                <p style="font-size:12px; color:#64748B; margin:0;">
                    <i class="fas fa-info-circle" style="color:var(--accent); margin-right:5px;"></i>
                    Permissions control what this role can access in the system.
                </p>
            </div>
        </div>
    </div>

</div>

{{-- ── ACTIONS ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; gap:12px; margin-top:24px; flex-wrap:wrap;">
    <div style="display:flex; align-items:center; gap:12px;">
        <button type="submit" class="btn-primary">
            <i class="fas fa-save"></i> {{ trans('global.save') }}
        </button>
        <a href="{{ route('admin.roles.index') }}" class="btn-ghost">
            {{ trans('global.cancel') }}
        </a>
    </div>
    @can('role_delete')
    <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline;"
          onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
        @method('DELETE') @csrf
        <button type="submit" class="btn-danger">
            <i class="fas fa-trash-alt"></i> Delete Role
        </button>
    </form>
    @endcan
</div>

</form>
@endsection

@section('scripts')
@parent
<script>
// Custom checkboxes
document.querySelectorAll('.role-checkbox-item').forEach(item => {
    item.addEventListener('click', function() {
        const cb = this.querySelector('input[type=checkbox]');
        cb.checked = !cb.checked;
        this.classList.toggle('checked', cb.checked);
    });
});

// Select / Deselect all
document.getElementById('select-all').addEventListener('click', () => {
    document.querySelectorAll('.role-checkbox-item').forEach(item => {
        item.querySelector('input').checked = true;
        item.classList.add('checked');
    });
});
document.getElementById('deselect-all').addEventListener('click', () => {
    document.querySelectorAll('.role-checkbox-item').forEach(item => {
        item.querySelector('input').checked = false;
        item.classList.remove('checked');
    });
});
</script>
@endsection
