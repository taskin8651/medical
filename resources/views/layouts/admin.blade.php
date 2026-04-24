<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('panel.site_title') }}</title>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js --}}
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

    {{-- Datatables --}}
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="//cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css">

    {{-- Select2 / Dropzone --}}
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css">

    <style>
        :root {
            --accent:       #4F46E5;
            --accent-light: #EEF2FF;
            --accent-dark:  #3730A3;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }

        /* ── Page bg ── */
        body { background: #F1F5F9; }

        /* ── Sidebar ── */
        #sidebar {
            width: 260px;
            min-width: 260px;
            background: #0F172A;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: width .3s ease, min-width .3s ease;
            position: relative;
            z-index: 40;
        }
        #sidebar.collapsed { width: 68px; min-width: 68px; }
        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .brand-text,
        #sidebar.collapsed .user-info,
        #sidebar.collapsed .chevron,
        #sidebar.collapsed .submenu { display: none !important; }
        #sidebar.collapsed .nav-link { justify-content: center; padding: 10px; }
        #sidebar.collapsed .nav-icon { margin: 0; }

        .nav-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 9px;
            font-size: 13.5px; font-weight: 500; color: #94A3B8;
            text-decoration: none; transition: all .2s;
            cursor: pointer; border: none; background: none; width: 100%;
        }
        .nav-link:hover { background: rgba(255,255,255,.06); color: #E2E8F0; }
        .nav-link.active { background: var(--accent); color: #fff; }
        .nav-link.active .nav-icon { color: #fff !important; }
        .nav-icon { font-size: 15px; width: 20px; text-align: center; flex-shrink: 0; }

        .submenu { margin-left: 30px; border-left: 1px solid rgba(255,255,255,.08); padding-left: 10px; margin-top: 2px; }
        .sub-link {
            display: block; padding: 7px 10px; border-radius: 7px;
            font-size: 13px; color: #64748B; text-decoration: none; transition: all .2s;
        }
        .sub-link:hover { background: rgba(255,255,255,.06); color: #CBD5E1; }
        .sub-link.active { color: #A5B4FC; font-weight: 600; }

        /* ── Mobile overlay ── */
        #sidebar-overlay {
            display: none; position: fixed; inset: 0; background: rgba(0,0,0,.5);
            z-index: 39; backdrop-filter: blur(2px);
        }

        /* ── Header ── */
        #main-header {
            background: #fff;
            border-bottom: 1px solid #E2E8F0;
            padding: 0 24px;
            height: 60px;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 30;
        }

        /* ── Breadcrumb ── */
        .breadcrumb-item { font-size: 13px; color: #94A3B8; }
        .breadcrumb-item.active { color: #1E293B; font-weight: 600; }

        /* ── Alert ── */
        .alert-success {
            background: #F0FDF4; border: 1px solid #BBF7D0; color: #15803D;
            padding: 12px 16px; border-radius: 10px; font-size: 13.5px;
            display: flex; align-items: center; gap: 10px; margin-bottom: 16px;
        }
        .alert-error {
            background: #FFF1F2; border: 1px solid #FECDD3; color: #BE123C;
            padding: 12px 16px; border-radius: 10px; font-size: 13.5px; margin-bottom: 16px;
        }

        /* ── Tooltip (collapsed sidebar) ── */
        #sidebar.collapsed .nav-link { position: relative; }
        #sidebar.collapsed .nav-link:hover::after {
            content: attr(data-tooltip);
            position: absolute; left: 100%; top: 50%; transform: translateY(-50%);
            background: #1E293B; color: #F1F5F9; font-size: 12px; font-weight: 500;
            padding: 5px 10px; border-radius: 6px; white-space: nowrap;
            margin-left: 10px; pointer-events: none; z-index: 100;
        }

        /* ── Notification dot ── */
        .notif-dot {
            width: 8px; height: 8px; border-radius: 50%; background: #EF4444;
            position: absolute; top: 3px; right: 3px; border: 2px solid #fff;
        }

        @media (max-width: 1024px) {
            #sidebar { position: fixed; left: -260px; top: 0; height: 100vh; transition: left .3s ease; }
            #sidebar.mobile-open { left: 0; }
            #sidebar-overlay { display: none; }
            #sidebar.mobile-open ~ #sidebar-overlay { display: block; }
        }
    </style>

    @yield('styles')
</head>

<body>

<div style="display:flex; min-height:100vh;">

    {{-- ══════════════ SIDEBAR ══════════════ --}}
    @include('partials.menu')
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    {{-- ══════════════ MAIN AREA ══════════════ --}}
    <div id="main-content" style="flex:1; display:flex; flex-direction:column; min-width:0; overflow:hidden;">

        {{-- ── HEADER ── --}}
        <header id="main-header">

            {{-- Left --}}
            <div style="display:flex; align-items:center; gap:16px;">

               {{-- Mobile hamburger --}}
<button
    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 bg-white text-gray-600 text-[15px]"
    onclick="toggleMobileSidebar()">
    <i class="fas fa-bars"></i>
</button>

{{-- Desktop collapse --}}
<button
    class="hidden lg:flex items-center justify-center w-9 h-9 rounded-lg border border-gray-200 bg-white text-gray-600 text-[13px]"
    id="sidebar-toggle"
    onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
</button>

                {{-- Breadcrumb --}}
                <div style="display:flex; align-items:center; gap:6px;" class="hidden sm:flex">
                    <span class="breadcrumb-item">{{ trans('panel.site_title') }}</span>
                    <span style="color:#CBD5E1; font-size:12px;">/</span>
                    <span class="breadcrumb-item active">@yield('page-title', 'Dashboard')</span>
                </div>
            </div>

            {{-- Right --}}
            <div style="display:flex; align-items:center; gap:8px;">

                {{-- Search --}}
                <div class="hidden md:flex" style="position:relative; margin-right:4px;">
                    <input type="text" placeholder="Search..."
                        style="padding: 7px 14px 7px 36px; border-radius:9px; border:1px solid #E2E8F0; font-size:13px; background:#F8FAFC; width:200px; outline:none; color:#374151;"
                        onfocus="this.style.borderColor='var(--accent)'; this.style.background='#fff';"
                        onblur="this.style.borderColor='#E2E8F0'; this.style.background='#F8FAFC';">
                    <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:#9CA3AF; font-size:12px; pointer-events:none;"></i>
                </div>

                {{-- Language --}}
                @if(count(config('panel.available_languages', [])) > 1)
                <div x-data="{open:false}" style="position:relative;">
                    <button @click="open = !open"
                        style="display:flex; align-items:center; gap:6px; padding:7px 12px; border-radius:9px; border:1px solid #E2E8F0; background:#fff; font-size:12px; font-weight:600; cursor:pointer; color:#374151; text-transform:uppercase;">
                        {{ app()->getLocale() }}
                        <i class="fas fa-chevron-down" style="font-size:10px;"></i>
                    </button>
                    <div x-show="open" x-transition @click.outside="open=false"
                        style="position:absolute; right:0; top:calc(100% + 6px); background:#fff; border:1px solid #E2E8F0; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,.1); min-width:150px; z-index:100; overflow:hidden;">
                        @foreach(config('panel.available_languages') as $langLocale => $langName)
                        <a href="{{ url()->current() }}?change_language={{ $langLocale }}"
                            style="display:block; padding:9px 14px; font-size:13px; color:#374151; text-decoration:none; transition:background .15s;"
                            onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                            {{ strtoupper($langLocale) }} – {{ $langName }}
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Notifications --}}
                <button style="position:relative; width:38px; height:38px; border-radius:9px; border:1px solid #E2E8F0; background:#fff; cursor:pointer; font-size:15px; color:#475569; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-bell"></i>
                    <span class="notif-dot"></span>
                </button>

                {{-- User dropdown --}}
                <div x-data="{open:false}" style="position:relative;">
                    <button @click="open = !open"
                        style="display:flex; align-items:center; gap:10px; padding:5px 10px 5px 5px; border-radius:10px; border:1px solid #E2E8F0; background:#fff; cursor:pointer;">

                        <div style="width:32px; height:32px; border-radius:8px; background:var(--accent); color:#fff; display:flex; align-items:center; justify-content:center; font-size:13px; font-weight:700; flex-shrink:0;">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>

                        <div class="hidden sm:block" style="text-align:left;">
                            <p style="font-size:13px; font-weight:600; color:#1E293B; margin:0; line-height:1.2;">{{ auth()->user()->name }}</p>
                            <p style="font-size:11px; color:#94A3B8; margin:0;">Administrator</p>
                        </div>

                        <i class="fas fa-chevron-down hidden sm:block" style="font-size:10px; color:#94A3B8;"></i>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.outside="open=false"
                         style="position:absolute; right:0; top:calc(100% + 8px); width:220px; background:#fff; border:1px solid #E2E8F0; border-radius:12px; box-shadow:0 8px 30px rgba(0,0,0,.1); z-index:100; overflow:hidden;">

                        <div style="padding:12px 14px; background:#F8FAFC; border-bottom:1px solid #F1F5F9;">
                            <p style="font-size:13.5px; font-weight:700; color:#1E293B; margin:0;">{{ auth()->user()->name }}</p>
                            <p style="font-size:12px; color:#94A3B8; margin:2px 0 0;">{{ auth()->user()->email }}</p>
                        </div>

                        <div style="padding:6px;">
                            <a href="{{ route('profile.password.edit') }}"
                                style="display:flex; align-items:center; gap:10px; padding:9px 10px; border-radius:8px; font-size:13px; color:#374151; text-decoration:none; transition:background .15s;"
                                onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-key" style="color:#94A3B8; width:16px; text-align:center;"></i>
                                Change Password
                            </a>

                            <div style="height:1px; background:#F1F5F9; margin:4px 0;"></div>

                            <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();"
                                style="display:flex; align-items:center; gap:10px; padding:9px 10px; border-radius:8px; font-size:13px; color:#EF4444; text-decoration:none; transition:background .15s;"
                                onmouseover="this.style.background='#FFF1F2'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-sign-out-alt" style="width:16px; text-align:center;"></i>
                                Sign Out
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        {{-- ── CONTENT ── --}}
        <main style="flex:1; padding:24px; overflow-y:auto;">

            @if(session('message'))
            <div class="alert-success">
                <i class="fas fa-check-circle" style="font-size:16px; flex-shrink:0;"></i>
                {{ session('message') }}
            </div>
            @endif

            @if($errors->count() > 0)
            <div class="alert-error">
                <div style="display:flex; align-items:center; gap:8px; margin-bottom:6px;">
                    <i class="fas fa-exclamation-circle" style="font-size:15px;"></i>
                    <strong style="font-size:13.5px;">Please fix the following errors:</strong>
                </div>
                <ul style="margin:0; padding-left:20px;">
                    @foreach($errors->all() as $error)
                    <li style="font-size:13px; margin-top:3px;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @yield('content')

        </main>

    </div>
</div>

{{-- Logout Form --}}
<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display:none;">
    @csrf
</form>

{{-- ══ JS ══ --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="//cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<script src="//cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>

<script>
$(function () {
    $.extend(true, $.fn.dataTable.defaults, { scrollX: true, pageLength: 25 });
});

// ── Sidebar collapse (desktop) ──
function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    sb.classList.toggle('collapsed');
    localStorage.setItem('sidebar_collapsed', sb.classList.contains('collapsed') ? '1' : '0');
}

// ── Sidebar mobile ──
function toggleMobileSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('sidebar-overlay');
    sb.classList.toggle('mobile-open');
    ov.style.display = sb.classList.contains('mobile-open') ? 'block' : 'none';
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('mobile-open');
    document.getElementById('sidebar-overlay').style.display = 'none';
}

// ── Restore collapse state ──
(function() {
    if (localStorage.getItem('sidebar_collapsed') === '1') {
        const sb = document.getElementById('sidebar');
        if (sb) sb.classList.add('collapsed');
    }
    // Restore accent color
    const t = localStorage.getItem('dash_theme');
    if (t) {
        try {
            const obj = JSON.parse(t);
            if (obj.accent) document.documentElement.style.setProperty('--accent', obj.accent.trim());
        } catch(e) {}
    }
})();
</script>

@yield('scripts')
</body>
</html>