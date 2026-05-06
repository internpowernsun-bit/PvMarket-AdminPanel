@extends('layouts.admin')

@section('title', 'Manage Listings')

@section('styles')
<style>
/* ── Page header ── */
.listings-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.listings-title {
    font-size: 24px;
    font-weight: 800;
    color: var(--text);
    margin-bottom: 4px;
}

.listings-subtitle {
    color: #e8571e;
    font-size: 13px;
    line-height: 1.8;
}

.header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
    flex-wrap: wrap;
}

.warehouse-select {
    padding: 10px 16px;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    color: var(--text);
    background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394A3B8' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
    appearance: none;
    min-width: 200px;
    cursor: pointer;
    outline: none;
    transition: border-color .2s;
}

.warehouse-select:focus { border-color: var(--primary); }

.btn-add {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: #1E293B;
    color: white;
    border: none;
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    text-decoration: none;
    transition: background .15s;
}

.btn-add:hover { background: #334155; color: white; }

/* ── Filter tabs ── */
.filter-tabs {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 24px;
}

.filter-tab {
    display: inline-block;
    padding: 7px 20px;
    border-radius: 25px;
    font-size: 14px;
    cursor: pointer;
    border: 1.5px solid var(--border);
    background: white;
    color: var(--text);
    text-decoration: none;
    transition: all .2s;
    font-family: inherit;
}

.filter-tab.active,
.filter-tab:hover {
    background: #e8571e;
    color: white;
    border-color: #e8571e;
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
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 16px;
    position: relative;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    display: flex;
    gap: 20px;
    align-items: flex-start;
}

.listing-thumb-wrap {
    position: relative;
    flex-shrink: 0;
}

.badge-paid {
    position: absolute;
    top: 6px;
    left: 6px;
    background: #2563eb;
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
    border-radius: 8px;
    border: 1px solid var(--border);
    background: #f3f4f6;
    display: block;
}

.status-badge {
    display: block;
    text-align: center;
    margin-top: 8px;
    padding: 3px 14px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 1.5px solid;
}

.status-badge.inactive { color: #e8571e; border-color: #e8571e; }
.status-badge.active   { color: #16a34a; border-color: #16a34a; }

/* ── Listing body ── */
.listing-body {
    flex: 1;
    min-width: 0;
}

.listing-top {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 12px;
}

.listing-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--text);
    margin: 0;
}

.listing-actions {
    display: flex;
    gap: 8px;
    flex-shrink: 0;
}

.icon-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--border);
    background: #f9fafb;
    cursor: pointer;
    transition: background .2s;
    text-decoration: none;
}

.icon-btn:hover           { background: #f3f4f6; }
.icon-btn.red             { border-color: var(--border); }
.icon-btn.red:hover       { background: #fee2e2; border-color: #fca5a5; }

/* ── Meta grid ── */
.listing-meta {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px 20px;
}

.listing-meta-item label {
    color: #888;
    font-size: 13px;
    display: block;
    margin-bottom: 1px;
}

.listing-meta-item span {
    font-weight: 600;
    font-size: 14px;
    color: var(--text);
}

/* ── Slots ── */
.slots-toggle-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 14px;
}

.slots-hint {
    font-size: 13px;
    color: #555;
}

.view-slots-btn {
    color: #e8571e;
    font-size: 13px;
    font-weight: 600;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    font-family: inherit;
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
    padding: 4px 0;
    border-bottom: 1px solid var(--border);
}

.slot-row:last-child { border-bottom: none; }

.slot-price {
    color: #e8571e;
    font-weight: 700;
}

/* ── Empty state ── */
.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-icon { font-size: 48px; margin-bottom: 12px; }
.empty-state-text { font-size: 15px; font-weight: 500; color: #aaa; }

/* ── Pagination ── */
.pagination-wrap { margin-top: 16px; }
</style>
@endsection

@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif

{{-- Header --}}
<!--
<div class="listings-header">
    <div>
        <div class="listings-title">Manage Listings</div>
        <div class="listings-subtitle">
            <div>*Make one time payment to use lifetime.</div>
            <div>*Per Offer List charge: $ 5</div>
            <div>*Unpaid offers: {{ $unpaidCount }}</div>
        </div>
    </div>
    <div class="header-actions">
        <form method="GET" action="{{ route('product_listing.index') }}" id="warehouseForm">
            <input type="hidden" name="filter" value="{{ $filter }}">
            <select name="warehouse_id" class="warehouse-select"
                    onchange="document.getElementById('warehouseForm').submit()">
                <option value="">Choose Warehouse...</option>
                @foreach($warehouses as $wh)
                    <option value="{{ $wh->id }}" {{ $warehouseFilter == $wh->id ? 'selected' : '' }}>
                        {{ $wh->name }}
                    </option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('product_listing.create') }}" class="btn-add">
            + Add
        </a>
    </div>
</div>-->

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
    <h1 style="font-size:22px; font-weight:800; color:var(--text);">Manage Listings</h1>
    
    <a href="{{ route('product_listing.create') }}"
       style="display:inline-flex; align-items:center; gap:7px; padding:10px 20px;
              background:var(--primary); color:white; border-radius:8px; font-size:14px;
              font-weight:600; text-decoration:none; white-space:nowrap;"
       onmouseover="this.style.background='var(--primary-d)'"
       onmouseout="this.style.background='var(--primary)'">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
        </svg>
        Add +
    </a>
