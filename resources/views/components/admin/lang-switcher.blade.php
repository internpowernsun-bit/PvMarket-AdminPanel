{{--
    LANGUAGE SWITCHER
    Include in layouts/admin.blade.php inside .topbar-right:
    @include('components.admin.lang-switcher')
--}}

@php
    use App\Services\TranslationService;
    $allLanguages = TranslationService::$languages;
    $currentLang  = session('admin_lang', 'en');
    $currentFlag  = $currentLang === 'en' ? '🇬🇧' : ($allLanguages[$currentLang]['flag'] ?? '🌐');
    $currentCode  = strtoupper($currentLang);
@endphp

<div class="lang-sw-wrap" id="langSwWrap">

    {{-- Trigger Button --}}
    <button class="lang-sw-btn" id="langSwBtn" onclick="toggleLangSw(event)" title="Switch Language">
        <span class="lang-sw-flag">{{ $currentFlag }}</span>
        <span class="lang-sw-code">{{ $currentCode }}</span>
        <svg width="9" height="9" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    </button>

    {{-- Dropdown Panel --}}
    <div class="lang-sw-dropdown" id="langSwDropdown">

        <div class="lang-sw-header">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/>
                <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
            </svg>
            Language
        </div>

        {{-- English --}}
        <button class="lang-sw-option {{ $currentLang === 'en' ? 'active' : '' }}"
                onclick="switchLang('en', '🇬🇧', 'English', false)">
            <span class="lang-sw-opt-flag">🇬🇧</span>
            <div class="lang-sw-opt-info">
                <span class="lang-sw-opt-name">English</span>
                <span class="lang-sw-opt-native">English</span>
            </div>
            @if($currentLang === 'en') <span class="lang-sw-check">✓</span> @endif
        </button>

        @foreach($allLanguages as $code => $info)
        <button class="lang-sw-option {{ $currentLang === $code ? 'active' : '' }}"
                onclick="switchLang('{{ $code }}', '{{ $info['flag'] }}', '{{ $info['name'] }}', {{ $info['rtl'] ? 'true' : 'false' }})">
            <span class="lang-sw-opt-flag">{{ $info['flag'] }}</span>
            <div class="lang-sw-opt-info">
                <span class="lang-sw-opt-name">{{ $info['name'] }}</span>
                <span class="lang-sw-opt-native">{{ $info['native'] }}</span>
            </div>
            @if($currentLang === $code) <span class="lang-sw-check">✓</span> @endif
        </button>
        @endforeach

    </div>
</div>

{{-- Loading Overlay --}}
<div class="lang-sw-loading" id="langSwLoading" style="display:none;">
    <div class="lang-sw-loading-box">
        <div class="lang-sw-spinner"></div>
        <div class="lang-sw-loading-flag" id="langSwLoadingFlag"></div>
        <div class="lang-sw-loading-text" id="langSwLoadingText">Switching language...</div>
        <div class="lang-sw-progress-wrap">
            <div class="lang-sw-progress-bar" id="langSwProgressBar" style="width:0%"></div>
        </div>
        <div class="lang-sw-progress-label" id="langSwProgressLabel">Please wait...</div>
    </div>
</div>

<style>
.lang-sw-wrap { position: relative; }

.lang-sw-btn {
    display: flex; align-items: center; gap: 5px;
    padding: 6px 10px;
    background: white;
    border: 1.5px solid var(--border);
    border-radius: 8px;
    cursor: pointer;
    font-family: inherit;
    font-size: 12px; font-weight: 700;
    color: var(--text);
    transition: all .15s;
    white-space: nowrap;
}
.lang-sw-btn:hover {
    border-color: var(--primary);
    background: var(--primary-l);
    color: var(--primary-d);
}
.lang-sw-flag { font-size: 17px; line-height: 1; }
.lang-sw-code { font-size: 12px; font-weight: 700; letter-spacing: .5px; }

.lang-sw-dropdown {
    display: none;
    position: absolute;
    top: calc(100% + 8px); right: 0;
    background: white;
    border: 1px solid var(--border);
    border-radius: 14px;
    box-shadow: 0 10px 36px rgba(0,0,0,.14);
    min-width: 220px; max-height: 420px;
    overflow-y: auto;
    z-index: 500;
    animation: langDropIn .15s ease;
}
.lang-sw-dropdown.open { display: block; }
.lang-sw-dropdown::-webkit-scrollbar { width: 3px; }
.lang-sw-dropdown::-webkit-scrollbar-thumb { background: var(--primary-m); border-radius: 3px; }

@keyframes langDropIn {
    from { opacity: 0; transform: translateY(-6px); }
    to   { opacity: 1; transform: translateY(0); }
}

.lang-sw-header {
    display: flex; align-items: center; gap: 7px;
    padding: 12px 14px 8px;
    font-size: 10px; font-weight: 700;
    color: var(--muted);
    text-transform: uppercase; letter-spacing: 1px;
    border-bottom: 1px solid var(--border);
}

.lang-sw-option {
    display: flex; align-items: center; gap: 10px;
    padding: 9px 14px; width: 100%;
    background: none; border: none;
    cursor: pointer; font-family: inherit;
    text-align: left; transition: background .1s;
}
.lang-sw-option:hover  { background: var(--primary-l); }
.lang-sw-option.active { background: var(--primary-l); color: var(--primary-d); }

