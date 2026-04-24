@extends('layouts.admin')
@section('page-title', trans('global.add') . ' ' . trans('cruds.permission.title_singular'))

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
            <a href="{{ route('admin.permissions.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600;">
                ← {{ trans('global.back_to_list') }}
            </a>
        </div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">
            {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
        </h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Create a new permission for system access control</p>
    </div>
</div>

<form method="POST" action="{{ route('admin.permissions.store') }}">
@csrf

<div style="max-width:600px;">

    {{-- ── PERMISSION INFO CARD ── --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-key"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Permission Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Define access control details</p>
            </div>
        </div>
        <div class="form-card-body">

            {{-- Title --}}
            <div class="field-group">
                <label class="field-label" for="title">
                    {{ trans('cruds.permission.fields.title') }} <span class="req">*</span>
                </label>
                <div class="input-icon-wrap">
                    <i class="fas fa-tag icon"></i>
                    <input type="text" name="title" id="title"
                           value="{{ old('title') }}" required
                           placeholder="Enter permission title"
                           class="field-input {{ $errors->has('title') ? 'error' : '' }}">
                </div>
                @if($errors->has('title'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('title') }}</p>
                @elseif(trans('cruds.permission.fields.title_helper'))
                    <p class="field-hint">{{ trans('cruds.permission.fields.title_helper') }}</p>
                @endif
            </div>

        </div>
    </div>

</div>

{{-- ── ACTIONS ── --}}
<div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
    <button type="submit" class="btn-primary">
        <i class="fas fa-check"></i> {{ trans('global.save') }}
    </button>
    <a href="{{ route('admin.permissions.index') }}" class="btn-ghost">
        {{ trans('global.cancel') }}
    </a>
</div>

</form>
@endsection
