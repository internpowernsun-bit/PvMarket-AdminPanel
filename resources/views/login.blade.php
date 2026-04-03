<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Powernsun — Admin Portal</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    :root {
      --text:    #111827;
      --muted:   #6B7280;
      --border:  #E5E7EB;
      --light:   #EBF0F8;
      --white:   #FFFFFF;
      --danger:  #EF4444;
      --success: #10B981;
      --blue:    #3B82F6;
      --blue-d:  #2563EB;
    }

    html, body {
      height: 100%;
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: var(--light);
      color: var(--text);
    }

    .page {
      min-height: 100vh;
      display: flex;
    }

    /* ── Left illustration panel ── */
    .left {
      flex: 1;
      background: #EBF0F8;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 60px;
      position: relative;
      overflow: hidden;
    }

    .left::before {
      content: '';
      position: absolute;
      width: 320px; height: 320px;
      border-radius: 50%;
      background: rgba(147,197,253,.18);
      top: -60px; left: -60px;
    }
    .left::after {
      content: '';
      position: absolute;
      width: 240px; height: 240px;
      border-radius: 50%;
      background: rgba(147,197,253,.13);
      bottom: -40px; right: -40px;
    }

    .illustration-wrap {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 460px;
    }

    .illustration-wrap svg {
      width: 100%;
      height: auto;
    }

    /* ── Right form panel ── */
    .right {
      width: 500px;
      min-width: 440px;
      background: white;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 56px 52px;
      box-shadow: -2px 0 30px rgba(0,0,0,.06);
    }

    .form-wrap { width: 100%; }

    .form-logo {
      display: flex;
      justify-content: center;
      margin-bottom: 24px;
    }

    .form-logo img {
      height: 54px;
      width: auto;
      object-fit: contain;
    }

    .form-top {
      text-align: center;
      margin-bottom: 32px;
    }

    .form-top h2 {
      font-size: 24px;
      font-weight: 800;
      color: var(--text);
      letter-spacing: -0.3px;
    }

    /* ── Alert ── */
    .alert {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 12px 16px;
      border-radius: 10px;
      font-size: 13px;
      font-weight: 500;
      margin-bottom: 20px;
    }
    .alert.error   { background: #FEF2F2; border: 1px solid #FECACA; color: var(--danger); }
    .alert.success { background: #ECFDF5; border: 1px solid #A7F3D0; color: var(--success); }

    /* ── Fields ── */
    .field { margin-bottom: 20px; }

    .field label {
      display: block;
      font-size: 13.5px;
      font-weight: 600;
      color: var(--text);
      margin-bottom: 8px;
    }

    .input-wrap { position: relative; }

    input[type="email"],
    input[type="password"],
    input[type="text"] {
      width: 100%;
      padding: 11px 14px;
      border: 1.5px solid var(--border);
      border-radius: 8px;
      font-family: inherit;
      font-size: 14px;
      color: var(--text);
      background: var(--white);
      outline: none;
      transition: border-color .2s, box-shadow .2s;
      -webkit-appearance: none;
      appearance: none;
    }

    input:focus {
      border-color: var(--blue);
      box-shadow: 0 0 0 3px rgba(59,130,246,.12);
    }

    input.is-invalid {
      border-color: var(--danger);
      box-shadow: 0 0 0 3px rgba(239,68,68,.1);
    }

    input::placeholder { color: #CBD5E1; }

    input:-webkit-autofill {
      -webkit-box-shadow: 0 0 0 30px white inset;
      -webkit-text-fill-color: var(--text);
    }

    .toggle-pass {
      position: absolute;
      right: 12px; top: 50%;
      transform: translateY(-50%);
      background: none; border: none;
      cursor: pointer; color: #9CA3AF;
      padding: 4px; display: flex;
    }
    .toggle-pass:hover { color: var(--blue); }

    .field-meta {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 6px;
    }

    .field-error { font-size: 12px; color: var(--danger); font-weight: 500; }

    .forgot { font-size: 12.5px; color: var(--blue); text-decoration: none; font-weight: 600; }
    .forgot:hover { color: var(--blue-d); }

    /* ── Remember ── */
    .remember {
      display: flex;
      align-items: center;
      gap: 9px;
      margin-bottom: 24px;
    }

    .remember input[type="checkbox"] {
      width: 16px; height: 16px;
      padding: 0; cursor: pointer;
      accent-color: var(--blue);
    }

    .remember label {
      font-size: 13px; color: var(--muted);
      cursor: pointer; font-weight: 500;
    }

    /* ── Submit btn ── */
    .btn-submit {
      width: 100%;
      padding: 13px;
      background: var(--blue);
      color: white;
      font-family: inherit;
      font-size: 15px;
      font-weight: 700;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background .2s, transform .1s, box-shadow .2s;
      letter-spacing: 0.2px;
    }

    .btn-submit:hover {
      background: var(--blue-d);
      box-shadow: 0 4px 18px rgba(59,130,246,.35);
      transform: translateY(-1px);
    }

    .btn-submit:active { transform: scale(.98); }

    /* ── Responsive ── */
    @media (max-width: 860px) {
      .left { display: none; }
      .right { width: 100%; min-width: unset; }
    }

    .right { animation: fadeIn .4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } }
  </style>
</head>
<body>

<div class="page">

  <!-- ── Left Panel ── -->
  <div class="left">
    <div class="illustration-wrap">
      <svg viewBox="0 0 520 440" xmlns="http://www.w3.org/2000/svg">

        <!-- Ground shadow -->
        <ellipse cx="260" cy="415" rx="180" ry="12" fill="rgba(147,197,253,0.2)"/>

        <!-- ═══ PERSON BODY ═══ -->
        <!-- Legs -->
        <path d="M220 310 Q210 360 195 400" stroke="#7C6FB0" stroke-width="28" stroke-linecap="round" fill="none"/>
        <path d="M255 315 Q265 365 275 400" stroke="#7C6FB0" stroke-width="28" stroke-linecap="round" fill="none"/>
        <!-- Shoes -->
        <ellipse cx="190" cy="402" rx="22" ry="10" fill="#E05252" transform="rotate(-10 190 402)"/>
        <ellipse cx="278" cy="402" rx="22" ry="10" fill="#E05252" transform="rotate(5 278 402)"/>

        <!-- Torso -->
        <path d="M200 220 Q195 280 215 320 L270 320 Q285 280 280 220 Z" fill="#5ECBCB"/>

        <!-- Left arm (holding shield) -->
        <path d="M200 230 Q160 270 155 310" stroke="#5ECBCB" stroke-width="26" stroke-linecap="round" fill="none"/>
        <!-- Right arm (down) -->
        <path d="M278 230 Q310 265 305 305" stroke="#5ECBCB" stroke-width="26" stroke-linecap="round" fill="none"/>

        <!-- Neck -->
        <rect x="228" y="195" width="24" height="30" rx="10" fill="#FBBF7C"/>

        <!-- Head -->
        <ellipse cx="240" cy="175" rx="38" ry="42" fill="#FBBF7C"/>
        <!-- Hair -->
        <path d="M205 158 Q208 120 240 118 Q272 120 275 158 Q265 135 240 133 Q215 135 205 158Z" fill="#2D2D3A"/>
        <!-- Eyes -->
        <ellipse cx="228" cy="172" rx="5" ry="6" fill="#2D2D3A"/>
        <ellipse cx="252" cy="172" rx="5" ry="6" fill="#2D2D3A"/>
        <!-- Smile -->
        <path d="M228 188 Q240 197 252 188" stroke="#2D2D3A" stroke-width="2" fill="none" stroke-linecap="round"/>

        <!-- ═══ SHIELD (left hand area) ═══ -->
        <g transform="translate(60, 240)">
          <path d="M55 10 L90 22 L90 55 C90 78 74 96 55 105 C36 96 20 78 20 55 L20 22 Z"
                fill="white" stroke="#BFDBFE" stroke-width="2.5"/>
          <path d="M55 18 L82 28 L82 54 C82 72 70 87 55 95 C40 87 28 72 28 54 L28 28 Z"
                fill="#DBEAFE" opacity="0.6"/>
          <!-- Check mark -->
          <circle cx="55" cy="58" r="20" fill="#3B82F6" opacity="0.15"/>
          <polyline points="44,58 52,66 68,48" stroke="#3B82F6" stroke-width="3.5"
                    stroke-linecap="round" stroke-linejoin="round" fill="none"/>
        </g>

        <!-- ═══ BROWSER WINDOW (right side, slightly behind person) ═══ -->
        <g transform="translate(270, 80)">
          <!-- Window frame -->
          <rect x="0" y="0" width="200" height="200" rx="14" fill="white"
                stroke="#E2E8F0" stroke-width="2"
                filter="url(#shadow)"/>
          <!-- Title bar -->
          <rect x="0" y="0" width="200" height="36" rx="14" fill="#FCD34D"/>
          <rect x="0" y="22" width="200" height="14" fill="#FCD34D"/>
          <!-- Window dots -->
          <circle cx="18" cy="18" r="5" fill="rgba(255,255,255,0.6)"/>
          <circle cx="34" cy="18" r="5" fill="rgba(255,255,255,0.6)"/>
          <circle cx="50" cy="18" r="5" fill="rgba(255,255,255,0.6)"/>

          <!-- Avatar in window -->
          <circle cx="100" cy="90" r="32" fill="#DBEAFE" stroke="#BFDBFE" stroke-width="2"/>
          <circle cx="100" cy="80" r="16" fill="#93C5FD"/>
          <ellipse cx="100" cy="112" rx="22" ry="12" fill="#93C5FD" opacity="0.5"/>

          <!-- Password field -->
          <rect x="20" y="136" width="160" height="22" rx="11" fill="#F8FAFC" stroke="#E2E8F0" stroke-width="1.5"/>
          <circle cx="42" cy="147" r="4" fill="#94A3B8"/>
          <circle cx="58" cy="147" r="4" fill="#94A3B8"/>
          <circle cx="74" cy="147" r="4" fill="#94A3B8"/>
          <circle cx="90" cy="147" r="4" fill="#94A3B8"/>
          <circle cx="106" cy="147" r="4" fill="#94A3B8"/>
          <circle cx="122" cy="147" r="4" fill="#94A3B8"/>
          <circle cx="138" cy="147" r="4" fill="#94A3B8"/>

          <!-- Login button -->
          <rect x="30" y="168" width="140" height="22" rx="11" fill="#EF4444"/>
          <text x="100" y="183" text-anchor="middle"
                font-family="Plus Jakarta Sans, sans-serif"
                font-size="9" font-weight="700" fill="white">LOGIN</text>
        </g>

        <!-- ═══ DECORATIVE ELEMENTS ═══ -->
        <!-- Curved arrow lines around person -->
        <path d="M170 140 Q140 100 160 70" stroke="#B0C4DE" stroke-width="1.5"
              stroke-dasharray="4 4" fill="none" opacity="0.6"/>
        <path d="M310 130 Q350 90 340 60" stroke="#B0C4DE" stroke-width="1.5"
              stroke-dasharray="4 4" fill="none" opacity="0.6"/>

        <!-- Floating dots -->
        <circle cx="100" cy="100" r="6" fill="#93C5FD" opacity="0.5"/>
        <circle cx="420" cy="380" r="8" fill="#BFDBFE" opacity="0.6"/>
        <circle cx="450" cy="120" r="5" fill="#DBEAFE" opacity="0.8"/>
        <circle cx="80" cy="370" r="7" fill="#93C5FD" opacity="0.4"/>
        <circle cx="480" cy="260" r="4" fill="#BFDBFE" opacity="0.5"/>
        <circle cx="150" cy="400" r="5" fill="#DBEAFE" opacity="0.5"/>
        <circle cx="400" cy="60" r="6" fill="#93C5FD" opacity="0.3"/>

        <!-- Small star/sparkle -->
        <g transform="translate(130, 200)" opacity="0.5">
          <line x1="8" y1="0" x2="8" y2="16" stroke="#93C5FD" stroke-width="1.5" stroke-linecap="round"/>
          <line x1="0" y1="8" x2="16" y2="8" stroke="#93C5FD" stroke-width="1.5" stroke-linecap="round"/>
        </g>
        <g transform="translate(390, 300)" opacity="0.4">
          <line x1="6" y1="0" x2="6" y2="12" stroke="#93C5FD" stroke-width="1.5" stroke-linecap="round"/>
          <line x1="0" y1="6" x2="12" y2="6" stroke="#93C5FD" stroke-width="1.5" stroke-linecap="round"/>
        </g>

        <defs>
          <filter id="shadow" x="-10%" y="-10%" width="120%" height="120%">
            <feDropShadow dx="0" dy="4" stdDeviation="6" flood-color="rgba(0,0,0,0.08)"/>
          </filter>
        </defs>
      </svg>
    </div>
  </div>

  <!-- ── Right Panel ── -->
  <div class="right">
    <div class="form-wrap">

      <div class="form-logo">
        <img src="{{ asset('assets/images/logos/Pv Market Logo-01.png') }}" alt="PV Market"/>
      </div>

      <div class="form-top">
        <h2>Welcome to PV Market</h2>
      </div>

      @if ($errors->any())
        <div class="alert error" role="alert">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
          </svg>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      @if (session('success'))
        <div class="alert success" role="alert">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
          </svg>
          <span>{{ session('success') }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <div class="field">
          <label for="email">Email Address</label>
          <div class="input-wrap">
            <input
              type="email" id="email" name="email"
              placeholder="you@powernsun.com"
              value="{{ old('email') }}"
              autocomplete="username"
              class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
              required
            />
          </div>
          @if($errors->has('email'))
            <div class="field-meta">
              <span class="field-error">{{ $errors->first('email') }}</span>
            </div>
          @endif
        </div>

        <div class="field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <input
              type="password" id="password" name="password"
              placeholder="Enter your password"
              autocomplete="current-password"
              class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
              style="padding-right:42px;"
              required
            />
            <button type="button" class="toggle-pass" id="togglePass">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
          <div class="field-meta">
            <span class="field-error">{{ $errors->first('password') }}</span>
            <a href="#" class="forgot">Forgot password?</a>
          </div>
        </div>

        <div class="remember">
          <input type="checkbox" id="remember" name="remember"/>
          <label for="remember">Remember this Device</label>
        </div>

        <button type="submit" class="btn-submit">Login</button>

      </form>

    </div>
  </div>
</div>

<script>
  document.getElementById('togglePass').addEventListener('click', () => {
    const input = document.getElementById('password');
    const isPass = input.type === 'password';
    input.type = isPass ? 'text' : 'password';
    document.getElementById('togglePass').innerHTML = isPass
      ? `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
           <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
           <line x1="1" y1="1" x2="23" y2="23"/>
         </svg>`
      : `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
           <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
           <circle cx="12" cy="12" r="3"/>
         </svg>`;
  });
</script>
</body>
</html>