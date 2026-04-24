@extends('layouts.admin')
@section('page-title', trans('cruds.user.title'))

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
.avatar-circle {
    width: 36px; height: 36px; border-radius: 9px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700; color: #fff;
    background: var(--accent); flex-shrink: 0;
}
.role-tag {
    display: inline-flex; align-items: center;
    padding: 3px 9px; border-radius: 6px;
    font-size: 11px; font-weight: 600;
    background: var(--accent-light); color: var(--accent);
    border: 1px solid;
    border-color: color-mix(in srgb, var(--accent) 20%, transparent);
}
.status-dot {
    width: 7px; height: 7px; border-radius: 50%; display: inline-block;
}
/* DataTable overrides */
table.dataTable thead th {
    background: #F8FAFC !important; color: #64748B !important;
    font-size: 11px !important; font-weight: 700 !important;
    text-transform: uppercase; letter-spacing: .06em;
    padding: 12px 16px !important; border-bottom: 1px solid #E2E8F0 !important;
    white-space: nowrap;
}
table.dataTable tbody td { padding: 13px 16px !important; border-bottom: 1px solid #F1F5F9 !important; font-size: 13.5px; }
table.dataTable tbody tr:hover td { background: #F8FAFC !important; }
table.dataTable tbody tr:last-child td { border-bottom: none !important; }
.dataTables_wrapper .dataTables_filter input {
    padding: 7px 12px 7px 34px !important; border-radius: 9px !important;
    border: 1px solid #E2E8F0 !important; font-size: 13px !important;
    background: #F8FAFC url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%239CA3AF' stroke-width='2'%3E%3Ccircle cx='11' cy='11' r='8'/%3E%3Cpath d='m21 21-4.35-4.35'/%3E%3C/svg%3E") no-repeat 10px center !important;
    outline: none !important;
}
.dataTables_wrapper .dataTables_filter input:focus { border-color: var(--accent) !important; background-color: #fff !important; }
.dataTables_wrapper .dataTables_filter label { font-size: 13px; color: #64748B; }
.dataTables_wrapper .dataTables_length select {
    padding: 6px 10px; border-radius: 8px; border: 1px solid #E2E8F0;
    font-size: 13px; background: #F8FAFC; color: #374151; outline: none;
}
.dataTables_wrapper .dataTables_info { font-size: 12px; color: #94A3B8; padding-top: 10px; }
.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 7px !important; font-size: 12px !important; padding: 5px 10px !important;
}
.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: var(--accent) !important; color: #fff !important; border-color: var(--accent) !important;
}
.dt-buttons .btn { border-radius: 8px !important; font-size: 12px !important; padding: 6px 12px !important; }
.btn-danger { background: #FFF1F2 !important; color: #BE123C !important; border: 1.5px solid #FECDD3 !important; }
</style>
@endsection

@section('content')

{{-- ── PAGE HEADER ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px; flex-wrap:wrap; gap:12px;">
    <div>
        <h2 style="font-size:22px; font-weight:700; color:#0F172A; margin:0;">
            {{ trans('cruds.user.title') }}
        </h2>
        <p style="font-size:13px; color:#64748B; margin:4px 0 0;">
            Manage all application users and their assigned roles
        </p>
    </div>
    @can('user_create')
    <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <i class="fas fa-plus" style="font-size:11px;"></i>
        {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
    </a>
    @endcan
</div>

{{-- ── STATS ROW ── --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(160px,1fr)); gap:14px; margin-bottom:24px;">
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 6px;">Total Users</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $users->count() }}</p>
    </div>
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 6px;">Verified</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $users->whereNotNull('email_verified_at')->count() }}</p>
    </div>
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 6px;">Unverified</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $users->whereNull('email_verified_at')->count() }}</p>
    </div>
    <div style="background:#fff; border:1px solid #E2E8F0; border-radius:12px; padding:16px 18px;">
        <p style="font-size:11px; font-weight:700; color:#94A3B8; text-transform:uppercase; letter-spacing:.05em; margin:0 0 6px;">Added Today</p>
        <p style="font-size:24px; font-weight:700; color:#0F172A; margin:0;">{{ $users->where('created_at', '>=', now()->startOfDay())->count() }}</p>
    </div>
</div>

{{-- ── TABLE CARD ── --}}
<div class="page-card">
    <div style="padding:16px 20px; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
        <p style="font-size:14px; font-weight:700; color:#0F172A; margin:0;">All Users</p>
        <span style="font-size:12px; color:#94A3B8;">
            <i class="fas fa-info-circle" style="margin-right:4px;"></i>
            Select rows to use bulk actions
        </span>
    </div>

    <div style="overflow-x:auto; padding:4px 10px;">
        <table class="min-w-full datatable datatable-User" style="width:100%;">
            <thead>
                <tr>
                    <th style="width:40px;"></th>
                    <th>{{ trans('cruds.user.fields.id') }}</th>
                    <th>{{ trans('cruds.user.fields.name') }}</th>
                    <th>{{ trans('cruds.user.fields.email') }}</th>
                    <th>{{ trans('cruds.user.fields.email_verified_at') }}</th>
                    <th>{{ trans('cruds.user.fields.roles') }}</th>
                    <th style="text-align:right;">{{ trans('global.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr data-entry-id="{{ $user->id }}">
                    <td></td>

                    <td>
                        <span style="font-size:12px; font-weight:700; color:#94A3B8;">#{{ $user->id }}</span>
                    </td>

                    <td>
                        <div style="display:flex; align-items:center; gap:10px;">
                            @php
                                $colors = ['#4F46E5','#0EA5E9','#10B981','#F59E0B','#EF4444','#8B5CF6','#EC4899','#14B8A6'];
                                $color  = $colors[$user->id % count($colors)];
                            @endphp
                            <div class="avatar-circle" style="background:{{ $color }};">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p style="font-size:13.5px; font-weight:600; color:#0F172A; margin:0;">{{ $user->name }}</p>
                                <p style="font-size:11px; color:#94A3B8; margin:1px 0 0;">Member</p>
                            </div>
                        </div>
                    </td>

                    <td style="color:#475569;">{{ $user->email }}</td>

                    <td>
                        @if($user->email_verified_at)
                            <div style="display:flex; align-items:center; gap:6px;">
                                <span class="status-dot" style="background:#10B981;"></span>
                                <span style="font-size:12.5px; color:#374151;">{{ $user->email_verified_at->format('d M Y') }}</span>
                            </div>
                        @else
                            <div style="display:flex; align-items:center; gap:6px;">
                                <span class="status-dot" style="background:#F59E0B;"></span>
                                <span style="font-size:12.5px; color:#92400E;">Pending</span>
                            </div>
                        @endif
                    </td>

                    <td>
                        <div style="display:flex; flex-wrap:wrap; gap:4px;">
                            @forelse($user->roles as $role)
                                <span class="role-tag">{{ $role->title }}</span>
                            @empty
                                <span style="font-size:12px; color:#94A3B8;">—</span>
                            @endforelse
                        </div>
                    </td>

                    <td>
                        <div style="display:flex; align-items:center; justify-content:flex-end; gap:6px;">
                            @can('user_show')
                            <a href="{{ route('admin.users.show', $user->id) }}"
                               class="btn-outline"
                               style="border-color:#E2E8F0; color:#475569;"
                               onmouseover="this.style.background='#F8FAFC'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-eye" style="font-size:11px;"></i> View
                            </a>
                            @endcan
                            @can('user_edit')
                            <a href="{{ route('admin.users.edit', $user->id) }}"
                               class="btn-outline"
                               style="border-color:color-mix(in srgb, var(--accent) 40%, transparent); color:var(--accent);"
                               onmouseover="this.style.background='var(--accent-light)'" onmouseout="this.style.background='transparent'">
                                <i class="fas fa-pencil-alt" style="font-size:11px;"></i> Edit
                            </a>
                            @endcan
                            @can('user_delete')
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('{{ trans('global.areYouSure') }}')">
                                @method('DELETE') @csrf
                                <button type="submit" class="btn-outline"
                                    style="border-color:#FECDD3; color:#BE123C; background:transparent;"
                                    onmouseover="this.style.background='#FFF1F2'" onmouseout="this.style.background='transparent'">
                                    <i class="fas fa-trash-alt" style="font-size:11px;"></i> Delete
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
$(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);

    @can('user_delete')
    dtButtons.push({
        text: '<i class="fas fa-trash-alt" style="margin-right:5px;"></i> {{ trans('global.datatables.delete') }}',
        url:  "{{ route('admin.users.massDestroy') }}",
        className: 'btn-danger',
        action: function (e, dt, node, config) {
            let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id');
            });
            if (!ids.length) { alert('{{ trans('global.datatables.zero_selected') }}'); return; }
            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({
                    headers: { 'x-csrf-token': _token },
                    method: 'POST', url: config.url,
                    data: { ids: ids, _method: 'DELETE' }
                }).done(() => location.reload());
            }
        }
    });
    @endcan

    $('.datatable-User:not(.ajaxTable)').DataTable({
        buttons: dtButtons,
        order: [[1, 'desc']],
        pageLength: 25,
        scrollX: true,
        language: {
            search: '',
            searchPlaceholder: 'Search users...',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_–_END_ of _TOTAL_ users',
        }
    });
});
</script>
@endsection