</div>

{{-- Filter Tabs --}}
<div class="filter-tabs">
    @foreach(['all' => 'All', 'active' => 'Active', 'inactive' => 'InActive', 'paid' => 'Paid', 'unpaid' => 'Unpaid'] as $key => $label)
        <a href="{{ route('product_listing.index', array_merge(request()->only('warehouse_id'), ['filter' => $key])) }}"
           class="filter-tab {{ $filter === $key ? 'active' : '' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- Listings --}}
@forelse($listings as $listing)
    @php
        $product   = $listing->product;
        $warehouse = $listing->warehouse;
        $slots     = $listing->slots ?? [];
    @endphp

    <div class="listing-card" id="card-{{ $listing->id }}">

        {{-- Thumbnail --}}
        <div class="listing-thumb-wrap">
            @if($listing->is_paid)
                <span class="badge-paid">Paid</span>
            @endif
            <img
                src="{{ $product?->image_url ?? asset('images/placeholder.png') }}"
                alt="{{ $product?->name }}"
                class="listing-thumb"
                onerror="this.src='https://via.placeholder.com/110x110?text=No+Image'"
            >
            <span class="status-badge {{ $listing->is_active ? 'active' : 'inactive' }}">
                {{ $listing->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>

        {{-- Body --}}
        <div class="listing-body">
            <div class="listing-top">
                <h5 class="listing-name">{{ $product?->name ?? 'N/A' }}</h5>
                <div class="listing-actions">
                    <a href="{{ route('product_listing.edit', $listing->id) }}" class="icon-btn" title="Edit">
                        <svg width="15" height="15" fill="none" stroke="#888" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('product_listing.destroy', $listing->id) }}"
                          onsubmit="return confirm('Delete this listing?')" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="icon-btn red" title="Delete">
                            <svg width="15" height="15" fill="none" stroke="#ef4444" stroke-width="2" viewBox="0 0 24 24">
                                <polyline points="3 6 5 6 21 6"/>
                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                <path d="M10 11v6M14 11v6"/>
                                <path d="M9 6V4h6v2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="listing-meta">
                <div class="listing-meta-item">
                    <label>Warehouse</label>
                    <span>{{ $warehouse?->name ?? 'N/A' }}</span>
                </div>
                <div class="listing-meta-item">
                    <label>Lead Time</label>
                    <span>{{ $listing->lead_time }} Weeks</span>
                </div>
                <div class="listing-meta-item">
                    <label>Location</label>
                    <span>{{ $warehouse?->country ?? 'N/A' }}</span>
                </div>
                <div class="listing-meta-item">
                    <label>Sell Type</label>
                    <span>{{ ucwords($listing->sell_type) }}</span>
                </div>
                <div class="listing-meta-item">
                    <label>Total Available</label>
                    <span>{{ number_format($listing->total_quantity) }} pcs</span>
                </div>
                <div class="listing-meta-item">
                    <label>Currency</label>
                    <span>{{ $listing->currency_id }}</span>
                </div>
            </div>

            @if(count($slots) > 0)
                <div class="slots-toggle-row">
                    <span class="slots-hint">1 📦 = price tier by qty</span>
                    <button class="view-slots-btn" onclick="toggleSlots('{{ $listing->id }}')">
                        View Slots ({{ count($slots) }})
                        <span id="arrow-{{ $listing->id }}">▼</span>
                    </button>
                </div>

                <div id="slots-{{ $listing->id }}" class="slots-detail" style="display:none;">
                    @foreach($slots as $i => $slot)
                        <div class="slot-row">
                            <span>
                                <strong>Slot {{ $i + 1 }}:</strong>
                                Min Qty {{ number_format($slot['min_quantity']) }}
                                @if(!empty($slot['max_quantity']))
                                    , Max Qty {{ number_format($slot['max_quantity']) }}
                                @else
                                    (And More)
                                @endif
                            </span>
                            <span class="slot-price">
                                {{ $listing->currency_id }} {{ number_format($slot['price'], 2) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
@empty
    <div class="listing-card" style="justify-content:center;">
        <div class="empty-state">
            <div class="empty-state-icon">📦</div>
            <div class="empty-state-text">No listings found.</div>
            <a href="{{ route('product_listing.create') }}" class="btn-add" style="margin-top:16px; display:inline-flex;">
                + Add Your First Offer
            </a>
        </div>
    </div>
@endforelse

{{-- Pagination --}}
<div class="pagination-wrap">
    {{ $listings->links() }}
</div>

@endsection

@section('scripts')
<script>
function toggleSlots(id) {
    const el    = document.getElementById('slots-' + id);
    const arrow = document.getElementById('arrow-' + id);
    if (el.style.display === 'none') {
        el.style.display = 'block';
        arrow.textContent = '▲';
    } else {
        el.style.display = 'none';
        arrow.textContent = '▼';
    }
}
</script>
@endsection