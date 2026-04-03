{{--
=========================================================
  x-admin.form-page  —  Reusable single-record form page
=========================================================
Props:
  title       — Page heading            e.g. "Add Advertisement"
  back-route  — href for ← Back button
  action      — form action URL
  method      — POST | PUT | PATCH  (default POST)
  enctype     — multipart/form-data | (blank)

Slot:
  default     — your form fields go here

Usage example:
─────────────────────────────────────────────────────────
<x-admin.form-page
    title="Add Advertisement"
    :back-route="route('admin.setup.advertisements.index')"
    :action="route('admin.setup.advertisements.store')"
>
    <div class="form-grid">
        <div class="form-group">
            <label class="form-label">Title <span>*</span></label>
            <input type="text" name="title" class="form-input" placeholder="Enter advertisement title" required/>
        </div>
        ...
    </div>
</x-admin.form-page>
─────────────────────────────────────────────────────────
--}}

@props([
    'title'     => 'Add',
    'backRoute' => '#',
    'action'    => '#',
    'method'    => 'POST',
    'enctype'   => 'multipart/form-data',
])

@extends('layouts.admin')

@section('title', $title)

@section('styles')
<style>
    /* ── Page header ── */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    .page-header h1 {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
        background: var(--text);
        color: white;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s, transform .1s;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .btn-back:hover { background: #334155; transform: translateY(-1px); }

    /* ── Panel ── */
    .content-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
        padding: 28px;
    }

    /* ── 2-column grid ── */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px 28px;
    }

    .form-grid .full-width { grid-column: 1 / -1; }

    /* ── Form groups ── */
    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .form-label span { color: var(--danger); margin-left: 2px; }

    .form-hint {
        font-size: 11px;
        color: var(--muted);
        margin-top: 3px;
    }

    /* ── Inputs ── */
    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 9px 13px;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-family: inherit;
        font-size: 13.5px;
        color: var(--text);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        background: white;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(14,165,233,.1);
    }

    .form-input::placeholder,
    .form-textarea::placeholder { color: #CBD5E1; }

    .form-textarea { resize: vertical; min-height: 90px; }

    /* File input */
    .form-file-wrap {
        border: 1.5px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        display: flex;
        align-items: center;
        background: white;
        transition: border-color .2s;
    }

    .form-file-wrap:focus-within { border-color: var(--primary); }

    .form-file-wrap input[type="file"] {
        flex: 1;
        padding: 8px 12px;
        border: none;
        outline: none;
        font-family: inherit;
        font-size: 13px;
        background: transparent;
        cursor: pointer;
    }

    .form-file-wrap input[type="file"]::-webkit-file-upload-button {
        padding: 6px 14px;
        background: var(--light);
        border: none;
        border-right: 1px solid var(--border);
        font-family: inherit;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        margin-right: 8px;
        transition: background .15s;
    }

    .form-file-wrap input[type="file"]::-webkit-file-upload-button:hover {
        background: var(--primary-l);
        color: var(--primary-d);
    }

    /* ── Save button row ── */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        padding-top: 20px;
        margin-top: 20px;
        border-top: 1px solid var(--border);
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 28px;
        background: #10B981;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        font-family: inherit;
        transition: background .15s, box-shadow .2s, transform .1s;
    }

    .btn-save:hover {
        background: #059669;
        box-shadow: 0 4px 14px rgba(16,185,129,.35);
        transform: translateY(-1px);
    }

    .btn-save:active { transform: scale(.97); }
</style>
@endsection

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
    <h1 style="font-size:22px; font-weight:800; color:var(--text);">{{ $title }}</h1>
    <a href="{{ $backRoute }}" class="btn-back">← Back</a>
</div>

<div class="content-panel">
    <form
        method="POST"
        action="{{ $action }}"
        enctype="{{ $enctype }}"
        id="mainForm"
    >
        @csrf
        @if(!in_array(strtoupper($method), ['POST', 'GET']))
            @method($method)
        @endif

        {{-- Page-specific fields injected here --}}
        {{ $slot }}

        <div class="form-actions">
            <button type="submit" class="btn-save">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                    <polyline points="17 8 12 3 7 8"/>
                    <line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                Save
            </button>
        </div>

    </form>
</div>

@endsection