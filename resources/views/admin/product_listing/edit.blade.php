@extends('layouts.admin')

@section('title', 'Edit Listing — Manage Listings')

@push('styles')
<style>
    :root {
        --pv-orange:    #F97316;
        --pv-orange-lt: #FFF7ED;
        --pv-text:      #1F2937;
        --pv-muted:     #6B7280;
        --pv-border:    #E5E7EB;
        --pv-bg:        #F3F4F6;
        --pv-white:     #FFFFFF;
        --pv-green:     #10B981;
        --pv-red:       #EF4444;
    }

    .edit-page        { display:flex; gap:24px; padding:28px; background:var(--pv-bg); min-height:100vh; align-items:flex-start; }
    .edit-main        { flex:1; min-width:0; display:flex; flex-direction:column; gap:20px; }
    .edit-sidebar     { width:280px; flex-shrink:0; position:sticky; top:80px; }

    /* ── section card ── */
    .section-card     { background:#fff; border:1px solid var(--pv-border); border-radius:14px; overflow:hidden; }
    .section-header   { display:flex; align-items:center; gap:12px; padding:18px 22px; border-bottom:1px solid var(--pv-border); }
    .section-icon     { width:36px; height:36px; border-radius:9px; display:flex; align-items:center; justify-content:center; }
    .icon-orange      { background:var(--pv-orange-lt); color:var(--pv-orange); }
    .icon-green       { background:#D1FAE5; color:#059669; }
    .icon-blue        { background:#EFF6FF; color:#3B82F6; }
    .section-title    { font-size:.95rem; font-weight:700; color:var(--pv-text); margin:0; }
    .section-subtitle { font-size:.78rem; color:var(--pv-muted); margin:2px 0 0; }
    .section-body     { padding:22px; }

    /* ── notice banner ── */
    .notice-banner    { background:#F9FAFB; border-bottom:1px solid var(--pv-border); padding:10px 22px; font-size:.8rem; color:var(--pv-muted); }

    /* ── form elements ── */
    .form-row         { display:grid; gap:18px; }
    .form-row.cols-3  { grid-template-columns:1fr 1fr 1fr; }
    .form-row.cols-2  { grid-template-columns:1fr 1fr; }
    .form-row.cols-1  { grid-template-columns:1fr; }
    .form-group       { display:flex; flex-direction:column; gap:5px; }
    .form-label       { font-size:.8rem; font-weight:600; color:var(--pv-text); }
    .form-label .req  { color:var(--pv-orange); }
    .form-control, .form-select {
        padding:9px 13px; border:1px solid var(--pv-border); border-radius:9px;
        font-size:.88rem; color:var(--pv-text); outline:none; background:#fff; width:100%;
    }
    .form-control:focus, .form-select:focus { border-color:var(--pv-orange); box-shadow:0 0 0 3px rgba(249,115,22,.12); }
    .input-suffix     { display:flex; }
    .input-suffix .form-control { border-radius:9px 0 0 9px; border-right:none; }
    .suffix-label     { padding:9px 12px; background:#F3F4F6; border:1px solid var(--pv-border); border-radius:0 9px 9px 0; font-size:.85rem; color:var(--pv-muted); white-space:nowrap; }

    /* ── warehouse banner in inventory ── */
    .warehouse-row    { display:flex; align-items:center; gap:8px; padding:10px 14px; background:#F9FAFB; border:1px solid var(--pv-border); border-radius:9px; margin-bottom:18px; font-size:.85rem; color:var(--pv-text); font-weight:500; }

    /* ── hold toggle ── */
    .hold-card        { background:#fff; border:1px solid var(--pv-border); border-radius:14px; }
    .hold-body        { display:flex; align-items:center; justify-content:space-between; padding:18px 22px; }
    .hold-info        { display:flex; align-items:center; gap:12px; }
    .hold-check       { width:30px; height:30px; background:#D1FAE5; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#059669; flex-shrink:0; }
    .hold-label       { font-size:.92rem; font-weight:700; color:var(--pv-text); margin:0; }
    .hold-sublabel    { font-size:.78rem; color:var(--pv-muted); margin:2px 0 0; }

    /* ── toggle switch ── */
    .toggle-wrap      { position:relative; display:inline-block; width:48px; height:26px; }
    .toggle-wrap input { opacity:0; width:0; height:0; }
    .toggle-slider    { position:absolute; inset:0; background:#D1D5DB; border-radius:26px; cursor:pointer; transition:.3s; }
    .toggle-slider:before { content:''; position:absolute; width:20px; height:20px; left:3px; top:3px; background:#fff; border-radius:50%; transition:.3s; }
    input:checked + .toggle-slider { background:var(--pv-orange); }
    input:checked + .toggle-slider:before { transform:translateX(22px); }

    /* ── price tier section ── */
    .tier-section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:16px; }
    .tier-count-badge { font-size:.78rem; color:var(--pv-muted); }
    .btn-add-tier     { display:inline-flex; align-items:center; gap:6px; padding:7px 14px; border:1px solid var(--pv-border); background:#fff; border-radius:8px; font-size:.82rem; color:var(--pv-text); cursor:pointer; }
    .btn-add-tier:hover { border-color:#9CA3AF; }

    /* ── tier row ── */
    .tier-row         { display:flex; align-items:center; justify-content:space-between; background:#F9FAFB; border:1px solid var(--pv-border); border-radius:10px; padding:14px 18px; margin-bottom:10px; }
    .tier-num         { width:28px; height:28px; background:var(--pv-orange); color:#fff; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.78rem; font-weight:700; flex-shrink:0; }
    .tier-range       { font-size:.88rem; color:var(--pv-text); font-weight:500; }
    .tier-type        { font-size:.75rem; color:var(--pv-muted); }
    .tier-price       { font-size:1rem; font-weight:700; color:var(--pv-text); text-align:right; }
    .tier-price-sub   { font-size:.73rem; color:var(--pv-muted); }

    /* ── add tier form ── */
    .new-tier-card    { border:2px solid var(--pv-orange); border-radius:12px; padding:18px 20px; margin-top:10px; background:#FFFBF5; }
    .new-tier-title   { display:flex; align-items:center; justify-content:space-between; font-size:.88rem; font-weight:700; color:var(--pv-text); margin-bottom:16px; }
    .range-badge      { font-size:.75rem; color:var(--pv-muted); background:#F3F4F6; border:1px solid var(--pv-border); border-radius:6px; padding:2px 8px; }
    .tier-form-row    { display:grid; grid-template-columns:auto 1fr auto 1fr 1fr; gap:10px; align-items:end; }
    .tier-form-label  { font-size:.78rem; font-weight:600; color:var(--pv-text); display:block; margin-bottom:4px; }
    .btn-range-type   { padding:8px 11px; border:2px solid var(--pv-orange); background:var(--pv-orange); color:#fff; border-radius:8px; font-size:.78rem; font-weight:700; cursor:pointer; white-space:nowrap; }
    .btn-range-more   { font-size:.72rem; color:var(--pv-muted); display:block; text-align:center; margin-top:2px; }
    .tier-form-actions { display:flex; gap:10px; justify-content:flex-end; margin-top:14px; }
    .btn-cancel       { padding:8px 18px; background:#fff; border:1px solid var(--pv-border); border-radius:8px; font-size:.85rem; color:var(--pv-text); cursor:pointer; }
    .btn-add-tier-confirm { padding:8px 20px; background:var(--pv-orange); color:#fff; border:none; border-radius:8px; font-size:.85rem; font-weight:600; cursor:pointer; }

    /* ── inventory history ── */
    .inv-history-empty { text-align:center; padding:36px 20px; color:var(--pv-muted); font-size:.85rem; }

    /* ── offer summary sidebar ── */
    .summary-card     { background:#fff; border:1px solid var(--pv-border); border-radius:14px; overflow:hidden; }
    .summary-header   { display:flex; align-items:center; gap:10px; padding:16px 18px; border-bottom:1px solid var(--pv-border); }
    .summary-body     { padding:16px 18px; }
    .summary-row      { display:flex; justify-content:space-between; align-items:flex-start; padding:9px 0; border-bottom:1px solid #F3F4F6; gap:12px; }
    .summary-row:last-child { border-bottom:none; }
    .summary-key      { font-size:.8rem; color:var(--pv-muted); }
    .summary-val      { font-size:.82rem; font-weight:600; color:var(--pv-text); text-align:right; max-width:55%; }
    .btn-update       { width:100%; padding:12px; background:var(--pv-orange); color:#fff; border:none; border-radius:10px; font-size:.9rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px; margin-top:14px; }
    .btn-update:hover { background:#EA6C0A; }
    .update-note      { text-align:center; font-size:.73rem; color:var(--pv-muted); margin-top:8px; }

    /* ── adjust stock / set alert buttons ── */
    .inventory-actions { display:flex; gap:10px; margin-left:auto; }
    .btn-sm-outline   { display:inline-flex; align-items:center; gap:5px; padding:6px 12px; border:1px solid var(--pv-border); background:#fff; border-radius:8px; font-size:.78rem; color:var(--pv-text); cursor:pointer; }
    .btn-sm-outline:hover { border-color:#9CA3AF; }
</style>
@endpush

@section('content')
<div class="edit-page">

    <form method="POST" action="{{ route('product_listing.update', $listing->_id) }}" id="editForm">
        @csrf @method('PUT')

        {{-- ════════════════════════════════════
             MAIN COLUMN
        ════════════════════════════════════ --}}
        <div class="edit-main">

            {{-- ── Product Details (read-only) ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon icon-orange">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <p class="section-title">Product Details</p>
                        <p class="section-subtitle">Select the product you want to list</p>
                    </div>
                </div>
                <div class="notice-banner">
                    Product and location details cannot be changed. Create a new listing if you need different options.
                </div>
                <div class="section-body">
                    <div class="form-row cols-3">
                        <div class="form-group">
                            <label class="form-label">Main Category</label>
                            <input type="text" class="form-control" value="{{ $listing->main_category_id }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sub Category</label>
                            <input type="text" class="form-control" value="{{ $listing->sub_category_id }}" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Product</label>
                            <input type="text" class="form-control" value="{{ optional($listing->product)->name ?? $listing->product_id }}" disabled>
                        </div>
                    </div>
                    <div class="form-row cols-1" style="margin-top:16px;">
                        <div class="form-group">
                            <label class="form-label">Warehouse</label>
                            <input type="text" class="form-control" value="{{ optional($listing->warehouse)->name ?? $listing->warehouse_id }}" disabled>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Offer Settings ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon icon-orange">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <div>
                        <p class="section-title">Offer Settings</p>
                        <p class="section-subtitle">Configure how you want to sell</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="form-row cols-3">
                        <div class="form-group">
                            <label class="form-label">Sell Type <span class="req">*</span></label>
                            <select name="sell_type" class="form-select">
                                @foreach(['sell by pieces'=>'Sell By Pieces Only','sell by containers'=>'Sell By Containers','sell by weight'=>'Sell By Weight'] as $val=>$label)
                                    <option value="{{ $val }}" {{ $listing->sell_type === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Currency <span class="req">*</span></label>
                            <select name="currency_id" class="form-select">
                                @foreach(['AED'=>'AED - UAE Dirham','USD'=>'USD - US Dollar','GBP'=>'GBP - British Pound','EUR'=>'EUR - Euro'] as $val=>$label)
                                    <option value="{{ $val }}" {{ $listing->currency_id === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Promotion</label>
                            <select name="discount_type" class="form-select">
                                <option value="">No Promotion</option>
                                <option value="fixed"      {{ $listing->discount_type === 'fixed'      ? 'selected' : '' }}>Fixed Discount</option>
                                <option value="percentage" {{ $listing->discount_type === 'percentage' ? 'selected' : '' }}>Percentage Discount</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Hold this offer ── --}}
            <div class="hold-card">
                <div class="hold-body">
                    <div class="hold-info">
                        <div class="hold-check">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div>
                            <p class="hold-label">Hold this offer</p>
                            <p class="hold-sublabel">This offer is active and visible to buyers</p>
                        </div>
                    </div>
                    <label class="toggle-wrap">
                        <input type="checkbox" name="is_active" value="1" id="holdToggle"
                               {{ $listing->is_active ? '' : 'checked' }}
                               onchange="updateHoldLabel(this)">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>

            {{-- ── Inventory & Pricing ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon icon-green">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                    </div>
                    <div style="flex:1;">
                        <p class="section-title">Inventory &amp; Pricing</p>
                        <p class="section-subtitle">Set quantity, lead time, and price tiers</p>
                    </div>
                    <div class="inventory-actions">
                        <button type="button" class="btn-sm-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Adjust Stock
                        </button>
                        <button type="button" class="btn-sm-outline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            Set Alert
                        </button>
                    </div>
                </div>
                <div class="section-body">
                    {{-- Warehouse pill --}}
                    <div class="warehouse-row">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#6B7280" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5V19a1 1 0 001 1h16a1 1 0 001-1v-8.5M3 10.5L12 4l9 6.5M3 10.5h18"/></svg>
                        {{ optional($listing->warehouse)->name ?? 'Warehouse' }}
                    </div>

                    {{-- Quantity / Lead Time / Incoterm --}}
                    <div class="form-row cols-3">
                        <div class="form-group">
                            <label class="form-label">Total Quantity <span class="req">*</span></label>
                            <div class="input-suffix">
                                <input type="number" name="total_quantity" class="form-control"
                                       value="{{ $listing->total_quantity }}" min="1" id="totalQty">
                                <span class="suffix-label">pcs</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Lead Time <span class="req">*</span></label>
                            <div class="input-suffix">
                                <input type="number" name="lead_time" class="form-control"
                                       value="{{ $listing->lead_time }}" min="0" id="leadTimeVal">
                                <span class="suffix-label">weeks</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Incoterm <span class="req">*</span></label>
                            <select name="incoterm" class="form-select">
                                <option value="">Select incoterm</option>
                                @foreach(['EXW','FCA','FOB','CFR','CIF','DAP','DDP'] as $inco)
                                    <option value="{{ $inco }}" {{ ($listing->incoterm ?? '') === $inco ? 'selected' : '' }}>{{ $inco }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ── Price Tiers ── --}}
                    <div style="margin-top:28px;">
                        <div class="tier-section-header">
                            <div>
                                <div style="font-size:.9rem;font-weight:700;color:var(--pv-text);">Price Tiers</div>
                                <div style="font-size:.78rem;color:var(--pv-muted);">Set different prices based on order quantity</div>
                            </div>
                            <div style="display:flex;align-items:center;gap:12px;">
                                <span class="tier-count-badge" id="tierCountBadge">
                                    + Add Tier &nbsp; <span id="tierFraction">{{ count($listing->slots ?? []) }}/3</span>
                                </span>
                                <button type="button" class="btn-add-tier" id="showAddTierBtn"
                                        onclick="showAddTierForm()">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                    Add Tier
                                </button>
                            </div>
                        </div>

                        {{-- Existing tiers --}}
                        <div id="tiersContainer">
                            @foreach($listing->slots ?? [] as $i => $slot)
                            <div class="tier-row" data-tier-index="{{ $i }}">
                                <div class="tier-num">{{ $i + 1 }}</div>
                                <div style="flex:1;margin:0 18px;">
                                    <div class="tier-range">
                                        {{ number_format($slot['min_quantity']) }} – {{ number_format($slot['max_quantity']) }} pcs
                                    </div>
                                    <div class="tier-type">Fixed range tier</div>
                                </div>
                                <div>
                                    <div class="tier-price">
                                        {{ $listing->currency_id }} {{ number_format($slot['price'], 2) }}
                                    </div>
                                    <div class="tier-price-sub">per pc</div>
                                </div>

                                {{-- Hidden inputs to submit existing slots --}}
                                <input type="hidden" name="slots[{{ $i }}][min_quantity]" value="{{ $slot['min_quantity'] }}">
                                <input type="hidden" name="slots[{{ $i }}][max_quantity]" value="{{ $slot['max_quantity'] }}">
                                <input type="hidden" name="slots[{{ $i }}][price]"        value="{{ $slot['price'] }}">
                            </div>
                            @endforeach
                        </div>

                        {{-- Add Tier Form (hidden by default) --}}
                        <div class="new-tier-card" id="addTierForm" style="display:none;">
                            <div class="new-tier-title">
                                New Price Tier
                                <span class="range-badge">Bounded range</span>
                            </div>
                            <div class="tier-form-row">
                                <div>
                                    <label class="tier-form-label">Min Qty <span style="color:var(--pv-orange)">*</span></label>
                                    <input type="number" class="form-control" id="newMinQty" placeholder="1" min="0" style="width:90px;">
                                </div>
                                <div style="display:flex;flex-direction:column;align-items:center;gap:2px;padding-top:22px;">
                                    <button type="button" class="btn-range-type">Fixed<br>Max</button>
                                    <span class="btn-range-more">And<br>More</span>
                                </div>
                                <div>
                                    <label class="tier-form-label">Max Qty <span style="color:var(--pv-orange)">*</span></label>
                                    <input type="number" class="form-control" id="newMaxQty" placeholder="100" min="1" style="width:110px;">
                                </div>
                                <div>
                                    <label class="tier-form-label">Price / Unit <span style="color:var(--pv-orange)">*</span></label>
                                    <div class="input-suffix">
                                        <span class="suffix-label" style="border-radius:9px 0 0 9px;border-right:none;">$</span>
                                        <input type="number" class="form-control" id="newPrice" placeholder="0.00" min="0" step="0.01" style="border-radius:0 9px 9px 0;">
                                    </div>
                                </div>
                            </div>
                            <div class="tier-form-actions">
                                <button type="button" class="btn-cancel" onclick="hideAddTierForm()">Cancel</button>
                                <button type="button" class="btn-add-tier-confirm" onclick="addTier()">Add Tier</button>
                            </div>
                        </div>
                    </div>

                    {{-- ── Inventory History ── --}}
                    <div style="margin-top:32px;">
                        <div style="font-size:.9rem;font-weight:700;color:var(--pv-text);margin-bottom:4px;">Inventory History</div>
                        <div style="font-size:.78rem;color:var(--pv-muted);margin-bottom:16px;">Recent stock movements and adjustments</div>
                        <div class="inv-history-empty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="#D1D5DB" stroke-width="1.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div style="margin-top:8px;">No inventory history yet</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>{{-- end .edit-main --}}

        {{-- ════════════════════════════════════
             SIDEBAR — Offer Summary
        ════════════════════════════════════ --}}
        <div class="edit-sidebar">
            <div class="summary-card">
                <div class="summary-header">
                    <div style="width:30px;height:30px;background:#EFF6FF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#3B82F6;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <div>
                        <div style="font-size:.9rem;font-weight:700;color:var(--pv-text);">Offer Summary</div>
                        <div style="font-size:.75rem;color:var(--pv-muted);">Review before publishing</div>
                    </div>
                </div>
                <div class="summary-body">
                    <div class="summary-row">
                        <span class="summary-key">Product</span>
                        <span class="summary-val">{{ optional($listing->product)->name ?? 'Talesun Solar Energy…' }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Warehouse</span>
                        <span class="summary-val">{{ optional($listing->warehouse)->name ?? $listing->warehouse_id }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Sell Type</span>
                        <span class="summary-val" id="summSellType">{{ ucwords(str_replace('sell by ','',$listing->sell_type)) }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Currency</span>
                        <span class="summary-val" id="summCurrency">{{ $listing->currency_id }}</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Quantity</span>
                        <span class="summary-val" id="summQty">{{ number_format($listing->total_quantity) }} pcs</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Price Tiers</span>
                        <span class="summary-val" id="summTiers">{{ count($listing->slots ?? []) }} {{ count($listing->slots ?? []) === 1 ? 'tier' : 'tiers' }}</span>
                    </div>

                    <button type="submit" class="btn-update">
                        Update Offer
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <p class="update-note">Changes will be reflected immediately</p>
                </div>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    let tierCount = {{ count($listing->slots ?? []) }};

    // ── Live summary updates ──────────────────────────────────────
    document.querySelector('[name="sell_type"]').addEventListener('change', function() {
        document.getElementById('summSellType').textContent =
            this.options[this.selectedIndex].text.replace('Sell By ','').replace('Only','').trim();
    });
    document.querySelector('[name="currency_id"]').addEventListener('change', function() {
        document.getElementById('summCurrency').textContent = this.value;
    });
    document.getElementById('totalQty').addEventListener('input', function() {
        document.getElementById('summQty').textContent = Number(this.value).toLocaleString() + ' pcs';
    });

    // ── Hold label update ─────────────────────────────────────────
    function updateHoldLabel(cb) {
        const sub = cb.closest('.hold-body').querySelector('.hold-sublabel');
        sub.textContent = cb.checked
            ? 'This offer is on hold and hidden from buyers'
            : 'This offer is active and visible to buyers';
    }

    // ── Add Tier UI ───────────────────────────────────────────────
    function showAddTierForm() {
        document.getElementById('addTierForm').style.display = 'block';
        document.getElementById('showAddTierBtn').style.display = 'none';
    }

    function hideAddTierForm() {
        document.getElementById('addTierForm').style.display = 'none';
        document.getElementById('showAddTierBtn').style.display = 'inline-flex';
        document.getElementById('newMinQty').value = '';
        document.getElementById('newMaxQty').value = '';
        document.getElementById('newPrice').value  = '';
    }

    function addTier() {
        const minQ  = parseInt(document.getElementById('newMinQty').value);
        const maxQ  = parseInt(document.getElementById('newMaxQty').value);
        const price = parseFloat(document.getElementById('newPrice').value);

        if (isNaN(minQ) || isNaN(maxQ) || isNaN(price)) {
            alert('Please fill in all tier fields.');
            return;
        }
        if (maxQ <= minQ) {
            alert('Max Qty must be greater than Min Qty.');
            return;
        }

        const idx      = tierCount;
        const currency = document.querySelector('[name="currency_id"]').value;
        const container = document.getElementById('tiersContainer');

        // Build display row
        const row = document.createElement('div');
        row.className = 'tier-row';
        row.dataset.tierIndex = idx;
        row.innerHTML = `
            <div class="tier-num">${idx + 1}</div>
            <div style="flex:1;margin:0 18px;">
                <div class="tier-range">${minQ.toLocaleString()} – ${maxQ.toLocaleString()} pcs</div>
                <div class="tier-type">Fixed range tier</div>
            </div>
            <div>
                <div class="tier-price">${currency} ${price.toFixed(2)}</div>
                <div class="tier-price-sub">per pc</div>
            </div>
            <input type="hidden" name="slots[${idx}][min_quantity]" value="${minQ}">
            <input type="hidden" name="slots[${idx}][max_quantity]" value="${maxQ}">
            <input type="hidden" name="slots[${idx}][price]"        value="${price}">
        `;
        container.appendChild(row);

        tierCount++;
        document.getElementById('tierFraction').textContent = tierCount + '/3';
        document.getElementById('summTiers').textContent    = tierCount + (tierCount === 1 ? ' tier' : ' tiers');
        hideAddTierForm();
    }
</script>
@endpush
