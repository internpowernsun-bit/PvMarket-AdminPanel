@extends('layouts.admin')

@section('title', 'Schedules')

@section('styles')
<style>
    /* ── Page header ── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .page-header-left {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .page-header-left h1 {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
        white-space: nowrap;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        background: var(--primary-d);
        color: white;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        white-space: nowrap;
        transition: background .15s, transform .1s, box-shadow .2s;
    }

    .btn-back:hover {
        background: var(--primary);
        box-shadow: 0 4px 12px rgba(14,165,233,.3);
        transform: translateY(-1px);
    }

    /* ── Content panel ── */
    .content-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    /* Blue header strip */
    .panel-header {
        background: linear-gradient(135deg, var(--primary-d) 0%, var(--primary) 100%);
        padding: 14px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .panel-header-title {
        font-size: 14px;
        font-weight: 700;
        color: white;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .panel-header-count {
        background: rgba(255,255,255,.2);
        color: white;
        font-size: 12px;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* Toolbar */
    .table-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 20px;
        border-bottom: 1px solid var(--border);
        gap: 12px;
        flex-wrap: wrap;
        background: #FAFBFD;
    }

    .search-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-group label {
        font-size: 13.5px;
        color: var(--text);
        font-weight: 600;
    }

    .search-input-wrap { position: relative; }

    .search-input-wrap svg {
        position: absolute;
        left: 10px; top: 50%;
        transform: translateY(-50%);
        color: #CBD5E1;
        pointer-events: none;
    }

    .search-input {
        padding: 8px 12px 8px 34px;
        border: 1.5px solid var(--border);
        border-radius: 7px;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        width: 230px;
        color: var(--text);
        transition: border-color .2s, box-shadow .2s;
        background: white;
    }

    .search-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(14,165,233,.1);
    }

    .search-input::placeholder { color: #CBD5E1; }

    .entries-group {
        display: flex;
        align-items: center;
        gap: 7px;
        font-size: 13.5px;
        color: var(--muted);
        font-weight: 500;
    }

    .entries-select {
        padding: 7px 26px 7px 10px;
        border: 1.5px solid var(--border);
        border-radius: 7px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
        background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 7px center;
        -webkit-appearance: none;
        appearance: none;
        outline: none;
        cursor: pointer;
        transition: border-color .2s;
    }

    .entries-select:focus, .entries-select:hover { border-color: var(--primary); }

    /* Table */
    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead { background: #F0F9FF; }

    .data-table th {
        padding: 11px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--primary-d);
        text-transform: uppercase;
        letter-spacing: .5px;
        border-bottom: 2px solid #BAE6FD;
        white-space: nowrap;
    }

    .data-table th.center,
    .data-table td.center { text-align: center; }

    .data-table td {
        padding: 14px 16px;
        font-size: 13.5px;
        color: var(--text);
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        transition: background .1s;
    }

    .data-table tr:last-child td { border-bottom: none; }
    .data-table tbody tr:nth-child(odd) td  { background: white; }
    .data-table tbody tr:nth-child(even) td { background: #FAFBFD; }
    .data-table tbody tr:hover td { background: #E0F2FE !important; }

    .requester-name {
        font-weight: 700;
        color: var(--text);
        font-size: 14px;
    }

    .requester-email {
        color: var(--primary-d);
        font-size: 13px;
        font-weight: 500;
    }

    .schedule-title {
        font-weight: 600;
        color: var(--text);
    }

    .schedule-date {
        font-size: 13px;
        color: var(--text);
        white-space: nowrap;
    }

    .schedule-duration {
        font-weight: 600;
        color: var(--primary-d);
        white-space: nowrap;
        font-size: 13px;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 14px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        min-width: 90px;
    }

    .status-accepted  { background: #10B981; color: white; }
    .status-pending   { background: #F59E0B; color: white; }
    .status-rejected  { background: #EF4444; color: white; }
    .status-completed { background: #3B82F6; color: white; }

    /* Action icons */
    .action-btns { display: flex; align-items: center; justify-content: center; gap: 8px; }

    .action-icon {
        width: 32px; height: 32px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; border: 1.5px solid transparent;
        background: none; border-radius: 7px;
        transition: all .15s; text-decoration: none;
    }

    .action-icon svg { width: 15px; height: 15px; }

    .action-icon.edit   { color: #D97706; border-color: #FDE68A; background: #FFFBEB; }
    .action-icon.delete { color: #DC2626; border-color: #FECACA; background: #FEF2F2; }

    .action-icon:hover { transform: translateY(-2px) scale(1.08); box-shadow: 0 3px 8px rgba(0,0,0,.12); }
    .action-icon.edit:hover   { background: #FEF3C7; border-color: #F59E0B; }
    .action-icon.delete:hover { background: #FEE2E2; border-color: #EF4444; }

    /* Table footer */
    .table-footer {
        padding: 12px 20px; font-size: 13px; color: var(--muted);
        border-top: 1px solid var(--border);
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 10px; background: #FAFBFD;
    }

    /* Pagination */
    .pagination { display: flex; gap: 3px; list-style: none; }

    .pagination li a,
    .pagination li span {
        display: flex; align-items: center; justify-content: center;
        min-width: 32px; height: 32px; padding: 0 8px;
        border-radius: 6px; border: 1.5px solid var(--border);
        font-size: 13px; font-weight: 500;
        text-decoration: none; color: var(--text); background: white;
        transition: all .15s;
    }

    .pagination li.active span { background: var(--primary-d); border-color: var(--primary-d); color: white; }
    .pagination li a:hover { border-color: var(--primary); color: var(--primary); background: var(--primary-l); transform: translateY(-1px); }

    /* Empty */
    .empty-state { text-align: center; padding: 52px 20px; color: var(--muted); }
    .empty-state svg { width: 42px; height: 42px; margin: 0 auto 12px; opacity: .2; display: block; }
    .empty-state p { font-size: 14px; font-weight: 500; }
</style>
@endsection

@section('content')

{{-- Page Header --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
    <h1 style="font-size:22px; font-weight:800; color:var(--text);">Meeting/Discussion Schedules</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Back</a>
</div>

{{-- Content Panel --}}
<div class="content-panel">

    {{-- Blue header strip --}}
    <div class="panel-header">
        <div class="panel-header-title">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                <line x1="16" y1="2" x2="16" y2="6"/>
                <line x1="8" y1="2" x2="8" y2="6"/>
                <line x1="3" y1="10" x2="21" y2="10"/>
            </svg>
            All Schedules
        </div>
        <span class="panel-header-count">{{ $schedules->total() }} total</span>
    </div>

    {{-- Toolbar --}}
    <div class="table-toolbar">
        <form method="GET" action="{{ route('admin.schedules.index') }}" class="search-group">
            <label>Search:</label>
            <div class="search-input-wrap">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search By Menu..."
                    class="search-input"
                />
            </div>
        </form>

        <form method="GET" action="{{ route('admin.schedules.index') }}" class="entries-group">
            Show
            <select name="entries" class="entries-select" onchange="this.form.submit()">
                <option value="10"  {{ request('entries', 10) == 10  ? 'selected' : '' }}>10</option>
                <option value="25"  {{ request('entries', 10) == 25  ? 'selected' : '' }}>25</option>
                <option value="50"  {{ request('entries', 10) == 50  ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('entries', 10) == 100 ? 'selected' : '' }}>100</option>
            </select>
            entries
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
        </form>
    </div>

    {{-- Table --}}
    <table class="data-table">
        <thead>
            <tr>
                <th class="center" style="width:60px;">S.No</th>
                <th>Requester</th>
                <th>Requester Email</th>
                <th>Title</th>
                <th>Date</th>
                <th class="center">Time</th>
                <th class="center">Status</th>
                <th class="center" style="width:100px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($schedules as $index => $schedule)
            <tr>
                <td class="center" style="font-weight:700; color:var(--muted); font-size:13px;">
                    {{ $schedules->firstItem() + $index }}
                </td>
                <td>
                    <div class="requester-name">{{ $schedule->requester ?? '—' }}</div>
                </td>
                <td>
                    <span class="requester-email">{{ $schedule->requester_email ?? '—' }}</span>
                </td>
                <td>
                    <span class="schedule-title">{{ $schedule->title ?? '—' }}</span>
                </td>
                <td>
                    <span class="schedule-date">
                        {{ $schedule->date ? \Carbon\Carbon::parse($schedule->date)->format('F j, Y \a\t g:i A') : '—' }}
                    </span>
                </td>
                <td class="center">
                    <span class="schedule-duration">
                        {{ $schedule->duration ?? '—' }} Mins
                    </span>
                </td>
                <td class="center">
                    @php $status = strtolower($schedule->status ?? 'pending'); @endphp
                    <span class="status-badge status-{{ $status }}">
                        {{ ucfirst($schedule->status ?? 'Pending') }}
                    </span>
                </td>
                <td>
                    <div class="action-btns">
                        <a href="#" class="action-icon edit" title="Edit">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>
                        <a href="#" class="action-icon delete" title="Delete">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                            </svg>
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <div class="empty-state">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="4" width="18" height="18" rx="2"/>
                            <line x1="16" y1="2" x2="16" y2="6"/>
                            <line x1="8" y1="2" x2="8" y2="6"/>
                            <line x1="3" y1="10" x2="21" y2="10"/>
                        </svg>
                        <p>No schedules found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer --}}
    {{-- Footer --}}
<div class="table-footer">
    <span>
        Showing <strong>{{ $schedules->firstItem() ?? 0 }}</strong> to
        <strong>{{ $schedules->lastItem() ?? 0 }}</strong> of
        <strong>{{ $schedules->total() }}</strong> entries
    </span>

    @if ($schedules->hasPages())
    <nav style="display:flex; align-items:center; gap:4px;">
        @if ($schedules->onFirstPage())
            <span style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:#CBD5E1;cursor:not-allowed;font-size:16px;">‹</span>
        @else
            <a href="{{ $schedules->previousPageUrl() }}" style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:var(--text);text-decoration:none;font-size:16px;font-weight:600;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)';this.style.background='var(--primary-l)';" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)';this.style.background='white';">‹</a>
        @endif

        @foreach ($schedules->getUrlRange(1, $schedules->lastPage()) as $page => $url)
            @if ($page == $schedules->currentPage())
                <span style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--primary-d);background:var(--primary-d);color:white;font-size:13px;font-weight:700;">{{ $page }}</span>
            @elseif ($page == 1 || $page == $schedules->lastPage() || abs($page - $schedules->currentPage()) <= 2)
                <a href="{{ $url }}" style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:var(--text);text-decoration:none;font-size:13px;font-weight:500;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)';this.style.background='var(--primary-l)';" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)';this.style.background='white';">{{ $page }}</a>
            @elseif ($page == $schedules->currentPage() - 3 || $page == $schedules->currentPage() + 3)
                <span style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:var(--muted);font-size:13px;">…</span>
            @endif
        @endforeach

        @if ($schedules->hasMorePages())
            <a href="{{ $schedules->nextPageUrl() }}" style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:var(--text);text-decoration:none;font-size:16px;font-weight:600;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)';this.style.background='var(--primary-l)';" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)';this.style.background='white';">›</a>
        @else
            <span style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:#CBD5E1;cursor:not-allowed;font-size:16px;">›</span>
        @endif
    </nav>
    @endif
</div>

</div>

@endsection