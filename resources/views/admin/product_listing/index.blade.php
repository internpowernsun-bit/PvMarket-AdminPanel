@extends('layouts.admin')

@section('title', 'Manage Listings')

@section('styles')
<style>
    :root {
        --text:      #1F2937;
        --muted:     #6B7280;
        --border:    #E5E7EB;
        --blue:      #3B82F6;
        --green:     #16a34a;
        --red:       #EF4444;
        --bg:        #F3F4F6;
    }

    /* ── Page shell ── */
    .page-wrap { padding: 28px; background: var(--bg); min-height: 100vh; }

    /* ── Header ── */
    .page-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 8px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .page-title { font-size: 22px; font-weight: 800; color: var(--text); margin: 0; }

    .page-subtitle { font-size: 12px; color: var(--primary); margin-top: 4px; line-height: 1.8; }

    .header-right {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    /* warehouse dropdown */
    .wh-wrap { display: flex; flex-direction: column; align-items: flex-end; gap: 4px; }
    .wh-label { font-size: 12px; font-weight: 600; color: var(--muted); }

    .warehouse-select {
        padding: 9px 36px 9px 14px;
        border: 1.5px solid var(--border);
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        color: var(--text);
        background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none;
        min-width: 210px;
        cursor: pointer;
        outline: none;
        transition: border-color .2s;
    }
    .warehouse-select:focus { border-color: var(--primary); }

    /* ── Warehouse clear button ── */
    .wh-clear-btn {
        display: none;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        color: var(--muted);
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        padding: 2px 0;
        text-decoration: underline;
    }
    .wh-clear-btn:hover { color: var(--red); }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 22px;
        background: var(--primary);
        color: white;
        border: none;
        border-radius: 9px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: background .15s;
    }
    .btn-add:hover { background: var(--primary-d); color: white; }

    /* ── Filter tabs ── */
    .filter-tabs {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 20px;
        margin-top: 16px;
    }

    .filter-tab {
        display: inline-block;
        padding: 7px 20px;
        border-radius: 25px;
        font-size: 13px;
        cursor: pointer;
        border: 1.5px solid var(--border);
        background: white;
        color: var(--text);
        text-decoration: none;
        transition: all .2s;
        font-family: inherit;
        font-weight: 500;
    }
    .filter-tab.active,
    .filter-tab:hover {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }

    /* ── Active warehouse indicator ── */
    .wh-active-badge {
        display: none;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        background: #FFF7ED;
        border: 1px solid #FDBA74;
        border-radius: 20px;
        font-size: 12px;
        color: var(--primary);
        font-weight: 600;
        margin-bottom: 12px;
    }

    /* ── Alert ── */
    .alert-success {
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        color: #065f46;
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    /* ── Listing card ── */
    .listing-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 16px;
        display: flex;
        gap: 20px;
        align-items: flex-start;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
        transition: box-shadow .2s;
    }
    .listing-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.08); }

    /* ── Thumbnail column ── */
    .listing-thumb-wrap { position: relative; flex-shrink: 0; }

    .badge-paid {
        position: absolute;
        top: 6px; left: 6px;
        background: var(--blue);
        color: white;
        font-size: 11px;
        padding: 2px 8px;
        border-radius: 4px;
        z-index: 2;
        font-weight: 600;
    }

    .listing-thumb {
        width: 110px;
        height: 110px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #f3f4f6;
        display: block;
    }

    .status-badge {
        display: block;
        text-align: center;
        margin-top: 8px;
        padding: 3px 0;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        border: 1.5px solid;
    }
    .status-badge.active   { color: var(--green); border-color: var(--green); }
    .status-badge.inactive { color: var(--primary); border-color: var(--primary); }

    /* ── Body column ── */
    .listing-body { flex: 1; min-width: 0; }

    .listing-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 14px;
        gap: 12px;
    }

    .listing-name {
        font-size: 17px;
        font-weight: 700;
        color: var(--text);
        margin: 0;
        line-height: 1.3;
    }

    .listing-sku {
        font-size: 11px;
        color: var(--muted);
        margin-top: 3px;
        font-family: monospace;
    }

    /* ── Action buttons ── */
    .listing-actions { display: flex; gap: 8px; flex-shrink: 0; }

    .icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border);
        background: #f9fafb;
        cursor: pointer;
        transition: background .15s;
        text-decoration: none;
    }
    .icon-btn:hover       { background: #f3f4f6; }
    .icon-btn.red:hover   { background: #fee2e2; border-color: #fca5a5; }

    /* ── Meta grid ── */
    .listing-meta {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 10px 24px;
    }

    .meta-item label {
        color: var(--muted);
        font-size: 12px;
        display: block;
        margin-bottom: 2px;
    }
    .meta-item span {
        font-weight: 600;
        font-size: 13px;
        color: var(--text);
    }

    /* ── Flag + country ── */
    .country-cell {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .country-flag {
        width: 20px;
        height: 14px;
        object-fit: cover;
        border-radius: 2px;
        border: 1px solid var(--border);
    }

    /* ── Slots ── */
    .slots-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 16px;
        padding-top: 12px;
        border-top: 1px solid #F3F4F6;
        flex-wrap: wrap;
        gap: 8px;
    }

    .slots-hint { font-size: 12px; color: var(--muted); }

    .view-slots-btn {
        color: var(--primary);
        font-size: 13px;
        font-weight: 600;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-family: inherit;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .slots-detail {
        background: #f9fafb;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px 16px;
        margin-top: 10px;
        font-size: 13px;
    }

    .slot-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 6px 0;
        border-bottom: 1px solid #F3F4F6;
    }
    .slot-row:last-child { border-bottom: none; }
    .slot-price { color: var(--primary); font-weight: 700; }

    /* ── Tags row (popular/sold) ── */
    .listing-tags { display: flex; gap: 6px; margin-top: 8px; flex-wrap: wrap; }

    .tag {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 10px;
        border-radius: 20px;
    }
    .tag-popular { background: #FEF3C7; color: #D97706; }
    .tag-soldoff { background: #FEE2E2; color: var(--red); }

    /* ── Empty state ── */
    .empty-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 70px 24px;
        text-align: center;
    }

    .empty-icon { font-size: 52px; margin-bottom: 12px; }
    .empty-text { font-size: 15px; color: #9CA3AF; font-weight: 500; margin-bottom: 20px; }

    /* ── Pagination ── */
    .pagination-wrap { margin-top: 16px; }

    @media (max-width: 700px) {
        .listing-card { flex-direction: column; }
        .listing-thumb { width: 100%; height: 180px; }
        .listing-meta { grid-template-columns: 1fr 1fr; }
        .header-right { width: 100%; }
        .warehouse-select { width: 100%; min-width: unset; }
    }
</style>
@endsection

@section('content')
<div class="page-wrap">

    {{-- Flash --}}
    @if(session('success'))
        <div class="alert-success">✓ {{ session('success') }}</div>
    @endif

    {{-- ── Header ── --}}
    <div class="page-header">
        <div>
            <h1 class="page-title">Manage Listings</h1>
            <div class="page-subtitle">
                <div>* Make one time payment to use lifetime.</div>
                <div>* Per Offer List charge: $5</div>
                <div>* Unpaid offers: <strong>{{ $unpaidCount }}</strong></div>
            </div>
        </div>

        <div class="header-right">
            {{-- Warehouse filter dropdown --}}
            <div class="wh-wrap">
                <span class="wh-label">
                    Filter by Warehouse
                    @if($warehouseFilter)
                        <button type="button"
                                class="wh-clear-btn"
                                id="whClearBtn"
                                style="display:inline-flex;"
                                onclick="clearWarehouseFilter()">
                            &nbsp;✕ Clear
                        </button>
                    @endif
                </span>

                {{-- The form wraps just the select; filter tab links carry warehouse_id via JS --}}
                <form method="GET" action="{{ route('product_listing.index') }}" id="warehouseForm">
                    <input type="hidden" name="filter" value="{{ $filter }}" id="warehouseFormFilter">
                    <select name="warehouse_id"
                            class="warehouse-select"
                            id="warehouseSelect"
                            onchange="applyWarehouseFilter(this)">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}"
                                {{ $warehouseFilter == $wh->id ? 'selected' : '' }}>
                                {{ $wh->name }}
                            </option>
                        @endforeach
                    </select>
                </form>

                @if($warehouseFilter)
                    @php $selectedWh = $warehouses->firstWhere('id', $warehouseFilter); @endphp
                    @if($selectedWh)
                        <span style="font-size:11px; color:var(--primary); font-weight:600;">
                            📦 Showing: {{ $selectedWh->name }}
                        </span>
                    @endif
                @endif
            </div>

            {{-- Add button --}}
            <a href="{{ route('product_listing.create') }}" class="btn-add">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                </svg>
                Add +
            </a>
        </div>
    </div>

    {{-- ── Filter Tabs ── --}}
    {{-- Tabs preserve the current warehouse filter via query string --}}
    <div class="filter-tabs">
        @foreach(['all' => 'All', 'active' => 'Active', 'inactive' => 'InActive', 'paid' => 'Paid', 'unpaid' => 'Unpaid'] as $key => $label)
            <a href="{{ route('product_listing.index', array_merge(
                    $warehouseFilter ? ['warehouse_id' => $warehouseFilter] : [],
                    ['filter' => $key]
                )) }}"
               class="filter-tab {{ $filter === $key ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- ── Listings ── --}}
    @forelse($listings as $listing)
        @php
    $productId   = is_object($listing->product_id) ? $listing->product_id->__toString() : (string)$listing->product_id;
