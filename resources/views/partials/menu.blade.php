<aside id="sidebar">

    {{-- ── BRAND ── --}}
    <div style="padding: 0 16px; height: 60px; display:flex; align-items:center; justify-content:space-between; border-bottom: 1px solid rgba(255,255,255,.06);">
        <div style="display:flex; align-items:center; gap:10px;" class="brand-area">
            <div style="width:34px; height:34px; border-radius:9px; background:var(--accent); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <i class="fas fa-bolt" style="color:#fff; font-size:15px;"></i>
            </div>
            <span class="brand-text" style="font-size:15px; font-weight:700; color:#F1F5F9; letter-spacing:-.2px;">
                {{ trans('panel.site_title') }}
            </span>
        </div>
    </div>

    {{-- ── USER MINI CARD ── --}}
    <div class="user-info" style="margin: 12px 12px 4px; background:rgba(255,255,255,.05); border-radius:10px; padding:10px 12px; display:flex; align-items:center; gap:10px;">
        <div style="width:36px; height:36px; border-radius:9px; background:var(--accent); color:#fff; display:flex; align-items:center; justify-content:center; font-size:14px; font-weight:700; flex-shrink:0;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div style="min-width:0;">
            <p style="font-size:13px; font-weight:600; color:#E2E8F0; margin:0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ auth()->user()->name }}</p>
            <p style="font-size:11px; color:#475569; margin:1px 0 0;">Administrator</p>
        </div>
    </div>

    {{-- ── NAV ── --}}
    <nav style="flex:1; padding:8px 10px; overflow-y:auto; display:flex; flex-direction:column; gap:2px;">

        {{-- Section Label --}}
        <p style="font-size:10px; font-weight:700; color:#334155; text-transform:uppercase; letter-spacing:.08em; padding:10px 10px 4px; margin:0;" class="nav-label">Main</p>

        {{-- Dashboard --}}
        <a href="{{ route('admin.home') }}" data-tooltip="Dashboard"
           class="nav-link {{ request()->routeIs('admin.home') ? 'active' : '' }}">
            <i class="fas fa-chart-pie nav-icon" style="color:{{ request()->routeIs('admin.home') ? '#fff' : '#64748B' }};"></i>
            <span class="nav-label">{{ trans('global.dashboard') }}</span>
        </a>

        {{-- ── USER MANAGEMENT GROUP ── --}}
        @can('user_management_access')
        @php
        $umActive = request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*') || request()->is('admin/audit-logs*');
        @endphp
        <div x-data="{ open: {{ $umActive ? 'true' : 'false' }} }">

            <button @click="open = !open" data-tooltip="Users"
                class="nav-link {{ $umActive ? 'active' : '' }}"
                style="justify-content: space-between;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <i class="fas fa-users nav-icon" style="color:{{ $umActive ? '#fff' : '#64748B' }};"></i>
                    <span class="nav-label">{{ trans('cruds.userManagement.title') }}</span>
                </div>
                <i class="fas fa-chevron-right chevron" style="font-size:10px; color:#475569; transition:transform .2s;"
                   :style="open ? 'transform:rotate(90deg)' : ''"></i>
            </button>

            <div class="submenu" x-show="open"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1">

                @can('permission_access')
                <a href="{{ route('admin.permissions.index') }}"
                   class="sub-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                    <i class="fas fa-key" style="margin-right:7px; font-size:11px; color:#475569;"></i>
                    {{ trans('cruds.permission.title') }}
                </a>
                @endcan

                @can('role_access')
                <a href="{{ route('admin.roles.index') }}"
                   class="sub-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                    <i class="fas fa-shield-alt" style="margin-right:7px; font-size:11px; color:#475569;"></i>
                    {{ trans('cruds.role.title') }}
                </a>
                @endcan

                @can('user_access')
                <a href="{{ route('admin.users.index') }}"
                   class="sub-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle" style="margin-right:7px; font-size:11px; color:#475569;"></i>
                    {{ trans('cruds.user.title') }}
                </a>
                @endcan

                @can('audit_log_access')
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="sub-link {{ request()->is('admin/audit-logs*') ? 'active' : '' }}">
                    <i class="fas fa-history" style="margin-right:7px; font-size:11px; color:#475569;"></i>
                    {{ trans('cruds.auditLog.title') }}
                </a>
                @endcan

            </div>
        </div>
        @endcan

        {{-- ── DIVIDER ── --}}
        <div style="height:1px; background:rgba(255,255,255,.05); margin:6px 4px;"></div>
        <p style="font-size:10px; font-weight:700; color:#334155; text-transform:uppercase; letter-spacing:.08em; padding:4px 10px; margin:0;" class="nav-label">Account</p>

        {{-- Change Password --}}
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
        @can('profile_password_edit')
        <a href="{{ route('profile.password.edit') }}" data-tooltip="Password"
           class="nav-link {{ request()->is('profile/password*') ? 'active' : '' }}">
            <i class="fas fa-key nav-icon" style="color:{{ request()->is('profile/password*') ? '#fff' : '#64748B' }};"></i>
            <span class="nav-label">{{ trans('global.change_password') }}</span>
        </a>
        @endcan
        @endif

        {{-- Settings placeholder --}}
        <a href="#" data-tooltip="Settings"
           class="nav-link">
            <i class="fas fa-cog nav-icon" style="color:#64748B;"></i>
            <span class="nav-label">Settings</span>
        </a>

    </nav>

    {{-- ── LOGOUT ── --}}
    <div style="padding:10px; border-top:1px solid rgba(255,255,255,.06);">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
           data-tooltip="Logout"
           class="nav-link"
           style="color:#64748B;"
           onmouseover="this.style.background='rgba(239,68,68,.15)'; this.style.color='#FCA5A5';"
           onmouseout="this.style.background='transparent'; this.style.color='#64748B';">
            <i class="fas fa-sign-out-alt nav-icon"></i>
            <span class="nav-label">{{ trans('global.logout') }}</span>
        </a>
    </div>

</aside>