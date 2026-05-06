@extends('layouts.admin')
@section('title', 'Edit Lead')

@section('content')

<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:24px;">
    <h1 style="font-size:22px; font-weight:800; color:var(--text);">Edit Lead</h1>
    <a href="{{ route('admin.leads.index') }}" class="btn-back"
       style="display:inline-flex;align-items:center;gap:8px;padding:10px 20px;background:#1E293B;color:white;border:none;border-radius:8px;font-family:inherit;font-size:14px;font-weight:600;text-decoration:none;">
        ← Back
    </a>
</div>

<div style="background:white;border:1px solid var(--border);border-radius:12px;padding:28px;max-width:680px;box-shadow:0 1px 4px rgba(0,0,0,.04);">

    <form method="POST" action="{{ route('admin.leads.update', $lead->id) }}">
        @csrf @method('PUT')

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

            <div>
                <label style="font-size:13px;font-weight:600;color:var(--text);display:block;margin-bottom:6px;">Name</label>
                <input type="text" name="name" value="{{ old('name', $lead->name) }}"
                       style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:13px;outline:none;box-sizing:border-box;"/>
            </div>

            <div>
                <label style="font-size:13px;font-weight:600;color:var(--text);display:block;margin-bottom:6px;">Email</label>
                <input type="email" name="email" value="{{ old('email', $lead->email) }}"
                       style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:13px;outline:none;box-sizing:border-box;"/>
            </div>

            <div>
                <label style="font-size:13px;font-weight:600;color:var(--text);display:block;margin-bottom:6px;">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $lead->phone) }}"
                       style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:13px;outline:none;box-sizing:border-box;"/>
            </div>

            <div>
                <label style="font-size:13px;font-weight:600;color:var(--text);display:block;margin-bottom:6px;">Status</label>
                <select name="status"
                        style="width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:8px;font-family:inherit;font-size:13px;outline:none;box-sizing:border-box;background:white;">
                    <option value="0" {{ $lead->status == 0 ? 'selected' : '' }}>Pending</option>
                    <option value="1" {{ $lead->status == 1 ? 'selected' : '' }}>Processed</option>
                    <option value="2" {{ $lead->status == 2 ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

        </div>

        {{-- Read-only info --}}
        <div style="background:#F8FAFC;border:1px solid var(--border);border-radius:8px;padding:16px;margin-bottom:20px;">
            <p style="font-size:12px;font-weight:700;color:var(--muted);margin-bottom:10px;text-transform:uppercase;letter-spacing:.5px;">Lead Info (read only)</p>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:13px;">
                <div><span style="color:var(--muted);">Lead Type:</span> <strong>{{ $lead->lead_type_label }}</strong></div>
                <div><span style="color:var(--muted);">Lead From:</span> <strong>{{ $lead->lead_from ?? 'Website' }}</strong></div>
                <div><span style="color:var(--muted);">Created:</span> <strong>{{ $lead->created_at ? \Carbon\Carbon::parse($lead->created_at)->format('d M Y, h:i A') : '-' }}</strong></div>
                <div><span style="color:var(--muted);">Lead Data:</span> <strong>{{ $lead->lead_data ?? '-' }}</strong></div>
            </div>
        </div>

        <button type="submit"
                style="padding:10px 28px;background:var(--primary);color:white;border:none;border-radius:8px;font-family:inherit;font-size:14px;font-weight:600;cursor:pointer;">
            Save Changes
        </button>

    </form>
</div>

@endsection