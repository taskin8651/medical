@extends('layouts.admin')
@section('page-title', trans('global.edit') . ' ' . trans('cruds.user.title_singular'))

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
.field-label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px; }
.field-label .req { color:#EF4444; margin-left:2px; }
.field-input {
    width:100%; padding:9px 13px;
    border:1.5px solid #E2E8F0; border-radius:9px;
    font-size:13.5px; color:#1E293B; outline:none;
    transition:border-color .2s, box-shadow .2s; background:#fff; font-family:inherit;
}
.field-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--accent) 15%, transparent);
}
.field-input.error { border-color:#EF4444; }
.field-hint  { font-size:12px; color:#94A3B8; margin-top:5px; }
.field-error { font-size:12px; color:#EF4444; margin-top:5px; display:flex; align-items:center; gap:4px; }
.field-group { margin-bottom:20px; }
.field-group:last-child { margin-bottom:0; }
.input-icon-wrap { position:relative; }
.input-icon-wrap .icon { position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#9CA3AF; font-size:13px; pointer-events:none; }
.input-icon-wrap .field-input { padding-left:36px; }
.eye-toggle { position:absolute; right:12px; top:50%; transform:translateY(-50%); color:#9CA3AF; font-size:13px; cursor:pointer; background:none; border:none; padding:0; }
.role-checkbox-item {
    display:flex; align-items:center; gap:10px;
    padding:10px 12px; border-radius:9px; border:1.5px solid #E2E8F0;
    cursor:pointer; transition:all .2s; background:#fff;
}
.role-checkbox-item:hover { border-color:var(--accent); background:var(--accent-light); }
.role-checkbox-item input[type=checkbox] { display:none; }
.role-checkbox-item .check-icon {
    width:20px; height:20px; border-radius:6px; border:2px solid #CBD5E1;
    display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:all .2s;
}
.role-checkbox-item.checked { border-color:var(--accent); background:var(--accent-light); }
.role-checkbox-item.checked .check-icon { background:var(--accent); border-color:var(--accent); }
.role-checkbox-item.checked .check-icon::after {
    content:''; width:10px; height:6px;
    border-left:2px solid #fff; border-bottom:2px solid #fff;
    transform:rotate(-45deg) translateY(-1px);
}
.btn-primary { display:inline-flex; align-items:center; gap:8px; padding:10px 22px; border-radius:10px; background:var(--accent); color:#fff; font-size:13.5px; font-weight:600; border:none; cursor:pointer; transition:opacity .2s; font-family:inherit; }
.btn-primary:hover { opacity:.88; }
.btn-ghost { display:inline-flex; align-items:center; gap:6px; padding:10px 18px; border-radius:10px; background:#F8FAFC; color:#475569; font-size:13.5px; font-weight:600; border:1.5px solid #E2E8F0; cursor:pointer; text-decoration:none; transition:background .15s; font-family:inherit; }
.btn-ghost:hover { background:#F1F5F9; }
</style>
@endsection

@section('content')

{{-- ── HEADER ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <a href="{{ route('admin.users.index') }}" style="font-size:13px; color:var(--accent); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:5px; margin-bottom:6px;">
            ← {{ trans('global.back_to_list') }}
        </a>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">
            {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
        </h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">Update account details and role assignments</p>
    </div>

    {{-- User identity pill --}}
    <div style="display:flex; align-items:center; gap:10px; padding:10px 14px; background:#fff; border:1px solid #E2E8F0; border-radius:11px;">
        @php $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6']; @endphp
        <div style="width:34px; height:34px; border-radius:9px; background:{{ $colors[$user->id % count($colors)] }}; color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700;">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <p style="font-size:13px; font-weight:700; color:#0F172A; margin:0;">{{ $user->name }}</p>
            <p style="font-size:11px; color:#94A3B8; margin:0;">ID #{{ $user->id }}</p>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.users.update', $user->id) }}">
@method('PUT')
@csrf

<div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">

    {{-- ── USER INFO ── --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-user-edit"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">User Information</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Update account credentials</p>
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
                           value="{{ old('name', $user->name) }}" required
                           class="field-input {{ $errors->has('name') ? 'error' : '' }}">
                </div>
                @if($errors->has('name'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('name') }}</p>
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
                           value="{{ old('email', $user->email) }}" required
                           class="field-input {{ $errors->has('email') ? 'error' : '' }}">
                </div>
                @if($errors->has('email'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('email') }}</p>
                @endif
            </div>

            {{-- Phone --}}
            <div class="field-group">
                <label class="field-label" for="phone">Phone</label>
                <div class="input-icon-wrap">
                    <i class="fas fa-phone icon"></i>
                    <input type="text" name="phone" id="phone"
                           value="{{ old('phone', $user->phone) }}"
                           class="field-input {{ $errors->has('phone') ? 'error' : '' }}">
                </div>
                @if($errors->has('phone'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('phone') }}</p>
                @endif
            </div>

            {{-- Password --}}
            <div class="field-group">
                <label class="field-label" for="password">
                    {{ trans('cruds.user.fields.password') }}
                    <span style="font-size:11px; font-weight:400; color:#94A3B8;">(optional)</span>
                </label>
                <div class="input-icon-wrap" style="position:relative;">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="password" id="password"
                           placeholder="Leave blank to keep current password"
                           class="field-input {{ $errors->has('password') ? 'error' : '' }}"
                           style="padding-right:40px;">
                    <button type="button" class="eye-toggle" onclick="togglePass('password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @if($errors->has('password'))
                    <p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('password') }}</p>
                @else
                    <p class="field-hint">{{ trans('cruds.user.fields.password_helper') }}</p>
                @endif
            </div>

            {{-- Account meta --}}
            <div style="padding:12px 14px; background:#F8FAFC; border-radius:10px; border:1px solid #F1F5F9;">
                <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 8px;">Account Info</p>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                    <div>
                        <p style="font-size:11px; color:#94A3B8; margin:0;">Joined</p>
                        <p style="font-size:13px; font-weight:600; color:#374151; margin:2px 0 0;">{{ optional($user->created_at)->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p style="font-size:11px; color:#94A3B8; margin:0;">Email Status</p>
                        @if($user->email_verified_at)
                            <p style="font-size:13px; font-weight:600; color:#10B981; margin:2px 0 0;"><i class="fas fa-check-circle" style="margin-right:3px;"></i>Verified</p>
                        @else
                            <p style="font-size:13px; font-weight:600; color:#F59E0B; margin:2px 0 0;"><i class="fas fa-clock" style="margin-right:3px;"></i>Pending</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- ── ROLES ── --}}
    <div class="form-card">
        <div class="form-card-header" style="justify-content:space-between;">
            <div style="display:flex; align-items:center; gap:10px;">
                <div class="form-card-icon"><i class="fas fa-shield-alt"></i></div>
                <div>
                    <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">{{ trans('cruds.user.fields.roles') }}</p>
                    <p style="font-size:12px; color:#94A3B8; margin:0;">Update permissions</p>
                </div>
            </div>
            <div style="display:flex; gap:8px;">
                <button type="button" id="select-all"
                    style="font-size:12px; font-weight:600; color:var(--accent); background:var(--accent-light); border:none; padding:5px 10px; border-radius:7px; cursor:pointer;">All</button>
                <button type="button" id="deselect-all"
                    style="font-size:12px; font-weight:600; color:#64748B; background:#F8FAFC; border:1px solid #E2E8F0; padding:5px 10px; border-radius:7px; cursor:pointer;">None</button>
            </div>
        </div>
        <div class="form-card-body">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; max-height:280px; overflow-y:auto; padding-right:4px;">
                @foreach($roles as $id => $role)
                @php $isChecked = in_array($id, old('roles', [])) || $user->roles->contains($id); @endphp
                <label class="role-checkbox-item {{ $isChecked ? 'checked' : '' }}" data-id="{{ $id }}">
                    <input type="checkbox" name="roles[]" value="{{ $id }}"
                           class="role-checkbox" {{ $isChecked ? 'checked' : '' }}>
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
                    Changes to roles take effect immediately after saving.
                </p>
            </div>
        </div>
    </div>

    {{-- WHOLESALE DETAILS --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-store"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Wholesale Details</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Business and license information</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="business_name">Business / Firm Name</label>
                <div class="input-icon-wrap">
                    <i class="fas fa-building icon"></i>
                    <input type="text" name="business_name" id="business_name" value="{{ old('business_name', $user->business_name) }}" class="field-input {{ $errors->has('business_name') ? 'error' : '' }}">
                </div>
                @if($errors->has('business_name'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('business_name') }}</p>@endif
            </div>

            <div class="field-group">
                <label class="field-label" for="business_type">Business Type</label>
                <select name="business_type" id="business_type" class="field-input {{ $errors->has('business_type') ? 'error' : '' }}">
                    <option value="">Select business type</option>
                    @foreach(['Retail Pharmacy', 'Hospital / Clinic', 'Distributor', 'Wholesaler', 'Medical Store', 'Other'] as $type)
                        <option value="{{ $type }}" {{ old('business_type', $user->business_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                @if($errors->has('business_type'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('business_type') }}</p>@endif
            </div>

            <div class="field-group">
                <label class="field-label" for="gst_no">GST Number</label>
                <div class="input-icon-wrap">
                    <i class="fas fa-file-invoice icon"></i>
                    <input type="text" name="gst_no" id="gst_no" value="{{ old('gst_no', $user->gst_no) }}" class="field-input {{ $errors->has('gst_no') ? 'error' : '' }}">
                </div>
                @if($errors->has('gst_no'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('gst_no') }}</p>@endif
            </div>

            <div class="field-group">
                <label class="field-label" for="drug_license_no">Drug License Number</label>
                <div class="input-icon-wrap">
                    <i class="fas fa-id-card icon"></i>
                    <input type="text" name="drug_license_no" id="drug_license_no" value="{{ old('drug_license_no', $user->drug_license_no) }}" class="field-input {{ $errors->has('drug_license_no') ? 'error' : '' }}">
                </div>
                @if($errors->has('drug_license_no'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('drug_license_no') }}</p>@endif
            </div>

            <div class="field-group">
                <label class="field-label" for="approval_status">Approval Status</label>
                <select name="approval_status" id="approval_status" class="field-input {{ $errors->has('approval_status') ? 'error' : '' }}">
                    @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label)
                        <option value="{{ $value }}" {{ old('approval_status', $user->approval_status ?? 'pending') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('approval_status'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('approval_status') }}</p>@endif
            </div>
        </div>
    </div>

    {{-- BILLING ADDRESS --}}
    <div class="form-card">
        <div class="form-card-header">
            <div class="form-card-icon"><i class="fas fa-location-dot"></i></div>
            <div>
                <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">Billing Address</p>
                <p style="font-size:12px; color:#94A3B8; margin:0;">Customer business address</p>
            </div>
        </div>
        <div class="form-card-body">
            <div class="field-group">
                <label class="field-label" for="address">Address</label>
                <textarea name="address" id="address" rows="3" class="field-input {{ $errors->has('address') ? 'error' : '' }}" style="padding-left:13px;">{{ old('address', $user->address) }}</textarea>
                @if($errors->has('address'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('address') }}</p>@endif
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div class="field-group">
                    <label class="field-label" for="city">City</label>
                    <input type="text" name="city" id="city" value="{{ old('city', $user->city) }}" class="field-input {{ $errors->has('city') ? 'error' : '' }}">
                    @if($errors->has('city'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('city') }}</p>@endif
                </div>
                <div class="field-group">
                    <label class="field-label" for="state">State</label>
                    <input type="text" name="state" id="state" value="{{ old('state', $user->state) }}" class="field-input {{ $errors->has('state') ? 'error' : '' }}">
                    @if($errors->has('state'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('state') }}</p>@endif
                </div>
                <div class="field-group">
                    <label class="field-label" for="pincode">Pincode</label>
                    <input type="text" name="pincode" id="pincode" value="{{ old('pincode', $user->pincode) }}" class="field-input {{ $errors->has('pincode') ? 'error' : '' }}">
                    @if($errors->has('pincode'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('pincode') }}</p>@endif
                </div>
                <div class="field-group">
                    <label class="field-label" for="country">Country</label>
                    <input type="text" name="country" id="country" value="{{ old('country', $user->country ?? 'India') }}" class="field-input {{ $errors->has('country') ? 'error' : '' }}">
                    @if($errors->has('country'))<p class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('country') }}</p>@endif
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ── ACTIONS ── --}}
<div style="display:flex; align-items:center; gap:12px; margin-top:24px;">
    <button type="submit" class="btn-primary">
        <i class="fas fa-save"></i> {{ trans('global.save') }}
    </button>
    <a href="{{ route('admin.users.index') }}" class="btn-ghost">{{ trans('global.cancel') }}</a>

    </form>

    @can('user_delete')
    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="margin-left:auto;"
          onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
        @method('DELETE') @csrf
        <button type="submit"
            style="display:inline-flex; align-items:center; gap:7px; padding:10px 18px; border-radius:10px; background:#FFF1F2; color:#BE123C; font-size:13px; font-weight:600; border:1.5px solid #FECDD3; cursor:pointer; font-family:inherit; transition:background .15s;"
            onmouseover="this.style.background='#FFE4E6'" onmouseout="this.style.background='#FFF1F2'">
            <i class="fas fa-trash-alt" style="font-size:12px;"></i> Delete User
        </button>
    </form>
    @endcan
</div>

@endsection

@section('scripts')
@parent
<script>
function togglePass(id, btn) {
    const input = document.getElementById(id);
    const icon  = btn.querySelector('i');
    input.type = input.type === 'password' ? 'text' : 'password';
    icon.classList.toggle('fa-eye'); icon.classList.toggle('fa-eye-slash');
}
document.querySelectorAll('.role-checkbox-item').forEach(item => {
    item.addEventListener('click', function() {
        const cb = this.querySelector('input[type=checkbox]');
        cb.checked = !cb.checked;
        this.classList.toggle('checked', cb.checked);
    });
});
document.getElementById('select-all').addEventListener('click', () => {
    document.querySelectorAll('.role-checkbox-item').forEach(item => {
        item.querySelector('input').checked = true; item.classList.add('checked');
    });
});
document.getElementById('deselect-all').addEventListener('click', () => {
    document.querySelectorAll('.role-checkbox-item').forEach(item => {
        item.querySelector('input').checked = false; item.classList.remove('checked');
    });
});
</script>
@endsection
