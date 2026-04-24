@extends('layouts.admin')
@section('page-title', trans('global.add') . ' ' . trans('cruds.user.title_singular'))

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

.eye-toggle {
    position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
    color: #9CA3AF; font-size: 13px; cursor: pointer; background: none; border: none;
    padding: 0; line-height: 1;
}

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
</style>
@endsection

@section('content')

{{-- ── HEADER ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px;">
            <a href="{{ route('admin.users.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600;">
                ← {{ trans('global.back_to_list') }}
            </a>
        </div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">
            {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
        </h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Fill in the details to create a new user account</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.users.store') }}">
@csrf

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- ── USER INFO CARD ── --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-user"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">User Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Basic account details</p>
            </div>
        </div>
        <div class="form-card-body">

            {{-- Name --}}
            <div class="field-group">
                <label class="field-label" for="name">
                    {{ trans('cruds.user.fields.name') }} <span class="req">*</span>
                </label>
                <div class="input-icon-wrap">
                    <i class="fas fa-user icon"></i>
                    <input type="text" name="name" id="name"
                           value="{{ old('name') }}" required
                           placeholder="Enter full name"
                           class="field-input {{ $errors->has('name') ? 'error' : '' }}">
                </div>
                @if($errors->has('name'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('name') }}</p>
                @elseif(trans('cruds.user.fields.name_helper'))
                    <p class="field-hint">{{ trans('cruds.user.fields.name_helper') }}</p>
                @endif
            </div>

            {{-- Email --}}
            <div class="field-group">
                <label class="field-label" for="email">
                    {{ trans('cruds.user.fields.email') }} <span class="req">*</span>
                </label>
                <div class="input-icon-wrap">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" name="email" id="email"
                           value="{{ old('email') }}" required
                           placeholder="user@example.com"
                           class="field-input {{ $errors->has('email') ? 'error' : '' }}">
                </div>
                @if($errors->has('email'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}</p>
                @endif
            </div>

            {{-- Password --}}
            <div class="field-group">
                <label class="field-label" for="password">
                    {{ trans('cruds.user.fields.password') }} <span class="req">*</span>
                </label>
                <div class="input-icon-wrap" style="position:relative;">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="password" id="password" required
                           placeholder="Create a strong password"
                           class="field-input {{ $errors->has('password') ? 'error' : '' }}"
                           style="padding-right:40px;">
                    <button type="button" class="eye-toggle" onclick="togglePass('password', this)">
                        <i class="fas fa-eye" id="eye-password"></i>
                    </button>
                </div>
                @if($errors->has('password'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('password') }}</p>
                @else
                    <p class="field-hint"><i class="fas fa-shield-alt" style="margin-right:4px;"></i>Min. 8 characters recommended</p>
                @endif
            </div>

            {{-- Password strength --}}
            <div style="margin-top:-10px;">
                <div style="display:flex; gap:4px; margin-bottom:4px;" id="strength-bars">
                    <div style="flex:1; height:4px; border-radius:2px; background:#E2E8F0;" id="bar-1"></div>
                    <div style="flex:1; height:4px; border-radius:2px; background:#E2E8F0;" id="bar-2"></div>
                    <div style="flex:1; height:4px; border-radius:2px; background:#E2E8F0;" id="bar-3"></div>
                    <div style="flex:1; height:4px; border-radius:2px; background:#E2E8F0;" id="bar-4"></div>
                </div>
                <p id="strength-text" style="font-size:11px; color:#94A3B8; margin:0;"></p>
            </div>

        </div>
    </div>

    {{-- ── ROLES CARD ── --}}
    <div class="form-card">
        <div class="form-card-header" style="justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="form-card-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">{{ trans('cruds.user.fields.roles') }}</p>
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
                @foreach($roles as $id => $role)
                <label class="role-checkbox-item {{ in_array($id, old('roles', [])) ? 'checked' : '' }}" data-id="{{ $id }}">
                    <input type="checkbox" name="roles[]" value="{{ $id }}"
                           class="role-checkbox"
                           {{ in_array($id, old('roles', [])) ? 'checked' : '' }}>
                    <div class="check-icon"></div>
                    <span style="font-size:13px; font-weight:500; color:#374151;">{{ $role }}</span>
                </label>
                @endforeach
            </div>

            @if($errors->has('roles'))
                <p class="field-error" style="margin-top:10px;"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('roles') }}</p>
            @endif

            <div style="margin-top:14px; padding:10px 14px; background:#F8FAFC; border-radius:9px; border:1px solid #F1F5F9;">
                <p style="font-size:12px; color:#64748B; margin:0;">
                    <i class="fas fa-info-circle" style="color:var(--accent); margin-right:5px;"></i>
                    Roles control what this user can see and do in the admin panel.
                </p>
            </div>
        </div>
    </div>

</div>

{{-- ── ACTIONS ── --}}
<div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
    <button type="submit" class="btn-primary">
        <i class="fas fa-check"></i> {{ trans('global.save') }}
    </button>
    <a href="{{ route('admin.users.index') }}" class="btn-ghost">
        {{ trans('global.cancel') }}
    </a>
</div>

</form>
@endsection

@section('scripts')
@parent
<script>
// Toggle password visibility
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

// Password strength
document.getElementById('password').addEventListener('input', function() {
    const val = this.value;
    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#EF4444','#F59E0B','#10B981','#4F46E5'];
    const labels = ['Weak','Fair','Good','Strong'];
    const bars   = ['bar-1','bar-2','bar-3','bar-4'];

    bars.forEach((b, i) => {
        const el = document.getElementById(b);
        el.style.background = i < score ? colors[score - 1] : '#E2E8F0';
    });

    const t = document.getElementById('strength-text');
    if (val.length === 0) { t.textContent = ''; }
    else { t.textContent = labels[score - 1] || 'Weak'; t.style.color = colors[score - 1]; }
});

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