@props(['label', 'routePattern', 'icon'])

{{-- Reusable product nav item with Products, MetaData, Product Detail Options --}}
<div class="nav-item has-children {{ request()->routeIs($routePattern . '.*') ? 'active open' : '' }}">
    {!! $icon !!}
    {{ $label }}
    <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <polyline points="9 18 15 12 9 6"/>
    </svg>
</div>
<div class="nav-sub {{ request()->routeIs($routePattern . '.*') ? 'open' : '' }}">
    <a href="#" class="nav-sub-item">Products</a>
    <a href="#" class="nav-sub-item">MetaData</a>
    <a href="#" class="nav-sub-item">Product Detail Options</a>
</div>