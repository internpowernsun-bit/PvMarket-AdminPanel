@if($mode === 'create')

{{-- ═══ CREATE MODE — uses form-page (single record, not table) ═══ --}}
<x-admin.form-page
    title="Add Coupon"
    :back-route="route('admin.setup.coupons.index')"
    :action="route('admin.setup.coupons.store')"
    method="POST"
>
    <div class="form-grid">

        <div class="form-group">
            <label class="form-label">Coupon Code <span>*</span></label>
            <input type="text" name="code" class="form-input" placeholder="e.g. OFF100"
                   value="{{ old('code') }}" style="text-transform:uppercase;" required/>
        </div>

        <div class="form-group">
            <label class="form-label">Discount Type <span>*</span></label>
            <select name="discount_type" class="form-input" required>
                <option value="">— Select —</option>
                <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                <option value="fixed"      {{ old('discount_type') === 'fixed'      ? 'selected' : '' }}>Fixed Amount</option>
                <option value="months"     {{ old('discount_type') === 'months'     ? 'selected' : '' }}>Free Months</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Discount Value <span>*</span></label>
            <input type="number" name="discount_value" class="form-input" placeholder="e.g. 20"
                   value="{{ old('discount_value') }}" min="0" step="0.01" required/>
        </div>

        <div class="form-group">
            <label class="form-label">Min Order Amount (AED)</label>
            <input type="number" name="min_order_amount" class="form-input" placeholder="Leave blank = no minimum"
                   value="{{ old('min_order_amount') }}" min="0" step="0.01"/>
        </div>

        <div class="form-group">
            <label class="form-label">Usage Limit</label>
            <input type="number" name="usage_limit" class="form-input" placeholder="Leave blank = unlimited"
                   value="{{ old('usage_limit') }}" min="1"/>
        </div>

        <div class="form-group">
            <label class="form-label">Start Date <span>*</span></label>
            <input type="date" name="start_date" class="form-input"
                   value="{{ old('start_date') }}" required/>
        </div>

        <div class="form-group">
            <label class="form-label">End Date <span>*</span></label>
            <input type="date" name="end_date" class="form-input"
                   value="{{ old('end_date') }}" required/>
        </div>

        <div class="form-group">
            <label class="form-label">Status <span>*</span></label>
            <select name="status" class="form-input" required>
                <option value="active"   {{ old('status', 'active') === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-input" placeholder="Short note about this coupon"
                   value="{{ old('description') }}"/>
        </div>

        

    </div>
</x-admin.form-page>
@php return; @endphp

@elseif($mode === 'edit')

{{-- ═══ EDIT MODE ═══ --}}
<x-admin.form-page
    title="Edit Coupon"
    :back-route="route('admin.setup.coupons.index')"
    :action="route('admin.setup.coupons.update', $record->id)"
    method="PUT"
>
    <div class="form-grid">

        <div class="form-group">
            <label class="form-label">Coupon Code <span>*</span></label>
            <input type="text" name="code" class="form-input" placeholder="e.g. OFF100"
                   value="{{ old('code', $record->code) }}" style="text-transform:uppercase;" required/>
        </div>

        <div class="form-group">
            <label class="form-label">Discount Type <span>*</span></label>
            <select name="discount_type" class="form-input" required>
                <option value="percentage" {{ old('discount_type', $record->discount_type) === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                <option value="fixed"      {{ old('discount_type', $record->discount_type) === 'fixed'      ? 'selected' : '' }}>Fixed Amount</option>
                <option value="months"     {{ old('discount_type', $record->discount_type) === 'months'     ? 'selected' : '' }}>Free Months</option>
            </select>
        </div>

        <div class="form-group">
            <label class="form-label">Discount Value <span>*</span></label>
            <input type="number" name="discount_value" class="form-input" placeholder="e.g. 20"
                   value="{{ old('discount_value', $record->discount_value) }}" min="0" step="0.01" required/>
        </div>

        <div class="form-group">
            <label class="form-label">Min Order Amount (AED)</label>
            <input type="number" name="min_order_amount" class="form-input" placeholder="Leave blank = no minimum"
                   value="{{ old('min_order_amount', $record->min_order_amount) }}" min="0" step="0.01"/>
        </div>

        <div class="form-group">
            <label class="form-label">Usage Limit</label>
            <input type="number" name="usage_limit" class="form-input" placeholder="Leave blank = unlimited"
                   value="{{ old('usage_limit', $record->usage_limit) }}" min="1"/>
        </div>

        <div class="form-group">
            <label class="form-label">Start Date <span>*</span></label>
            <input type="date" name="start_date" class="form-input"
                   value="{{ old('start_date', $record->start_date) }}" required/>
        </div>

        <div class="form-group">
            <label class="form-label">End Date <span>*</span></label>
            <input type="date" name="end_date" class="form-input"
                   value="{{ old('end_date', $record->end_date) }}" required/>
        </div>

        <div class="form-group">
            <label class="form-label">Status <span>*</span></label>
            <select name="status" class="form-input" required>
                <option value="active"   {{ old('status', $record->status) === 'active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $record->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="form-group" style="grid-column: 1 / -1;">
            <label class="form-label">Description</label>
            <input type="text" name="description" class="form-input" placeholder="Short note about this coupon"
                   value="{{ old('description', $record->description) }}"/>
        </div>
        

    </div>
</x-admin.form-page>
@php return; @endphp

@else

{{-- ═══════════════════════════════════════
     INDEX MODE
════════════════════════════════════════ --}}
@extends('layouts.admin')
@section('title', 'Coupons Management')

@section('styles')
<style>
    /* ── Page bg – must match notch circles ── */
    :root { --page-bg: #f1f4f8; }

    /* ── Grid: 3 equal columns ── */
    .coupons-grid {
    display: grid;
    grid-template-columns: repeat(3, 340px);
    justify-content: start;
    gap: 24px;
}

    @media (max-width: 1100px) { .coupons-grid { grid-template-columns: repeat(2,1fr); } }
    @media (max-width:  680px) { .coupons-grid { grid-template-columns: 1fr; } }

    /* ── Ticket wrapper ── */
    .coupon-card {
        display: flex;
        flex-direction: row;
        height: 200px;
        border-radius: 16px;
        overflow: visible;
        box-shadow: 0 4px 20px rgba(0,0,0,.13);
        transition: transform .22s ease, box-shadow .22s ease;
        position: relative;
    }
    .coupon-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 32px rgba(0,0,0,.18);
    }
    .coupon-card.is-expired  { opacity: .55; filter: grayscale(.45); }
    .coupon-card.is-inactive { opacity: .60; filter: grayscale(.30); }

    /* ── Yellow left section ── */
    .coupon-left {
        position: relative;
        flex: 1;
        background: #F5C400;
        border-radius: 16px 0 0 16px;
        display: flex;
        flex-direction: row;
        align-items: stretch;
        overflow: hidden;
    }

    /* Side stripe */
    .coupon-side-stripe {
        width: 34px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-right: 2px dashed rgba(0,0,0,.18);
        position: relative;
        z-index: 1;
    }
    .coupon-side-text {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 3px;
        color: rgba(0,0,0,.30);
        color: #0d0d0d;
        white-space: nowrap;
        text-transform: uppercase;
        pointer-events: none;
    }

    /* Notch circles */
    .coupon-notch-top,
    .coupon-notch-bottom {
        position: absolute;
        left: 20px;
        width: 28px;
        height: 28px;
        background: var(--page-bg);
        border-radius: 50%;
        z-index: 4;
        pointer-events: none;
    }
    .coupon-notch-top    { top: -14px; }
    .coupon-notch-bottom { bottom: -14px; }

    /* ── Main body ── */
    .coupon-body {
        flex: 1;
        padding: 16px 16px 14px 18px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-width: 0;
    }

    .coupon-top { display: flex; flex-direction: column; }

    /* Discount badge */
    .coupon-discount-box {
        display: inline-flex;
        align-items: center;
        background: #0d0d0d;
        color: #F5C400;
        font-size: 16px;
        font-weight: 900;
        padding: 5px 14px;
        border-radius: 5px;
        letter-spacing: .6px;
        text-transform: uppercase;
        align-self: flex-start;
        margin-bottom: 4px;
        white-space: nowrap;
    }

    /* COUPON heading — jet black, large */
    .coupon-label {
        font-size: 34px;
        font-weight: 900;
        color: #0d0d0d;
        letter-spacing: 4px;
        line-height: 1;
        margin-bottom: 8px;
        text-transform: uppercase;
    }

    /* Meta rows */
    .coupon-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
        margin-bottom: 6px;
    }
    .coupon-meta-item {
        font-size: 10px;
        font-weight: 700;
        color: #1a1a1a;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .coupon-meta-item svg { flex-shrink: 0; color: #0d0d0d; opacity: .65; }

    /* Extra row: usage + description */
    .coupon-extra {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 4px;
    }

    .coupon-usage-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: rgba(0,0,0,.12);
        border-radius: 20px;
        padding: 3px 9px;
        font-size: 9px;
        font-weight: 800;
        color: #0d0d0d;
        letter-spacing: 1px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .coupon-desc {
        font-size: 10px;
        font-weight: 600;
        color: #1a1a1a;
        font-style: italic;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 200px;
        opacity: .70;
    }

    /* Actions */
    .coupon-actions { display: flex; gap: 10px; align-items: center; }
    .coupon-action-btn {
        background: none; border: none; cursor: pointer; color: #000000;
        color: rgba(0,0,0,.45); padding: 4px; border-radius: 4px;
        transition: color .15s; display: flex; align-items: center;
        text-decoration: none; line-height: 0;
    }
    .coupon-action-btn:hover        { color: #000000; }
    .coupon-action-btn.delete:hover { color: #DC2626; }

    /* ── White right stub ── */
    .coupon-right {
        width: 90px;
        flex-shrink: 0;
        background: #ffffff;
        border-radius: 0 16px 16px 0;
        border-left: 2px dashed rgba(0,0,0,.15);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        overflow: hidden;
        position: relative;
        padding: 14px 0;
    }

    /* Coupon code — jet black, large, vertical */
    .coupon-code {
        writing-mode: vertical-rl;
        transform: rotate(180deg);
        font-size: 15px;
        font-weight: 900;
        color: #0d0d0d;
        letter-spacing: 5px;
        text-transform: uppercase;
    }

    /* Status dot */
    .coupon-status-dot {
        width: 10px; height: 10px;
        border-radius: 50%; flex-shrink: 0;
    }
    .coupon-status-dot.active   { background: #22C55E; box-shadow: 0 0 0 3px rgba(34,197,94,.22); }
    .coupon-status-dot.inactive { background: #94A3B8; }
    .coupon-status-dot.expired  { background: #EF4444; box-shadow: 0 0 0 3px rgba(239,68,68,.20); }

    /* ── Status badges ── */
    .status-badge {
        position: absolute;
        top: 12px;
        right: 98px;
        font-size: 8px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 4px;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        z-index: 5;
        color: white;
    }
    .status-badge.expired  { background: rgba(220,38,38,.88); }
    .status-badge.inactive { background: rgba(100,116,139,.85); }

    /* ── Empty state ── */
    .empty-state {
        text-align: center; padding: 80px 20px;
        color: var(--muted); grid-column: 1 / -1;
    }
    .empty-state svg { width: 52px; height: 52px; margin: 0 auto 16px; opacity: .15; display: block; }
    .empty-state h3  { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 6px; }
    .empty-state p   { font-size: 13.5px; }

    /* ── Alert ── */
    .alert-success {
        padding: 12px 16px; background: #D1FAE5; color: #065F46;
        border: 1px solid #A7F3D0; border-radius: 8px; font-size: 13.5px;
        font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;
    }

    /* ── Search ── */
    .search-wrap     { position: relative; display: inline-block; }
    .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #CBD5E1; pointer-events: none; }
    .search-inp {
        padding: 9px 14px 9px 38px; border: 1.5px solid var(--border); border-radius: 8px;
        font-family: inherit; font-size: 13px; outline: none; width: 280px;
        color: var(--text); background: white; transition: border-color .2s;
    }
    .search-inp:focus        { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(14,165,233,.1); }
    .search-inp::placeholder { color: #CBD5E1; }
</style>
@endsection

@section('content')

{{-- ── Header ── --}}
<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
    <h1 style="font-size:22px; font-weight:800; color:var(--text); margin:0;">Coupons</h1>
    <a href="{{ route('admin.setup.coupons.create') }}"
       style="display:inline-flex; align-items:center; gap:7px; padding:10px 22px;
              background:var(--primary); color:white; border-radius:8px; font-size:14px;
              font-weight:600; text-decoration:none; white-space:nowrap;"
       onmouseover="this.style.background='var(--primary-d)'"
       onmouseout="this.style.background='var(--primary)'">
        Add &nbsp;+
    </a>
</div>

{{-- ── Success flash ── --}}
@if(session('success'))
    <div class="alert-success">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

{{-- ── Search ── --}}
<form method="GET" action="{{ route('admin.setup.coupons.index') }}" style="margin-bottom:32px;">
    <div class="search-wrap">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="8"/>
            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Search coupon code…" class="search-inp"/>
    </div>
</form>

{{-- ── Cards Grid ── --}}
<div class="coupons-grid">
    @forelse($coupons as $coupon)
    {{-- NEW — replace with this --}}
@php
    $isExpired  = \Carbon\Carbon::parse($coupon->end_date)->isPast();
    $isInactive = !$isExpired && $coupon->status === 'inactive';

    $discountLabel = match($coupon->discount_type) {
        'percentage' => $coupon->discount_value . '% OFF',
        'fixed'      => 'AED ' . number_format($coupon->discount_value, 0) . ' OFF',
        'months'     => $coupon->discount_value . ' MONTHS FREE',
        default      => $coupon->discount_value . ' OFF',
    };

    $statusClass = $isExpired ? 'expired' : ($isInactive ? 'inactive' : 'active');
    $startDate   = $coupon->start_date;
    $endDate     = $coupon->end_date;
@endphp
    <div class="coupon-card {{ $isExpired ? 'is-expired' : ($isInactive ? 'is-inactive' : '') }}">

        @if($isExpired)
            <span class="status-badge expired">Expired</span>
        @elseif($isInactive)
            <span class="status-badge inactive">Inactive</span>
        @endif

        {{-- ── Yellow Left ── --}}
        <div class="coupon-left">

            <div class="coupon-notch-top"></div>
            <div class="coupon-notch-bottom"></div>

            <div class="coupon-side-stripe">
                <span class="coupon-side-text">Enjoy Your Gift</span>
            </div>

            <div class="coupon-body">
                <div class="coupon-top">

                    {{-- Discount label --}}
                    <div class="coupon-discount-box">{{ $discountLabel }}</div>

                    {{-- COUPON heading --}}
                    <div class="coupon-label">COUPON</div>

                    {{-- Date range + min order --}}
                    <div class="coupon-meta">
                        @if($startDate && $endDate)
                        <span class="coupon-meta-item">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8"  y1="2" x2="8"  y2="6"/>
                                <line x1="3"  y1="10" x2="21" y2="10"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}
                            &nbsp;–&nbsp;
                            {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                        </span>
                        @endif

                        @if(!empty($coupon->min_order_amount) && $coupon->min_order_amount > 0)
                        <span class="coupon-meta-item">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="12" y1="1" x2="12" y2="23"/>
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                            </svg>
                            Min order: AED {{ number_format($coupon->min_order_amount, 0) }}
                        </span>
                        @endif
                    </div>

                    {{-- Usage limit + description --}}
                    <div class="coupon-extra">
                        @if(!empty($coupon->usage_limit))
                        <span class="coupon-usage-pill">
                            <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                <circle cx="9" cy="7" r="4"/>
                                <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            {{ $coupon->usage_limit }} uses
                        </span>
                        @endif

                        @if(!empty($coupon->description))
                        <span class="coupon-desc">{{ $coupon->description }}</span>
                        @endif
                    </div>
                </div>

                {{-- Edit / Delete --}}
                <div class="coupon-actions">
                    <a href="{{ route('admin.setup.coupons.edit', $coupon->id) }}"
   class="coupon-action-btn" title="Edit">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2.5">
        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
    </svg>
</a>
                    <button class="coupon-action-btn delete" title="Delete"
        onclick="if(confirm('Delete this coupon?')) document.getElementById('del-{{ $coupon->id }}').submit()">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
         stroke="currentColor" stroke-width="2.5">
        <polyline points="3 6 5 6 21 6"/>
        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
        <path d="M10 11v6M14 11v6M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
    </svg>
</button>
                    <form id="del-{{ $coupon->id }}" method="POST"
                          action="{{ route('admin.setup.coupons.destroy', $coupon->id) }}" style="display:none;">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        {{-- ── White Right Stub ── --}}
        <div class="coupon-right">
            <div class="coupon-status-dot {{ $statusClass }}" title="{{ ucfirst($statusClass) }}"></div>
            <span class="coupon-code">{{ $coupon->code }}</span>
        </div>

    </div>
    @empty
    <div class="empty-state">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
            <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        <h3>No Coupons Yet</h3>
        <p>Click <strong>Add +</strong> to create your first coupon.</p>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if ($coupons->hasPages())
<div style="display:flex; align-items:center; justify-content:space-between; margin-top:28px; flex-wrap:wrap; gap:10px;">
    <span style="font-size:13px; color:var(--muted);">
        {{ $coupons->firstItem() ?? 0 }}–{{ $coupons->lastItem() ?? 0 }} of {{ $coupons->total() }} coupons
    </span>
    <nav style="display:flex; align-items:center; gap:4px;">
        @if ($coupons->onFirstPage())
            <span style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:#CBD5E1;cursor:not-allowed;font-size:16px;">‹</span>
        @else
            <a href="{{ $coupons->previousPageUrl() }}" style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:var(--text);text-decoration:none;font-size:16px;font-weight:600;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)';this.style.background='var(--primary-l)';" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)';this.style.background='white';">‹</a>
        @endif

        @foreach ($coupons->getUrlRange(1, $coupons->lastPage()) as $page => $url)
            @if ($page == $coupons->currentPage())
                <span style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--primary-d);background:var(--primary-d);color:white;font-size:13px;font-weight:700;">{{ $page }}</span>
            @elseif ($page == 1 || $page == $coupons->lastPage() || abs($page - $coupons->currentPage()) <= 2)
                <a href="{{ $url }}" style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:var(--text);text-decoration:none;font-size:13px;font-weight:500;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)';this.style.background='var(--primary-l)';" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)';this.style.background='white';">{{ $page }}</a>
            @elseif ($page == $coupons->currentPage() - 3 || $page == $coupons->currentPage() + 3)
                <span style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:var(--muted);font-size:13px;">…</span>
            @endif
        @endforeach

        @if ($coupons->hasMorePages())
            <a href="{{ $coupons->nextPageUrl() }}" style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:var(--text);text-decoration:none;font-size:16px;font-weight:600;transition:all .15s;" onmouseover="this.style.borderColor='var(--primary)';this.style.color='var(--primary)';this.style.background='var(--primary-l)';" onmouseout="this.style.borderColor='var(--border)';this.style.color='var(--text)';this.style.background='white';">›</a>
        @else
            <span style="display:flex;align-items:center;justify-content:center;width:34px;height:34px;border-radius:6px;border:1.5px solid var(--border);background:white;color:#CBD5E1;cursor:not-allowed;font-size:16px;">›</span>
        @endif
    </nav>
</div>
@endif


@endsection

@endif