.lang-sw-opt-flag { font-size: 22px; flex-shrink: 0; }
.lang-sw-opt-info { flex: 1; display: flex; flex-direction: column; gap: 1px; }
.lang-sw-opt-name   { font-size: 13px; font-weight: 600; color: var(--text); }
.lang-sw-opt-native { font-size: 11px; color: var(--muted); }
.lang-sw-check { color: var(--primary); font-weight: 800; font-size: 14px; margin-left: auto; }

/* ── Loading overlay ── */
.lang-sw-loading {
    position: fixed; inset: 0;
    background: rgba(255,255,255,.88);
    backdrop-filter: blur(4px);
    z-index: 9999;
    align-items: center; justify-content: center;
}
.lang-sw-loading-box {
    background: white;
    border: 1px solid var(--border);
    border-radius: 16px;
    padding: 32px 40px;
    text-align: center;
    box-shadow: 0 12px 40px rgba(0,0,0,.14);
    min-width: 300px;
}
.lang-sw-spinner {
    width: 36px; height: 36px;
    border: 3px solid var(--primary-m);
    border-top-color: var(--primary);
    border-radius: 50%;
    animation: langSpin .7s linear infinite;
    margin: 0 auto 14px;
}
@keyframes langSpin { to { transform: rotate(360deg); } }
.lang-sw-loading-flag  { font-size: 36px; margin-bottom: 10px; }
.lang-sw-loading-text  { font-size: 15px; font-weight: 700; color: var(--text); margin-bottom: 16px; }
.lang-sw-progress-wrap { background: var(--border); border-radius: 8px; height: 6px; overflow: hidden; margin-bottom: 8px; }
.lang-sw-progress-bar  { height: 100%; background: var(--primary); border-radius: 8px; transition: width .3s ease; }
.lang-sw-progress-label { font-size: 12px; color: var(--muted); min-height: 18px; }
</style>

<script>
function toggleLangSw(e) {
    e.stopPropagation();
    document.getElementById('langSwDropdown').classList.toggle('open');
}

document.addEventListener('click', function(e) {
    const wrap = document.getElementById('langSwWrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('langSwDropdown').classList.remove('open');
    }
});

const CSRF = () => document.querySelector('meta[name="csrf-token"]').content;

function setProgress(pct, label) {
    document.getElementById('langSwProgressBar').style.width   = Math.min(pct, 100) + '%';
    document.getElementById('langSwProgressLabel').textContent = label;
}

async function switchLang(code, flag, name, isRtl) {
    document.getElementById('langSwDropdown').classList.remove('open');

    // Show overlay
    const overlay = document.getElementById('langSwLoading');
    document.getElementById('langSwLoadingFlag').textContent = flag;
    document.getElementById('langSwLoadingText').textContent = code === 'en'
        ? 'Switching to English...'
        : `Switching to ${name}...`;
    setProgress(5, 'Saving language preference...');
    overlay.style.display = 'flex';

    // ── STEP 1: Save language to session ─────────────────────────
    let models = [];
    try {
        const res  = await fetch('{{ route("admin.translations.switch-language") }}', {
            method:  'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF() },
            body:    JSON.stringify({ lang: code }),
        });

        if (!res.ok) throw new Error(`HTTP ${res.status}`);

        const data = await res.json();
        if (!data.success) throw new Error(data.error || 'Switch failed');

        models = data.models || []; // server tells us which models need translating

    } catch (err) {
        overlay.style.display = 'none';
        alert('Language switch failed:\n' + err.message);
        return;
    }

    // ── STEP 2: If English, just reload ──────────────────────────
    if (models.length === 0) {
        setProgress(100, 'Done!');
        document.documentElement.setAttribute('dir',  isRtl ? 'rtl' : 'ltr');
        document.documentElement.setAttribute('lang', code);
        setTimeout(() => window.location.reload(), 400);
        return;
    }

    // ── STEP 3: Translate each model one at a time ───────────────
    // Each request is small → never hits the 30s PHP timeout
    document.getElementById('langSwLoadingText').textContent = `Translating to ${name}...`;
    setProgress(10, 'Starting translations...');

    const total = models.length;
    let done    = 0;

    for (const model of models) {
        setProgress(10 + (done / total) * 85, `Translating ${model}s → ${name}...`);

        try {
            const res = await fetch('{{ route("admin.translations.translate-all") }}', {
                method:  'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF() },
                body:    JSON.stringify({ model: model, target_lang: code }),
            });

            // Don't fail the whole process if one model has an error
            if (res.ok) {
                const data = await res.json();
                if (data.translated > 0) {
                    setProgress(10 + ((done + 1) / total) * 85,
                        `✓ ${model}: translated ${data.translated} records`);
                } else {
                    setProgress(10 + ((done + 1) / total) * 85,
                        `✓ ${model}: already up to date`);
                }
            }
        } catch (e) {
            // Log but continue — don't block the user
            console.warn(`Translation failed for ${model}:`, e.message);
        }

        done++;
    }

    // ── STEP 4: Done ─────────────────────────────────────────────
    setProgress(100, `✅ All done — loading page...`);
    document.documentElement.setAttribute('dir',  isRtl ? 'rtl' : 'ltr');
    document.documentElement.setAttribute('lang', code);

    setTimeout(() => window.location.reload(), 600);
}
</script>