$warehouseId = is_object($listing->warehouse_id) ? $listing->warehouse_id->__toString() : (string)$listing->warehouse_id;
$product     = $productsMap[$productId] ?? null;
$warehouse   = $warehousesMap[$warehouseId] ?? null;
    $slots     = $listing->slots ?? [];
@endphp

        <div class="listing-card" id="card-{{ $listing->id }}">

            {{-- Thumbnail --}}
            <div class="listing-thumb-wrap">
                @if($listing->is_paid)
                    <span class="badge-paid">Paid</span>
                @endif
                <img
                    @php $firstImage = $listing->images[0] ?? null; @endphp
src="{{ $firstImage && !empty($firstImage['path']) ? asset('storage/' . $firstImage['path']) : asset('images/placeholder.png') }}"
                    alt="{{ $product?->name }}"
                    class="listing-thumb"
                    onerror="this.src='https://placehold.co/110x110/f3f4f6/9ca3af?text=No+Image'"
                >
                <span class="status-badge {{ $listing->is_active ? 'active' : 'inactive' }}">
                    {{ $listing->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

            {{-- Body --}}
            <div class="listing-body">
                <div class="listing-top">
                    <div>
                        <h5 class="listing-name">{{ $product?->product_name ?? $product?->name ?? $listing->product_id }}</h5>
                        <div class="listing-sku">SKU: {{ $listing->sku_code }}</div>

                        {{-- Tags --}}
                        <div class="listing-tags">
                            @if(!empty($listing->is_popular))
                                <span class="tag tag-popular">⭐ Popular</span>
                            @endif
                            @if(!empty($listing->is_sold_off))
                                <span class="tag tag-soldoff">🚫 Sold Off</span>
                            @endif
                        </div>
                    </div>

                    <div class="listing-actions">

    {{-- Approve Payment --}}
    @if(!$listing->is_paid)
    <form method="POST" action="{{ route('product_listing.approvePayment', $listing->id) }}" style="margin:0;">
        @csrf
        <button type="submit" class="icon-btn" title="Approve Payment"
                style="background:#EFF6FF; border-color:#BFDBFE;"
                onclick="return confirm('Approve payment for this listing?')">
            <svg width="14" height="14" fill="none" stroke="#3B82F6" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1"/>
                <circle cx="12" cy="12" r="9"/>
            </svg>
        </button>
    </form>
    @else
    <span class="icon-btn" title="Payment Approved" style="background:#D1FAE5; border-color:#6EE7B7; cursor:default;">
        <svg width="14" height="14" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
    </span>
    @endif

    {{-- Approve Listing --}}
    @if($listing->verification_status !== 'approved')
    <form method="POST" action="{{ route('product_listing.approveListing', $listing->id) }}" style="margin:0;">
        @csrf
        <button type="submit" class="icon-btn" title="Approve Listing"
                style="background:#F0FDF4; border-color:#BBF7D0;"
                onclick="return confirm('Approve this listing?')">
            <svg width="14" height="14" fill="none" stroke="#16a34a" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </button>
    </form>
    @else
    <span class="icon-btn" title="Listing Approved" style="background:#D1FAE5; border-color:#6EE7B7; cursor:default;">
        <svg width="14" height="14" fill="none" stroke="#059669" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </span>
    @endif

    <a href="{{ route('product_listing.edit', $listing->id) }}"
       class="icon-btn" title="Edit">
                            <svg width="14" height="14" fill="none" stroke="#888" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                        </a>
                        <form method="POST"
                              action="{{ route('product_listing.destroy', $listing->id) }}"
                              onsubmit="return confirm('Delete this listing?')"
                              style="margin:0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn red" title="Delete">
                                <svg width="14" height="14" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                                    <polyline points="3 6 5 6 21 6"/>
                                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                    <path d="M10 11v6M14 11v6M9 6V4h6v2"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Meta grid --}}
                <div class="listing-meta">
                    <div class="meta-item">
                        <label>Warehouse</label>
                        <span>{{ $warehouse?->warehouse_name ?? $listing->warehouse_id }}</span>
                    </div>
                    <div class="meta-item">
                        <label>Lead Time</label>
                        <span>{{ $listing->lead_time }} Weeks</span>
                    </div>
                    <div class="meta-item">
                        <label>Sell Type</label>
                        <span>{{ ucwords($listing->sell_type) }}</span>
                    </div>
                    <div class="meta-item">
                        <label>Location</label>
                        <span>
                            @if($warehouse?->country)
                                <span class="country-cell">
                                    {{ $warehouse->city }}, {{ $warehouse->country }}
                                </span>
                            @else
                                {{ $listing->warehouse_id }}
                            @endif
                        </span>
                    </div>
                    <div class="meta-item">
                        <label>Total Available</label>
                        <span>{{ number_format($listing->total_quantity) }} pcs</span>
                    </div>
                    <div class="meta-item">
                        <label>Currency</label>
                        <span>{{ $listing->currency_id }}</span>
                    </div>
                </div>

                {{-- Slots footer --}}
                @if(count($slots) > 0)
                    <div class="slots-footer">
                        <span class="slots-hint">
                            📦 {{ count($slots) }} price {{ count($slots) === 1 ? 'tier' : 'tiers' }}
                            &nbsp;·&nbsp; Incoterm: {{ $listing->incoterm ?? '—' }}
                        </span>
                        <button class="view-slots-btn" onclick="toggleSlots('{{ $listing->id }}')">
                            View Slots ({{ count($slots) }})
                            <span id="arrow-{{ $listing->id }}">▼</span>
                        </button>
                    </div>

                    <div id="slots-{{ $listing->id }}" class="slots-detail" style="display:none;">
                        @foreach($slots as $i => $slot)
                            <div class="slot-row">
                                <span>
                                    <strong>Tier {{ $i + 1 }}:</strong>
                                    @php
    $minQty = is_array($slot) ? $slot['min_quantity'] : $slot->min_quantity ?? 0;
    $maxQty = is_array($slot) ? ($slot['max_quantity'] ?? null) : ($slot->max_quantity ?? null);
@endphp
Min {{ number_format($minQty) }}
@if(!empty($maxQty))
    – Max {{ number_format($maxQty) }} pcs
@else
    pcs &amp; above
@endif
                                </span>
                                <span class="slot-price">
                                    {{ $listing->currency_id }} {{ number_format(is_array($slot) ? $slot['price'] : $slot->price ?? 0, 2) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>{{-- end .listing-body --}}
        </div>

    @empty
        <div class="empty-card">
            <div class="empty-icon">📦</div>
            <div class="empty-text">
                @if($warehouseFilter)
                    No listings found for this warehouse.
                @else
                    No listings found.
                @endif
            </div>
            <a href="{{ route('product_listing.create') }}" class="btn-add"
               style="display:inline-flex; margin:0 auto;">
                + Add Your First Offer
            </a>
        </div>
    @endforelse

    {{-- Pagination --}}
    <div class="pagination-wrap">
        {{ $listings->links() }}
    </div>

</div>
@endsection

@section('scripts')
<script>
// ── Slot toggle ───────────────────────────────────────────────
function toggleSlots(id) {
    const el    = document.getElementById('slots-' + id);
    const arrow = document.getElementById('arrow-' + id);
    const open  = el.style.display !== 'none' && el.style.display !== '';
    el.style.display   = open ? 'none' : 'block';
    arrow.textContent  = open ? '▼' : '▲';
}

// ── Warehouse filter ──────────────────────────────────────────
function applyWarehouseFilter(select) {
    // Sync the hidden filter input before submit
    const filterInput = document.getElementById('warehouseFormFilter');
    if (filterInput) {
        filterInput.value = '{{ $filter }}';
    }
    document.getElementById('warehouseForm').submit();
}

function clearWarehouseFilter() {
    const select = document.getElementById('warehouseSelect');
    if (select) select.value = '';
    // Build URL without warehouse_id
    const url = new URL(window.location.href);
    url.searchParams.delete('warehouse_id');
    window.location.href = url.toString();
}
</script>
@endsection