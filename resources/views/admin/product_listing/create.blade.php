@extends('layouts.admin')

@section('title', 'Create New Offer')

@section('styles')
<style>
    :root {
        --or: #F97316;
        --or-lt: #FFF7ED;
        --or-d: #EA6C0A;
        --text: #1F2937;
        --muted: #6B7280;
        --border: #E5E7EB;
        --bg: #F3F4F6;
        --white: #FFFFFF;
        --green: #10B981;
        --red: #EF4444;
        --blue: #3B82F6;
    }

    .create-page {
        display: flex;
        gap: 24px;
        padding: 28px;
        background: var(--bg);
        min-height: 100vh;
        align-items: flex-start;
    }

    .create-main {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .create-sidebar {
        width: 280px;
        flex-shrink: 0;
        position: sticky;
        top: 80px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* ── Page header ── */
    .page-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 4px;
    }

    .btn-back-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid var(--border);
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        color: var(--text);
        flex-shrink: 0;
    }

    .page-header h1 {
        font-size: 1.35rem;
        font-weight: 800;
        color: var(--text);
        margin: 0;
    }

    .page-header p {
        font-size: .8rem;
        color: var(--muted);
        margin: 2px 0 0;
    }

    /* ── Step indicator ── */
    .step-bar {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-left: auto;
    }

    .step-dot {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--border);
        background: var(--white);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 700;
        color: var(--muted);
    }

    .step-dot.done { background: var(--green); border-color: var(--green); color: #fff; }
    .step-dot.active { background: var(--or); border-color: var(--or); color: #fff; }
    .step-line { width: 20px; height: 2px; background: var(--border); border-radius: 2px; }

    /* ── Section card ── */
    .section-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 18px 22px;
        border-bottom: 1px solid var(--border);
    }

    .section-icon {
        width: 36px;
        height: 36px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .icon-orange { background: var(--or-lt); color: var(--or); }
    .icon-green  { background: #D1FAE5; color: #059669; }
    .icon-blue   { background: #EFF6FF; color: var(--blue); }
    .icon-purple { background: #F5F3FF; color: #7C3AED; }

    .section-title { font-size: .95rem; font-weight: 700; color: var(--text); margin: 0; }
    .section-subtitle { font-size: .78rem; color: var(--muted); margin: 2px 0 0; }

    .section-body { padding: 22px; }

    /* ── Form elements ── */
    .form-row { display: grid; gap: 16px; }
    .form-row.cols-4 { grid-template-columns: repeat(4, 1fr); }
    .form-row.cols-3 { grid-template-columns: repeat(3, 1fr); }
    .form-row.cols-2 { grid-template-columns: repeat(2, 1fr); }
    .form-row.cols-1 { grid-template-columns: 1fr; }

    .form-group { display: flex; flex-direction: column; gap: 5px; }

    .form-label {
        font-size: .8rem;
        font-weight: 600;
        color: var(--text);
    }

    .form-label .req { color: var(--or); }

    .form-control, .form-select {
        padding: 9px 13px;
        border: 1px solid var(--border);
        border-radius: 9px;
        font-size: .88rem;
        color: var(--text);
        outline: none;
        background: #fff;
        width: 100%;
        font-family: inherit;
        transition: border-color .15s, box-shadow .15s;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--or);
        box-shadow: 0 0 0 3px rgba(249,115,22,.1);
    }

    .input-suffix { display: flex; }
    .input-suffix .form-control { border-radius: 9px 0 0 9px; border-right: none; }
    .suffix-label {
        padding: 9px 12px;
        background: #F3F4F6;
        border: 1px solid var(--border);
        border-radius: 0 9px 9px 0;
        font-size: .85rem;
        color: var(--muted);
        white-space: nowrap;
    }

    .error-msg { color: var(--red); font-size: .75rem; margin-top: 3px; }

    /* ── Warehouse pill ── */
    .warehouse-pill {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        background: #F9FAFB;
        border: 1px solid var(--border);
        border-radius: 9px;
        margin-bottom: 18px;
        font-size: .85rem;
        color: var(--text);
        font-weight: 500;
    }

    /* ── Image upload ── */
    .upload-zone {
        border: 2px dashed var(--border);
        border-radius: 12px;
        padding: 36px 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }

    .upload-zone:hover { border-color: var(--or); background: var(--or-lt); }

    .upload-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #F3F4F6;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        color: var(--or);
    }

    .upload-title { font-size: .9rem; font-weight: 600; color: var(--text); margin-bottom: 4px; }
    .upload-sub   { font-size: .75rem; color: var(--muted); }

    /* ── Slots ── */
    .slots-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 14px;
    }

    .slots-label { font-size: .88rem; font-weight: 700; color: var(--text); }
    .slots-hint  { font-size: .75rem; color: var(--muted); margin-top: 2px; }

    .btn-add-slot {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border: 1px solid var(--border);
        background: var(--white);
        border-radius: 8px;
        font-size: .82rem;
        color: var(--text);
        cursor: pointer;
        font-family: inherit;
        transition: border-color .15s;
    }

    .btn-add-slot:hover { border-color: #9CA3AF; }

    .slot-list-box {
        border: 1px solid var(--border);
        border-radius: 10px;
        overflow: hidden;
        min-height: 52px;
    }

    .slot-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 16px;
        border-bottom: 1px solid #F3F4F6;
        font-size: .83rem;
    }

    .slot-item:last-child { border-bottom: none; }

    .slot-num {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--or);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 700;
        flex-shrink: 0;
        margin-right: 10px;
    }

    .slot-price-tag { font-size: .9rem; font-weight: 700; color: var(--text); }
    .slot-price-sub { font-size: .72rem; color: var(--muted); }

    .slot-item-empty {
        padding: 18px;
        text-align: center;
        color: #9CA3AF;
        font-size: .82rem;
    }

    .btn-icon-sm {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        color: #9ca3af;
        font-size: .8rem;
    }

    .btn-icon-sm:hover { color: var(--or); }

    /* ── Add slot form ── */
    .slot-form-box {
        border: 2px solid var(--or);
        border-radius: 12px;
        padding: 18px 20px;
        margin-top: 12px;
        background: #FFFBF5;
    }

    .slot-form-title {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: .88rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 16px;
    }

    .range-badge {
        font-size: .73rem;
        color: var(--muted);
        background: #F3F4F6;
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 2px 8px;
    }

    .slot-form-row { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 12px; align-items: end; }

    .slot-form-actions { display: flex; gap: 10px; justify-content: flex-end; margin-top: 14px; }

    .btn-cancel {
        padding: 8px 18px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: .85rem;
        color: var(--text);
        cursor: pointer;
        font-family: inherit;
    }

    .btn-save-slot {
        padding: 8px 20px;
        background: var(--or);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: .85rem;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
    }

    .btn-save-slot:hover { background: var(--or-d); }

    /* ── Sidebar cards ── */
    .summary-card {
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 14px;
        overflow: hidden;
    }

    .summary-header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 18px;
        border-bottom: 1px solid var(--border);
    }

    .summary-body { padding: 16px 18px; }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 9px 0;
        border-bottom: 1px solid #F3F4F6;
        gap: 12px;
    }

    .summary-row:last-child { border-bottom: none; }
    .summary-key { font-size: .8rem; color: var(--muted); }
    .summary-val { font-size: .82rem; font-weight: 600; color: var(--text); text-align: right; max-width: 55%; }

    .btn-publish {
        width: 100%;
        padding: 12px;
        background: var(--or);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: .9rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 14px;
        font-family: inherit;
    }

    .btn-publish:hover { background: var(--or-d); }

    .publish-note {
        text-align: center;
        font-size: .72rem;
        color: var(--muted);
        margin-top: 8px;
    }

    /* ── Alert ── */
    .alert-error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: .85rem;
    }

    @media (max-width: 900px) {
        .create-page { flex-direction: column; padding: 16px; }
        .create-sidebar { width: 100%; position: static; }
        .form-row.cols-4,
        .form-row.cols-3 { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 600px) {
        .form-row.cols-4,
        .form-row.cols-3,
        .form-row.cols-2 { grid-template-columns: 1fr; }
        .slot-form-row { grid-template-columns: 1fr 1fr; }
    }
</style>
@endsection

@section('content')
<div class="create-page">

    <form method="POST" action="{{ route('product_listing.store') }}" id="listingForm" style="display:contents;">
        @csrf

        {{-- ══════════════════════════════
             MAIN COLUMN
        ══════════════════════════════ --}}
        <div class="create-main">

            {{-- Page header --}}
            <div>
                <div class="page-header">
                    <a href="{{ route('product_listing.index') }}" class="btn-back-circle">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1>Create New Offer</h1>
                        <p>List your product for sale on the marketplace</p>
                    </div>
                    <div class="step-bar">
                        <div class="step-dot active">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-dot">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-dot">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div class="step-line"></div>
                        <div class="step-dot">
                            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <span style="font-size:.72rem; color:var(--muted); margin-left:4px;">0/4</span>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert-error">
                    <ul style="margin:0; padding-left:18px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ── Product Details ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon icon-orange">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="section-title">Product Details</p>
                        <p class="section-subtitle">Select the product you want to list</p>
                    </div>
                </div>
                <div class="section-body">
                    <div class="form-row cols-3">
                        <div class="form-group">
                            <label class="form-label">Main Category <span class="req">*</span></label>
                            <select name="main_category_id" class="form-select" id="mainCatSelect" required>
                                <option value="">Select main category</option>
                                @foreach($mainCategories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('main_category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('main_category_id')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sub Category <span class="req">*</span></label>
                            <select name="sub_category_id" class="form-select" required>
                                <option value="">Select sub category</option>
                                @foreach($subCategories as $sub)
                                    <option value="{{ $sub->id }}" {{ old('sub_category_id') == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sub_category_id')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Product <span class="req">*</span></label>
                            <select name="product_id" class="form-select" id="productSelect" required>
                                <option value="">Select product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('product_id')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="form-row cols-1" style="margin-top:16px;">
                        <div class="form-group">
                            <label class="form-label">Select Warehouse <span class="req">*</span></label>
                            <select name="warehouse_id" class="form-select" id="warehouseSelect" required>
                                <option value="">Choose a warehouse</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}" {{ old('warehouse_id') == $wh->id ? 'selected' : '' }}>
                                        {{ $wh->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('warehouse_id')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Offer Settings ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon icon-orange">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
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
                            <select name="sell_type" class="form-select" id="sellTypeSelect" required>
                                <option value="">Select sell type</option>
                                @foreach($sellTypes as $type)
                                    <option value="{{ $type }}" {{ old('sell_type') == $type ? 'selected' : '' }}>
                                        {{ ucwords($type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sell_type')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Currency <span class="req">*</span></label>
                            <select name="currency_id" class="form-select" id="currencySelect" required>
                                <option value="">Select currency</option>
                                @foreach($currencies as $cur)
                                    <option value="{{ $cur }}" {{ old('currency_id') == $cur ? 'selected' : '' }}>
                                        {{ $cur }}
                                    </option>
                                @endforeach
                            </select>
                            @error('currency_id')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Promotion</label>
                            <select name="discount_type" class="form-select">
                                @foreach($discountTypes as $dt)
                                    <option value="{{ $dt }}" {{ old('discount_type') == $dt ? 'selected' : '' }}>
                                        {{ $dt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Inventory & Pricing ── --}}
            <div class="section-card">
                <div class="section-header">
                    <div class="section-icon icon-green">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="section-title">Inventory &amp; Pricing</p>
                        <p class="section-subtitle">Set quantity, lead time, and price tiers</p>
                    </div>
                </div>
                <div class="section-body">

                    {{-- Warehouse pill (dynamic) --}}
                    <div class="warehouse-pill" id="warehousePill" style="{{ old('warehouse_id') ? '' : 'display:none;' }}">
                        <svg width="15" height="15" fill="none" stroke="#6B7280" stroke-width="1.8" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 10.5V19a1 1 0 001 1h16a1 1 0 001-1v-8.5M3 10.5L12 4l9 6.5M3 10.5h18"/>
                        </svg>
                        <span id="warehousePillName">—</span>
                    </div>

                    <div class="form-row cols-3">
                        <div class="form-group">
                            <label class="form-label">Total Quantity <span class="req">*</span></label>
                            <div class="input-suffix">
                                <input type="number" name="total_quantity" class="form-control"
                                       value="{{ old('total_quantity') }}" min="1" required placeholder="e.g. 4500"
                                       id="totalQtyInput">
                                <span class="suffix-label">pcs</span>
                            </div>
                            @error('total_quantity')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Lead Time <span class="req">*</span></label>
                            <div class="input-suffix">
                                <input type="number" name="lead_time" class="form-control"
                                       value="{{ old('lead_time') }}" min="0" required placeholder="e.g. 10">
                                <span class="suffix-label">weeks</span>
                            </div>
                            @error('lead_time')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">Incoterm <span class="req">*</span></label>
                            <select name="incoterm" class="form-select" required>
                                <option value="">Select incoterm</option>
                                @foreach($incoterms as $code => $label)
                                    <option value="{{ $code }}" {{ old('incoterm') == $code ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('incoterm')<div class="error-msg">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    {{-- Price Tiers --}}
                    <div style="margin-top: 28px;">
                        <div class="slots-header">
                            <div>
                                <div class="slots-label">Price Tiers</div>
                                <div class="slots-hint">Set different prices based on order quantity</div>
                            </div>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <span style="font-size:.75rem; color:var(--muted);">+ Add Tier &nbsp;<span id="tierFraction">0/3</span></span>
                                <button type="button" class="btn-add-slot" onclick="showSlotForm()">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Tier
                                </button>
                            </div>
                        </div>

                        <div class="slot-list-box" id="slotList">
                            <div class="slot-item-empty" id="slotEmptyMsg">No tiers added yet. Click "Add Tier" to create one.</div>
                        </div>

                        {{-- Add Slot Form --}}
                        <div class="slot-form-box" id="slotFormBox" style="display:none;">
                            <div class="slot-form-title">
                                New Price Tier
                                <span class="range-badge">Bounded range</span>
                            </div>
                            <div class="slot-form-row">
                                <div class="form-group">
                                    <label class="form-label">Min Qty <span class="req">*</span></label>
                                    <input type="number" id="slotMinQty" class="form-control" placeholder="e.g. 1" min="0">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Max Qty</label>
                                    <div style="display:flex; gap:8px; align-items:center; padding-top:4px;">
                                        <label style="font-size:.8rem; cursor:pointer; display:flex; align-items:center; gap:4px;">
                                            <input type="radio" name="maxQtyType" value="specific" id="radioSpecific"> Specific
                                        </label>
                                        <label style="font-size:.8rem; cursor:pointer; display:flex; align-items:center; gap:4px;">
                                            <input type="radio" name="maxQtyType" value="more" id="radioMore" checked> And More
                                        </label>
                                    </div>
                                    <div id="specificMaxWrapper" style="display:none; margin-top:6px;">
                                        <input type="number" id="slotMaxQty" class="form-control" placeholder="e.g. 500" min="1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Price / Unit <span class="req">*</span></label>
                                    <input type="number" id="slotPrice" class="form-control" placeholder="0.00" step="0.01" min="0">
                                </div>
                                <div style="display:flex; gap:8px; padding-bottom:1px;">
                                    <button type="button" class="btn-cancel" onclick="cancelSlot()">Cancel</button>
                                    <button type="button" class="btn-save-slot" onclick="saveSlot()">Save</button>
                                </div>
                            </div>
                            <div id="slotError" class="error-msg" style="display:none; margin-top:8px;"></div>
                        </div>

                        <div id="slotInputs"></div>
                    </div>
                </div>
            </div>

        </div>{{-- end .create-main --}}

        {{-- ══════════════════════════════
             SIDEBAR
        ══════════════════════════════ --}}
        <div class="create-sidebar">

            {{-- Product Images card --}}
            <div class="summary-card">
                <div class="summary-header">
                    <div class="section-icon icon-purple" style="width:30px;height:30px;border-radius:8px;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 15l-5-5L5 21"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:.9rem; font-weight:700; color:var(--text);">Product Images</div>
                        <div style="font-size:.73rem; color:var(--muted);">Upload up to 5 photos</div>
                    </div>
                </div>
                <div style="padding:16px 18px;">
                    <div class="upload-zone" onclick="document.getElementById('imgInput').click()">
                        <div class="upload-icon">
                            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                            </svg>
                        </div>
                        <div class="upload-title">Drag &amp; drop images</div>
                        <div class="upload-sub">or click to browse<br>JPEG, PNG, WebP · Max 5MB each</div>
                        <input type="file" id="imgInput" name="images[]" multiple accept="image/*" style="display:none;">
                    </div>
                </div>
            </div>

            {{-- Offer Summary card --}}
            <div class="summary-card">
                <div class="summary-header">
                    <div style="width:30px;height:30px;background:#EFF6FF;border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--blue);">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:.9rem; font-weight:700; color:var(--text);">Offer Summary</div>
                        <div style="font-size:.73rem; color:var(--muted);">Review before publishing</div>
                    </div>
                </div>
                <div class="summary-body">
                    <div class="summary-row">
                        <span class="summary-key">Product</span>
                        <span class="summary-val" id="summProduct">Not selected</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Warehouse</span>
                        <span class="summary-val" id="summWarehouse">Not selected</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Sell Type</span>
                        <span class="summary-val" id="summSellType">Not set</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Currency</span>
                        <span class="summary-val" id="summCurrency">Not set</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Quantity</span>
                        <span class="summary-val" id="summQty">—</span>
                    </div>
                    <div class="summary-row">
                        <span class="summary-key">Price Tiers</span>
                        <span class="summary-val" id="summTiers">0 tiers</span>
                    </div>

                    <button type="submit" class="btn-publish">
                        Publish Offer
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                    <p class="publish-note">Your offer will be reviewed before going live</p>
                </div>
            </div>

        </div>

    </form>
</div>
@endsection

@section('scripts')
<script>
let slots = [];
let editingIndex = null;

// ── Warehouse pill ──────────────────────────────────────────
document.getElementById('warehouseSelect').addEventListener('change', function () {
    const text = this.options[this.selectedIndex].text;
    const pill = document.getElementById('warehousePill');
    const pillName = document.getElementById('warehousePillName');
    if (this.value) {
        pillName.textContent = text;
        pill.style.display = 'flex';
        document.getElementById('summWarehouse').textContent = text;
    } else {
        pill.style.display = 'none';
        document.getElementById('summWarehouse').textContent = 'Not selected';
    }
});

// ── Live summary ─────────────────────────────────────────────
document.getElementById('productSelect').addEventListener('change', function () {
    document.getElementById('summProduct').textContent =
        this.value ? this.options[this.selectedIndex].text : 'Not selected';
});

document.getElementById('sellTypeSelect').addEventListener('change', function () {
    document.getElementById('summSellType').textContent =
        this.value ? this.options[this.selectedIndex].text : 'Not set';
});

document.getElementById('currencySelect').addEventListener('change', function () {
    document.getElementById('summCurrency').textContent =
        this.value || 'Not set';
});

document.getElementById('totalQtyInput').addEventListener('input', function () {
    document.getElementById('summQty').textContent =
        this.value ? Number(this.value).toLocaleString() + ' pcs' : '—';
});

// ── Max qty radio ─────────────────────────────────────────────
document.querySelectorAll('input[name="maxQtyType"]').forEach(r => {
    r.addEventListener('change', function () {
        document.getElementById('specificMaxWrapper').style.display =
            this.value === 'specific' ? 'block' : 'none';
    });
});

// ── Slot form ─────────────────────────────────────────────────
function showSlotForm() {
    document.getElementById('slotFormBox').style.display = 'block';
    document.getElementById('slotMinQty').value = '';
    document.getElementById('slotMaxQty').value = '';
    document.getElementById('slotPrice').value  = '';
    document.getElementById('radioMore').checked = true;
    document.getElementById('specificMaxWrapper').style.display = 'none';
    document.getElementById('slotError').style.display = 'none';
    editingIndex = null;
}

function cancelSlot() {
    document.getElementById('slotFormBox').style.display = 'none';
    editingIndex = null;
}

function saveSlot() {
    const minQty  = parseInt(document.getElementById('slotMinQty').value);
    const price   = parseFloat(document.getElementById('slotPrice').value);
    const maxType = document.querySelector('input[name="maxQtyType"]:checked').value;
    const maxQty  = maxType === 'specific' ? parseInt(document.getElementById('slotMaxQty').value) : null;
    const errEl   = document.getElementById('slotError');

    if (isNaN(minQty) || minQty < 0) {
        errEl.textContent = 'Please enter a valid minimum quantity.';
        errEl.style.display = 'block'; return;
    }
    if (isNaN(price) || price < 0) {
        errEl.textContent = 'Please enter a valid price.';
        errEl.style.display = 'block'; return;
    }
    if (maxType === 'specific' && (isNaN(maxQty) || maxQty <= minQty)) {
        errEl.textContent = 'Max quantity must be greater than minimum.';
        errEl.style.display = 'block'; return;
    }

    const slot = { min_quantity: minQty, max_quantity: maxQty, price };
    if (editingIndex !== null) slots[editingIndex] = slot;
    else slots.push(slot);

    renderSlots();
    cancelSlot();
}

function editSlot(idx) {
    const s = slots[idx];
    showSlotForm();
    document.getElementById('slotMinQty').value = s.min_quantity;
    document.getElementById('slotPrice').value  = s.price;
    if (s.max_quantity !== null) {
        document.getElementById('radioSpecific').checked = true;
        document.getElementById('specificMaxWrapper').style.display = 'block';
        document.getElementById('slotMaxQty').value = s.max_quantity;
    } else {
        document.getElementById('radioMore').checked = true;
    }
    editingIndex = idx;
}

function deleteSlot(idx) {
    slots.splice(idx, 1);
    renderSlots();
}

function renderSlots() {
    const listEl   = document.getElementById('slotList');
    const inputsEl = document.getElementById('slotInputs');

    listEl.innerHTML   = '';
    inputsEl.innerHTML = '';

    if (slots.length === 0) {
        listEl.innerHTML = '<div class="slot-item-empty">No tiers added yet. Click "Add Tier" to create one.</div>';
        document.getElementById('summTiers').textContent = '0 tiers';
        document.getElementById('tierFraction').textContent = '0/3';
        return;
    }

    slots.forEach((slot, i) => {
        const maxLabel = slot.max_quantity !== null
            ? `${slot.max_quantity.toLocaleString()} pcs`
            : 'And More';
        const row = document.createElement('div');
        row.className = 'slot-item';
        row.innerHTML = `
            <div style="display:flex;align-items:center;flex:1;">
                <div class="slot-num">${i + 1}</div>
                <div>
                    <div style="font-weight:600;font-size:.83rem;">${slot.min_quantity.toLocaleString()} – ${maxLabel}</div>
                    <div style="font-size:.72rem;color:var(--muted);">Fixed range tier</div>
                </div>
            </div>
            <div style="text-align:right;margin-right:12px;">
                <div class="slot-price-tag">${slot.price.toFixed(2)}</div>
                <div class="slot-price-sub">per pc</div>
            </div>
            <div style="display:flex;gap:4px;">
                <button type="button" class="btn-icon-sm" onclick="editSlot(${i})" title="Edit">✏️</button>
                <button type="button" class="btn-icon-sm" onclick="deleteSlot(${i})" title="Delete">🗑️</button>
            </div>
        `;
        listEl.appendChild(row);

        inputsEl.innerHTML += `
            <input type="hidden" name="slots[${i}][min_quantity]" value="${slot.min_quantity}">
            <input type="hidden" name="slots[${i}][max_quantity]" value="${slot.max_quantity ?? ''}">
            <input type="hidden" name="slots[${i}][price]" value="${slot.price}">
        `;
    });

    const count = slots.length;
    document.getElementById('summTiers').textContent = count + (count === 1 ? ' tier' : ' tiers');
    document.getElementById('tierFraction').textContent = count + '/3';
}
</script>
@endsection