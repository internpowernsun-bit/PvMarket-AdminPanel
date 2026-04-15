@extends('layouts.admin')
@section('title', 'Page Sections')
@section('content')

<h1 style="font-size:22px; font-weight:800; margin-bottom:24px;">Page Templates</h1>

<div style="display:grid; grid-template-columns:repeat(auto-fill,minmax(240px,1fr)); gap:20px;">
    @foreach($pages as $key => $label)
        @if(!in_array($key, []))
        <a href="{{ route('admin.page-sections.edit', $key) }}"
           style="background:white; border:1px solid var(--border); border-radius:12px;
                  padding:24px; text-decoration:none; color:var(--text);
                  box-shadow:0 1px 4px rgba(0,0,0,.04); transition:all .2s;"
           onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-2px)'"
           onmouseout="this.style.borderColor='var(--border)'; this.style.transform=''">
            <div style="font-size:28px; margin-bottom:10px;">
                {{ ['home'=>'🏠','about'=>'ℹ️','contact'=>'📞','terms'=>'📄', 'disclaimer'=>'⚠️','delivery'=>'🚚','privacy'=>'🔒','faq'=>'❓', 'customer_support'=>'💬'][$key] ?? '📝' }}
            </div>
            <div style="font-size:15px; font-weight:700;">{{ $label }}</div>
            <div style="font-size:12px; color:var(--muted); margin-top:4px;">Edit page content</div>
        </a>
        @endif
        {{-- Temporarily commented: terms, privacy --}}
    @endforeach
</div>

@endsection