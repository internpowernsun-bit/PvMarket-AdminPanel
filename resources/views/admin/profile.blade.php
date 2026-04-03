@extends('layouts.admin')

@section('title', 'My Profile')

@section('styles')
<style>
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .page-header h1 {
        font-size: 22px;
        font-weight: 800;
        color: var(--text);
    }

    /* ── Profile layout: left card + right form ── */
    .profile-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 24px;
        align-items: start;
    }

    /* ── Left: Avatar card ── */
    .profile-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
        text-align: center;
    }

    .profile-card-header {
        background: linear-gradient(135deg, var(--primary-d) 0%, var(--primary) 100%);
        padding: 32px 20px 20px;
    }

    .avatar-wrap {
        position: relative;
        display: inline-block;
        margin-bottom: 14px;
    }

    .avatar-circle {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(255,255,255,.25);
        border: 3px solid rgba(255,255,255,.5);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 800;
        color: white;
        margin: 0 auto;
        overflow: hidden;
    }

    .avatar-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .avatar-edit-btn {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 28px;
        height: 28px;
        background: white;
        border-radius: 50%;
        border: 2px solid var(--primary-m);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: var(--primary-d);
        transition: all .15s;
    }

    .avatar-edit-btn:hover {
        background: var(--primary-l);
        transform: scale(1.1);
    }

    .profile-name {
        font-size: 18px;
        font-weight: 800;
        color: white;
        margin-bottom: 4px;
    }

    .profile-email {
        font-size: 12px;
        color: rgba(255,255,255,.8);
        font-weight: 500;
    }

    .profile-card-body {
        padding: 20px;
    }

    .profile-badge {
        display: inline-block;
        padding: 5px 16px;
        background: var(--primary-l);
        color: var(--primary-d);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: capitalize;
        margin-bottom: 16px;
    }

    .profile-stat {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
    }

    .profile-stat:last-child { border-bottom: none; }

    .profile-stat-label {
        color: var(--muted);
        font-weight: 500;
    }

    .profile-stat-value {
        color: var(--text);
        font-weight: 600;
    }

    .status-dot {
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .status-dot::before {
        content: '';
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10B981;
        display: inline-block;
    }

    /* ── Right: Edit form ── */
    .profile-form-panel {
        background: white;
        border: 1px solid var(--border);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    .panel-header {
        padding: 18px 24px;
        border-bottom: 1px solid var(--border);
        background: #FAFBFD;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .panel-header h2 {
        font-size: 15px;
        font-weight: 700;
        color: var(--text);
    }

    .panel-header svg {
        color: var(--primary-d);
    }

    .panel-body { padding: 24px; }

    /* Tabs */
    .tab-nav {
        display: flex;
        gap: 4px;
        margin-bottom: 24px;
        border-bottom: 2px solid var(--border);
    }

    .tab-btn {
        padding: 9px 18px;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--muted);
        background: none;
        border: none;
        cursor: pointer;
        font-family: inherit;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
        transition: all .15s;
        border-radius: 6px 6px 0 0;
    }

    .tab-btn:hover { color: var(--primary-d); background: var(--primary-l); }

    .tab-btn.active {
        color: var(--primary-d);
        border-bottom-color: var(--primary-d);
        background: var(--primary-l);
    }

    .tab-content { display: none; }
    .tab-content.active { display: block; }

    /* Form grid */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px 24px;
    }

    .form-grid .full { grid-column: 1 / -1; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: var(--text);
    }

    .form-label span { color: var(--danger); margin-left: 2px; }

    .form-input {
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

    .form-input:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(14,165,233,.1);
    }

    .form-input::placeholder { color: #CBD5E1; }

    .form-input:disabled {
        background: var(--light);
        color: var(--muted);
        cursor: not-allowed;
    }

    /* Password strength */
    .password-wrap { position: relative; }

    .password-toggle {
        position: absolute;
        right: 12px; top: 50%;
        transform: translateY(-50%);
        background: none; border: none;
        cursor: pointer; color: var(--muted);
        padding: 0;
        transition: color .15s;
    }

    .password-toggle:hover { color: var(--primary); }

    .form-hint {
        font-size: 11px;
        color: var(--muted);
        margin-top: 2px;
    }

    /* Alert */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 500;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alert-success {
        background: #D1FAE5;
        color: #065F46;
        border: 1px solid #A7F3D0;
    }

    .alert-error {
        background: #FEE2E2;
        color: #991B1B;
        border: 1px solid #FECACA;
    }

    /* Save button */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 20px;
        margin-top: 4px;
        border-top: 1px solid var(--border);
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: var(--primary-d);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        font-family: inherit;
        transition: background .15s, box-shadow .2s, transform .1s;
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
    transition: background .15s;
    white-space: nowrap;
}
.btn-back:hover { background: #334155; }

    .btn-save:hover {
        background: var(--primary);
        box-shadow: 0 4px 12px rgba(14,165,233,.3);
        transform: translateY(-1px);
    }

    /* Avatar upload hidden input */
    #avatarInput { display: none; }
</style>
@endsection

@section('content')


<div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
    <h1 style="font-size:22px; font-weight:800; color:var(--text);">My Profile</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn-back">← Back</a>
</div>

{{-- Success / Error alerts --}}
@if(session('success'))
    <div class="alert alert-success" style="margin-bottom:20px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="alert alert-error" style="margin-bottom:20px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        {{ $errors->first() }}
    </div>
@endif

<div class="profile-layout">

    {{-- ── Left: Profile Card ── --}}
    <div class="profile-card">
        <div class="profile-card-header">
            <div class="avatar-wrap">
                <div class="avatar-circle" id="avatarPreview">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar"/>
                    @else
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    @endif
                </div>
                <label for="avatarInput" class="avatar-edit-btn" title="Change photo">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </label>
            </div>
            <div class="profile-name">{{ Auth::user()->name }}</div>
            <div class="profile-email">{{ Auth::user()->email }}</div>
        </div>

        <div class="profile-card-body">
            <div class="profile-badge">{{ Auth::user()->role ?? 'Admin' }}</div>

            <div class="profile-stat">
                <span class="profile-stat-label">Status</span>
                <span class="profile-stat-value status-dot">Active</span>
            </div>
            <div class="profile-stat">
                <span class="profile-stat-label">Role</span>
                <span class="profile-stat-value">{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'Admin')) }}</span>
            </div>
            <div class="profile-stat">
                <span class="profile-stat-label">Member Since</span>
                <span class="profile-stat-value">
                    {{ Auth::user()->created_at ? \Carbon\Carbon::parse(Auth::user()->created_at)->format('M Y') : 'N/A' }}
                </span>
            </div>
            <div class="profile-stat">
                <span class="profile-stat-label">Last Login</span>
                <span class="profile-stat-value">Today</span>
            </div>
        </div>
    </div>

    {{-- ── Right: Edit Form ── --}}
    <div class="profile-form-panel">
        <div class="panel-header">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
            </svg>
            <h2>Edit Profile</h2>
        </div>

        <div class="panel-body">

            {{-- Tabs --}}
            <div class="tab-nav">
                <button class="tab-btn active" onclick="switchTab('personal', this)">Personal Info</button>
                <button class="tab-btn" onclick="switchTab('password', this)">Change Password</button>
            </div>

            {{-- Tab 1: Personal Info --}}
            <div class="tab-content active" id="tab-personal">
                <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Hidden avatar input --}}
                    <input type="file" id="avatarInput" name="avatar" accept="image/*"
                           onchange="previewAvatar(this)"/>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Full Name <span>*</span></label>
                            <input type="text" name="name" class="form-input"
                                   value="{{ old('name', Auth::user()->name) }}"
                                   placeholder="Enter your name" required/>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email Address <span>*</span></label>
                            <input type="email" name="email" class="form-input"
                                   value="{{ old('email', Auth::user()->email) }}"
                                   placeholder="Enter email" required/>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-input"
                                   value="{{ old('phone', Auth::user()->phone ?? '') }}"
                                   placeholder="+971 XX XXX XXXX"/>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-input"
                                   value="{{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'Admin')) }}"
                                   disabled/>
                        </div>

                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17 21 17 13 7 13 7 21"/>
                                <polyline points="7 3 7 8 15 8"/>
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Tab 2: Change Password --}}
            <div class="tab-content" id="tab-password">
                <form method="POST" action="{{ route('admin.profile.password') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <div class="form-group full">
                            <label class="form-label">Current Password <span>*</span></label>
                            <div class="password-wrap">
                                <input type="password" name="current_password" id="currentPwd"
                                       class="form-input" placeholder="Enter current password"
                                       style="padding-right:42px;" required/>
                                <button type="button" class="password-toggle"
                                        onclick="togglePwd('currentPwd', this)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">New Password <span>*</span></label>
                            <div class="password-wrap">
                                <input type="password" name="password" id="newPwd"
                                       class="form-input" placeholder="Min. 8 characters"
                                       style="padding-right:42px;" required/>
                                <button type="button" class="password-toggle"
                                        onclick="togglePwd('newPwd', this)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirm New Password <span>*</span></label>
                            <div class="password-wrap">
                                <input type="password" name="password_confirmation" id="confirmPwd"
                                       class="form-input" placeholder="Repeat new password"
                                       style="padding-right:42px;" required/>
                                <button type="button" class="password-toggle"
                                        onclick="togglePwd('confirmPwd', this)">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                            <span class="form-hint">Must be at least 8 characters</span>
                        </div>

                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

</div>

@endsection

@section('scripts')
<script>
    // Tab switching
    function switchTab(name, btn) {
        document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + name).classList.add('active');
        btn.classList.add('active');
    }

    // Password visibility toggle
    function togglePwd(id, btn) {
        const input = document.getElementById(id);
        input.type = input.type === 'password' ? 'text' : 'password';
    }

    // Avatar preview
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                const preview = document.getElementById('avatarPreview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Avatar"/>`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection