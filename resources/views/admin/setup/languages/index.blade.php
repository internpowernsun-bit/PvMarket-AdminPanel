@extends('layouts.admin')

@section('title', 'Language Management')

@section('content')
<style>
    /* ── Page shell ── */
    .lang-page { padding: 28px 32px; }

    .lang-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        margin-bottom: 28px;
        gap: 16px;
        flex-wrap: wrap;
    }
    .lang-header-left h1 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary, #111);
        margin: 0 0 4px;
    }
    .lang-header-left p {
        font-size: 0.85rem;
        color: var(--text-muted, #6b7280);
        margin: 0;
    }

    /* ── Alert banners ── */
    .lang-alert {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 20px;
        animation: slideDown .25s ease;
    }
    .lang-alert.success { background:#d1fae5; color:#065f46; border:1px solid #6ee7b7; }
    .lang-alert.error   { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
    @keyframes slideDown { from{opacity:0;transform:translateY(-8px)} to{opacity:1;transform:none} }

    /* ── Cards ── */
    .lang-card {
        background: var(--bg-card, #fff);
        border: 1px solid var(--border, #e5e7eb);
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 24px;
    }
    .lang-card-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border, #e5e7eb);
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg-subtle, #f9fafb);
    }
    .lang-card-header h2 {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-primary, #111);
        margin: 0;
    }
    .lang-card-icon {
        width: 32px; height: 32px;
        border-radius: 8px;
        background: var(--primary, #2563eb);
        display: grid; place-items: center;
        flex-shrink: 0;
    }
    .lang-card-icon svg { width:16px; height:16px; stroke:#fff; }

    /* ── Add-language form ── */
    .add-form { padding: 20px; }
    .add-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr auto auto;
        gap: 12px;
        align-items: end;
    }
    @media(max-width:700px){ .add-form-row{ grid-template-columns:1fr 1fr; } }

    .form-group label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted,#6b7280);
        text-transform: uppercase;
        letter-spacing:.05em;
        margin-bottom: 6px;
    }
    .form-control {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid var(--border,#e5e7eb);
        border-radius: 8px;
        font-size: 0.875rem;
        background: var(--bg-input,#fff);
        color: var(--text-primary,#111);
        outline: none;
        transition: border-color .15s;
    }
    .form-control:focus { border-color: var(--primary,#2563eb); box-shadow: 0 0 0 3px rgba(37,99,235,.1); }

    .rtl-toggle {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .rtl-toggle label.main-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted,#6b7280);
        text-transform: uppercase;
        letter-spacing:.05em;
    }
    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        height: 38px;
    }
    .toggle {
        position: relative;
        width: 44px; height: 24px;
        flex-shrink: 0;
    }
    .toggle input { opacity:0; width:0; height:0; }
    .toggle-slider {
        position: absolute; inset: 0;
        background: #d1d5db;
        border-radius: 999px;
        cursor: pointer;
        transition: background .2s;
    }
    .toggle-slider::before {
        content:'';
        position: absolute;
        width:18px; height:18px;
        border-radius:50%;
        background:#fff;
        top:3px; left:3px;
        transition: transform .2s;
        box-shadow: 0 1px 3px rgba(0,0,0,.2);
    }
    .toggle input:checked ~ .toggle-slider { background: var(--primary,#2563eb); }
    .toggle input:checked ~ .toggle-slider::before { transform: translateX(20px); }
    .toggle-label { font-size: 0.8rem; color: var(--text-muted,#6b7280); white-space: nowrap; }

    /* ── Buttons ── */
    .btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 16px;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        transition: all .15s;
        white-space: nowrap;
        text-decoration: none;
    }
    .btn svg { width:14px; height:14px; stroke:currentColor; }
    .btn-primary { background: var(--primary,#2563eb); color:#fff; }
    .btn-primary:hover { background: #1d4ed8; }
    .btn-sm { padding: 6px 11px; font-size: 0.75rem; }
    .btn-ghost { background:transparent; color:var(--text-muted,#6b7280); border:1px solid var(--border,#e5e7eb); }
    .btn-ghost:hover { background:var(--bg-subtle,#f9fafb); color:var(--text-primary,#111); }
    .btn-danger { background:#fee2e2; color:#dc2626; border:1px solid #fca5a5; }
    .btn-danger:hover { background:#fecaca; }
    .btn-success { background:#d1fae5; color:#065f46; border:1px solid #6ee7b7; }
    .btn-success:hover { background:#a7f3d0; }

    /* ── Table ── */
    .lang-table-wrap { overflow-x: auto; }
    table.lang-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.875rem;
    }
    .lang-table thead th {
        padding: 10px 16px;
        text-align: left;
        font-size: 0.72rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: var(--text-muted,#6b7280);
        background: var(--bg-subtle,#f9fafb);
        border-bottom: 1px solid var(--border,#e5e7eb);
    }
    .lang-table tbody tr {
        border-bottom: 1px solid var(--border,#e5e7eb);
        transition: background .12s;
    }
    .lang-table tbody tr:last-child { border-bottom: none; }
    .lang-table tbody tr:hover { background: var(--bg-subtle,#f9fafb); }
    .lang-table td { padding: 12px 16px; vertical-align: middle; }

    .btn-back { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; background:var(--text); color:white; border-radius:8px; font-size:13.5px; font-weight:600; text-decoration:none; transition:background .15s; white-space:nowrap; }
    .btn-back:hover { background:#334155; }

    .lang-code-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 34px; height: 26px;
        background: var(--bg-subtle,#f3f4f6);
        border: 1px solid var(--border,#e5e7eb);
        border-radius: 6px;
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-primary,#111);
        letter-spacing: .04em;
    }
    .rtl-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 600;
    }
    .rtl-badge.yes { background:#ede9fe; color:#6d28d9; }
    .rtl-badge.no  { background:#f3f4f6; color:#9ca3af; }

    .default-badge {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 0.7rem;
        font-weight: 700;
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fcd34d;
    }

    /* Inline edit row */
    .edit-form-inline {
        display: none;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }
    .edit-form-inline.open { display: flex; }
    .edit-form-inline .form-control { padding: 6px 10px; font-size:0.8rem; }

    .actions-cell { display:flex; gap:6px; align-items:center; }
</style>

<div class="lang-page">

    {{-- Header --}}
    <!--<div class="lang-header">
        <div class="lang-header-left">
            <h1>Language Management</h1>
           
        </div>
    </div>-->

    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
    <h1 style="font-size:22px; font-weight:800; color:var(--text);">Language Management</h1>
    <a href="{{route('admin.dashboard') }}" class="btn-back">← Back</a>
</div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="lang-alert success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="lang-alert error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:16px;height:16px;flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- ═══ ADD LANGUAGE CARD ═══ --}}
    <div class="lang-card">
        <div class="lang-card-header">
            <div class="lang-card-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </div>
            <h2>Add New Language</h2>
        </div>
        <div class="add-form">
            <form method="POST" action="{{ route('admin.setup.languages.store') }}">
                @csrf
                <div class="add-form-row">
                    {{-- Quick-pick from ISO list --}}
                    <div class="form-group">
                        <label>Quick Pick (ISO)</label>
                        <select class="form-control" id="iso-picker" onchange="fillFromIso(this)">
                            <option value=""> select a language </option>
                            @foreach($isoLanguages as $code => $name)
                                @if(!isset($available[$code]))
                                    <option value="{{ $code }}" data-name="{{ $name }}">{{ $name }} ({{ $code }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    {{-- Or type manually --}}
                    <div class="form-group">
                        <label>Code <span style="color:#9ca3af;font-weight:400;">(2-letter)</span></label>
                        <input type="text" name="code" id="add-code" class="form-control"
                               placeholder="e.g. fr" maxlength="2" pattern="[a-zA-Z]{2}"
                               value="{{ old('code') }}" required>
                    </div>

                    <div class="form-group" style="grid-column: span 1;">
                        <label>Language Name</label>
                        <input type="text" name="name" id="add-name" class="form-control"
                               placeholder="e.g. French"
                               value="{{ old('name') }}" required>
                    </div>

                    <div class="rtl-toggle">
                        <label class="main-label">RTL?</label>
                        <div class="toggle-wrap">
                            <label class="toggle">
                                <input type="checkbox" name="rtl" value="1" id="add-rtl">
                                <span class="toggle-slider"></span>
                            </label>
                            <span class="toggle-label">Right-to-left</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                            Add Language
                        </button>
                    </div>
                </div>
                @error('code')<p style="color:#dc2626;font-size:.8rem;margin:6px 0 0;">{{ $message }}</p>@enderror
                @error('name')<p style="color:#dc2626;font-size:.8rem;margin:6px 0 0;">{{ $message }}</p>@enderror
            </form>
        </div>
    </div>

    {{-- ═══ LANGUAGES TABLE CARD ═══ --}}
    <div class="lang-card">
        <div class="lang-card-header" style="justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div class="lang-card-icon" style="background:#059669;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                </div>
                <h2>Active Languages <span style="color:#9ca3af;font-weight:400;">({{ count($available) }})</span></h2>
            </div>

            {{-- Set Default --}}
            <form method="POST" action="{{ route('admin.setup.languages.set-default') }}" style="display:flex;gap:8px;align-items:center;">
                @csrf
                <select name="default" class="form-control" style="width:auto;padding:6px 10px;font-size:0.8rem;">
                    @foreach($available as $code => $name)
                        <option value="{{ $code }}" {{ $default === $code ? 'selected' : '' }}>{{ $name }} ({{ $code }})</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-success btn-sm">Set Default</button>
            </form>
        </div>

        <div class="lang-table-wrap">
            <table class="lang-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Language Name</th>
                        <th>Direction</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($available as $code => $name)
                    <tr>
                        <td><span class="lang-code-badge">{{ $code }}</span></td>
                        <td>
                            <div id="display-{{ $code }}" style="font-weight:500;color:var(--text-primary,#111);">{{ $name }}</div>
                            {{-- Inline edit form --}}
                            <form method="POST" action="{{ route('admin.setup.languages.update', $code) }}"
                                  class="edit-form-inline" id="edit-{{ $code }}">
                                @csrf @method('PUT')
                                <input type="text" name="name" class="form-control"
                                       value="{{ $name }}" placeholder="Language name" required
                                       style="max-width:180px;">
                                <label class="toggle" title="RTL">
                                    <input type="checkbox" name="rtl" value="1" {{ in_array($code, $rtl) ? 'checked' : '' }}>
                                    <span class="toggle-slider"></span>
                                </label>
                                <span class="toggle-label" style="font-size:.75rem;">RTL</span>
                                <button type="submit" class="btn btn-primary btn-sm">Save</button>
                                <button type="button" class="btn btn-ghost btn-sm" onclick="toggleEdit('{{ $code }}', false)">Cancel</button>
                            </form>
                        </td>
                        <td>
                            @if(in_array($code, $rtl))
                                <span class="rtl-badge yes">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:10px;height:10px;"><polyline points="17 8 21 12 17 16"/><line x1="3" y1="12" x2="21" y2="12"/></svg>
                                    RTL
                                </span>
                            @else
                                <span class="rtl-badge no">LTR</span>
                            @endif
                        </td>
                        <td>
                            @if($default === $code)
                                <span class="default-badge">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="width:10px;height:10px;"><polyline points="20 6 9 17 4 12"/></svg>
                                    Default
                                </span>
                            @else
                                <span style="font-size:.75rem;color:#9ca3af;">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="actions-cell">
                                <button type="button" class="btn btn-ghost btn-sm"
                                        onclick="toggleEdit('{{ $code }}', true)">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    Edit
                                </button>

                                @if($default !== $code)
                                <form method="POST" action="{{ route('admin.setup.languages.destroy', $code) }}"
                                      onsubmit="return confirm('Remove {{ $name }} ({{ $code }}) from the languages list?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                                        Remove
                                    </button>
                                </form>
                                @else
                                    <span style="font-size:.72rem;color:#9ca3af;">Can't remove default</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:40px;color:#9ca3af;">
                            No languages configured yet. Add one above.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Config file preview --}}
    <!--
    <div class="lang-card">
        <div class="lang-card-header">
            <div class="lang-card-icon" style="background:#7c3aed;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <h2>config/languages.php — Live Preview</h2>
        </div>
        <div style="padding:16px 20px;">
            <pre style="background:var(--bg-subtle,#f9fafb);border:1px solid var(--border,#e5e7eb);border-radius:8px;padding:16px;font-size:.78rem;overflow-x:auto;margin:0;color:var(--text-primary,#111);line-height:1.6;">&lt;?php

return [
    'default' =&gt; '{{ $default }}',

    'rtl' =&gt; [{{ implode(', ', array_map(fn($c) => "'{$c}'", $rtl)) }}],

    'available' =&gt; [
@foreach($available as $code => $name)        '{{ $code }}' =&gt; '{{ $name }}',
@endforeach    ],
];</pre>
        </div>
    </div>-->

</div>

<script>
function fillFromIso(el) {
    const opt = el.options[el.selectedIndex];
    if (!opt.value) return;
    document.getElementById('add-code').value = opt.value;
    document.getElementById('add-name').value = opt.getAttribute('data-name');

    // Auto-check RTL for known RTL codes
    const rtlCodes = ['ar','ur','he','fa','ps','sd','ug','yi','ku'];
    document.getElementById('add-rtl').checked = rtlCodes.includes(opt.value);
}

function toggleEdit(code, open) {
    const display  = document.getElementById('display-' + code);
    const editForm = document.getElementById('edit-' + code);
    if (open) {
        display.style.display  = 'none';
        editForm.classList.add('open');
    } else {
        display.style.display  = '';
        editForm.classList.remove('open');
    }
}
</script>
@endsection