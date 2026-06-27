<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi OTP – AI Expense Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; }

        /* ── Left Panel ── */
        .left-panel {
            background: linear-gradient(160deg, #0f172a 0%, #1e3a8a 35%, #1d4ed8 70%, #2563eb 100%);
            position: relative; overflow: hidden;
        }
        .dot-bg {
            background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 26px 26px;
            position: absolute; inset: 0;
        }
        .blob1 { position:absolute; top:-80px; left:-80px; width:320px; height:320px; background:rgba(99,102,241,0.35); border-radius:50%; filter:blur(80px); }
        .blob2 { position:absolute; bottom:-100px; right:-60px; width:280px; height:280px; background:rgba(59,130,246,0.4); border-radius:50%; filter:blur(70px); }
        .glass-card { background:rgba(255,255,255,0.1); backdrop-filter:blur(12px); border:1px solid rgba(255,255,255,0.2); border-radius:16px; }
        @keyframes floatAnim { 0%,100%{transform:translateY(0px)} 50%{transform:translateY(-10px)} }
        .float { animation:floatAnim 4s ease-in-out infinite; }
        .pill { background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.22); color:#fff; font-size:11px; font-weight:500; padding:6px 12px; border-radius:999px; }

        /* ── OTP Inputs ── */
        .otp-inputs { display:flex; gap:12px; justify-content:center; margin-bottom:28px; }
        .otp-digit {
            width: 56px; height: 64px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            font-size: 28px; font-weight: 700; color: #0f172a;
            text-align: center;
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, transform 0.1s;
            font-family: 'Inter', sans-serif;
            caret-color: transparent;
        }
        .otp-digit:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
            transform: scale(1.06);
        }
        .otp-digit.filled {
            border-color: #2563eb;
            background: #eff6ff;
        }
        .otp-digit.error { border-color: #ef4444 !important; background: #fff1f2; }

        .btn-primary {
            width:100%; background:linear-gradient(135deg,#1d4ed8,#2563eb);
            color:white; font-weight:600; font-size:15px; padding:14px;
            border-radius:12px; border:none; cursor:pointer;
            display:flex; align-items:center; justify-content:center; gap:8px;
            transition:all 0.2s; box-shadow:0 8px 24px rgba(37,99,235,0.35);
            font-family:'Inter',sans-serif;
        }
        .btn-primary:hover { background:linear-gradient(135deg,#1e40af,#1d4ed8); transform:translateY(-1px); box-shadow:0 12px 30px rgba(37,99,235,0.4); }
        .btn-primary:active { transform:translateY(0); }
        .btn-primary:disabled { opacity:0.5; cursor:not-allowed; transform:none; }

        /* ── Countdown ── */
        @keyframes countdown-pulse { 0%,100%{opacity:1} 50%{opacity:0.6} }
        .countdown-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: #fef3c7; border: 1px solid #fde68a;
            color: #92400e; font-size: 12px; font-weight: 600;
            padding: 6px 14px; border-radius: 999px;
        }
        .countdown-badge.urgent { background: #fee2e2; border-color: #fca5a5; color: #991b1b; animation: countdown-pulse 1s ease-in-out infinite; }
        .countdown-badge.expired { background: #f3f4f6; border-color: #d1d5db; color: #6b7280; }

        /* ── Step indicator ── */
        .step-line { flex:1; height:2px; background:rgba(255,255,255,0.15); }
        .step-done  .step-line { background:rgba(74,222,128,0.6); }

        @media(max-width:1024px) {
            .left-panel { display:none !important; }
            body > div:last-child { width:100% !important; }
        }
    </style>
</head>
<body style="min-height:100vh; display:flex;">

{{-- ═══ LEFT PANEL ═══ --}}
<div class="left-panel" style="width:50%; display:flex; flex-direction:column; justify-content:space-between; padding:40px 48px;">
    <div class="dot-bg"></div>
    <div class="blob1"></div>
    <div class="blob2"></div>

    {{-- Logo --}}
    <div style="position:relative; z-index:10; display:flex; align-items:center; gap:12px;">
        <div style="width:40px; height:40px; background:rgba(255,255,255,0.15); border:1px solid rgba(255,255,255,0.3); border-radius:12px; display:flex; align-items:center; justify-content:center;">
            <svg width="20" height="20" fill="none" stroke="white" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <span style="color:white; font-weight:700; font-size:16px;">AI Expense Tracker</span>
    </div>

    {{-- Center Content --}}
    <div style="position:relative; z-index:10;">
        <h1 style="color:white; font-size:34px; font-weight:800; line-height:1.2; margin:0 0 12px;">
            Hampir Selesai!<br>
            <span style="color:#93c5fd;">Verifikasi Reset Password 🔐</span>
        </h1>
        <p style="color:#bfdbfe; font-size:14px; line-height:1.7; margin:0 0 28px; max-width:300px;">
            Kami telah mengirim kode OTP 6 digit ke email Anda. Masukkan kode tersebut untuk mereset password Anda.
        </p>

        {{-- Step progress --}}
        <div class="glass-card float" style="padding:20px; max-width:310px; margin-bottom:20px;">
            <p style="color:#93c5fd; font-size:11px; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 16px;">Progress Reset Password</p>
            <div style="display:flex; align-items:center; gap:6px;">
                {{-- Step 1 done --}}
                <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="width:32px; height:32px; background:#4ade80; border-radius:50%; display:flex; align-items:center; justify-content:center;">
                        <svg width="16" height="16" fill="white" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                    <p style="color:#4ade80; font-size:10px; font-weight:600; margin:0; text-align:center;">Minta<br>Reset</p>
                </div>
                <div style="flex:1; height:2px; background:rgba(74,222,128,0.5); border-radius:999px;"></div>
                {{-- Step 2 active --}}
                <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="width:32px; height:32px; background:#2563eb; border:2px solid #93c5fd; border-radius:50%; display:flex; align-items:center; justify-content:center; animation:floatAnim 2s ease-in-out infinite;">
                        <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <p style="color:white; font-size:10px; font-weight:600; margin:0; text-align:center;">Verifikasi<br>Email</p>
                </div>
                <div style="flex:1; height:2px; background:rgba(255,255,255,0.15); border-radius:999px;"></div>
                {{-- Step 3 pending --}}
                <div style="display:flex; flex-direction:column; align-items:center; gap:4px;">
                    <div style="width:32px; height:32px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.3); border-radius:50%; display:flex; align-items:center; justify-content:center;">
                        <svg width="14" height="14" fill="none" stroke="white" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p style="color:#93c5fd; font-size:10px; font-weight:600; margin:0; text-align:center;">Reset<br>Selesai</p>
                </div>
            </div>
        </div>

        {{-- Email hint --}}
        <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(37,99,235,0.2); border:1px solid rgba(37,99,235,0.4); padding:10px 16px; border-radius:12px; max-width:310px;">
            <svg width="16" height="16" fill="none" stroke="#93c5fd" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span style="color:#bfdbfe; font-size:13px;">Cek folder <strong style="color:white;">Spam/Junk</strong> jika tidak ada</span>
        </div>
    </div>

    {{-- Pills --}}
    <div style="position:relative; z-index:10; display:flex; flex-wrap:wrap; gap:8px;">
        @foreach(['🔒 Aman & Terenkripsi','⚡ Berlaku 10 Menit','📧 Kirim Ulang Gratis'] as $f)
            <span class="pill">{{ $f }}</span>
        @endforeach
    </div>
</div>

{{-- ═══ RIGHT PANEL: OTP Form ═══ --}}
<div style="width:50%; background:#f8fafc; display:flex; align-items:center; justify-content:center; padding:40px 24px; overflow-y:auto;">
    <div style="width:100%; max-width:420px;">

        {{-- Header --}}
        <div style="text-align:center; margin-bottom:32px;">
            <div style="width:72px; height:72px; background:linear-gradient(135deg,#1d4ed8,#2563eb); border-radius:20px; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; box-shadow:0 8px 24px rgba(37,99,235,0.3);">
                <svg width="34" height="34" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <h2 style="font-size:24px; font-weight:800; color:#0f172a; margin:0 0 8px;">Verifikasi Email 📩</h2>
            <p style="font-size:14px; color:#64748b; margin:0; line-height:1.6;">
                Kode OTP dikirim ke<br>
                <strong style="color:#1d4ed8;">{{ $email }}</strong>
            </p>
        </div>

        {{-- Countdown Timer --}}
        <div style="text-align:center; margin-bottom:24px;">
            <div class="countdown-badge" id="countdown-badge">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span id="countdown-text">10:00</span>
            </div>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div style="background:#f0fdf4; border:1px solid #86efac; color:#166534; padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if ($errors->any())
            <div style="background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:20px; display:flex; align-items:start; gap:10px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p style="margin:0 0 2px;">{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- OTP Form --}}
        <form method="POST" action="{{ route('password.otp.verify') }}" id="otp-form">
            @csrf

            <p style="font-size:13px; font-weight:600; color:#374151; text-align:center; margin-bottom:16px;">
                Masukkan 6 digit kode OTP
            </p>

            {{-- 6 individual digit boxes --}}
            <div class="otp-inputs" id="otp-container">
                @for ($i = 1; $i <= 6; $i++)
                    <input
                        type="text"
                        inputmode="numeric"
                        maxlength="1"
                        pattern="[0-9]"
                        class="otp-digit {{ $errors->has('otp') ? 'error' : '' }}"
                        id="otp-{{ $i }}"
                        autocomplete="off"
                        data-index="{{ $i }}"
                    >
                @endfor
            </div>

            {{-- Hidden input holding the combined value --}}
            <input type="hidden" name="otp" id="otp-combined">

            <button type="submit" class="btn-primary" id="submit-btn" disabled>
                <svg width="18" height="18" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Verifikasi & Lanjut Reset Password
            </button>
        </form>

        {{-- Resend --}}
        <div style="margin-top:24px; text-align:center;">
            <p style="font-size:13px; color:#64748b; margin-bottom:12px;">Tidak menerima kode?</p>
            <form method="POST" action="{{ route('password.otp.resend') }}" id="resend-form">
                @csrf
                <button type="submit" id="resend-btn"
                    style="background:none; border:1.5px solid #e2e8f0; border-radius:10px; padding:10px 20px; font-size:13px; font-weight:600; color:#1d4ed8; cursor:pointer; font-family:'Inter',sans-serif; transition:all 0.2s; display:inline-flex; align-items:center; gap:8px; width:100%; justify-content:center;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Kirim Ulang Kode OTP
                </button>
            </form>
        </div>

        {{-- Back link --}}
        <p style="text-align:center; font-size:13px; color:#94a3b8; margin-top:20px;">
            <a href="{{ route('register') }}" style="color:#64748b; text-decoration:none; font-weight:500;">← Kembali ke Pendaftaran</a>
        </p>
    </div>
</div>

<script>
    // ── OTP digit box behaviour ──────────────────────────────────────────────
    const digits   = document.querySelectorAll('.otp-digit');
    const combined = document.getElementById('otp-combined');
    const submitBtn= document.getElementById('submit-btn');

    function updateCombined() {
        let val = '';
        digits.forEach(d => { val += d.value; });
        combined.value = val;
        submitBtn.disabled = val.length < 6;

        digits.forEach(d => {
            if (d.value) d.classList.add('filled');
            else         d.classList.remove('filled');
        });
    }

    digits.forEach((input, idx) => {
        input.addEventListener('keydown', e => {
            if (e.key === 'Backspace' && !input.value && idx > 0) {
                digits[idx - 1].focus();
                digits[idx - 1].value = '';
                updateCombined();
            }
        });

        input.addEventListener('input', e => {
            // Allow only digits
            input.value = input.value.replace(/[^0-9]/g, '').slice(-1);
            updateCombined();
            if (input.value && idx < digits.length - 1) {
                digits[idx + 1].focus();
            }
        });

        input.addEventListener('paste', e => {
            e.preventDefault();
            const pasted = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            pasted.split('').slice(0, 6).forEach((ch, i) => {
                if (digits[i]) digits[i].value = ch;
            });
            const next = Math.min(pasted.length, 5);
            digits[next].focus();
            updateCombined();
        });
    });

    // Focus first box on load
    digits[0].focus();

    // ── Countdown timer ──────────────────────────────────────────────────────
    const badge    = document.getElementById('countdown-badge');
    const countTxt = document.getElementById('countdown-text');
    let seconds    = 10 * 60; // 10 minutes

    function tick() {
        if (seconds <= 0) {
            clearInterval(timer);
            countTxt.textContent = 'Kedaluwarsa';
            badge.className = 'countdown-badge expired';
            submitBtn.disabled = true;
            submitBtn.textContent = 'OTP Kedaluwarsa — Minta Kode Baru';
            return;
        }
        seconds--;
        const m = String(Math.floor(seconds / 60)).padStart(2, '0');
        const s = String(seconds % 60).padStart(2, '0');
        countTxt.textContent = `${m}:${s}`;

        if (seconds <= 60) {
            badge.className = 'countdown-badge urgent';
        }
    }

    const timer = setInterval(tick, 1000);

    // ── Resend button cooldown ───────────────────────────────────────────────
    const resendBtn = document.getElementById('resend-btn');
    let resendCooldown = 60;

    function startResendCooldown() {
        resendBtn.disabled = true;
        const orig = resendBtn.innerHTML;
        const cd = setInterval(() => {
            resendCooldown--;
            resendBtn.innerHTML = `
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Kirim ulang dalam ${resendCooldown}s`;
            if (resendCooldown <= 0) {
                clearInterval(cd);
                resendBtn.disabled = false;
                resendBtn.innerHTML = orig;
                resendCooldown = 60;
            }
        }, 1000);
    }

    document.getElementById('resend-form').addEventListener('submit', () => {
        startResendCooldown();
    });
</script>

</body>
</html>
