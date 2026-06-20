<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar – AI Expense Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; }

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
        .mini-stat { background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); border-radius:12px; }
        @keyframes floatAnim { 0%,100%{transform:translateY(0px)} 50%{transform:translateY(-10px)} }
        .float { animation:floatAnim 4s ease-in-out infinite; }
        .pill { background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.22); color:#fff; font-size:11px; font-weight:500; padding:6px 12px; border-radius:999px; }

        .form-input {
            width:100%; padding:12px 16px 12px 44px;
            border:1.5px solid #e5e7eb; border-radius:12px;
            font-size:14px; color:#111827; background:#fff;
            outline:none; transition:border-color 0.2s, box-shadow 0.2s;
            font-family:'Inter',sans-serif;
        }
        .form-input:focus { border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,0.12); }
        .form-input::placeholder { color:#9ca3af; }
        .input-icon { position:absolute; left:14px; top:50%; transform:translateY(-50%); color:#9ca3af; width:18px; height:18px; }
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
        .label { display:block; font-size:13px; font-weight:500; color:#374151; margin-bottom:6px; }
        .progress-bar { width:100%; background:rgba(255,255,255,0.12); border-radius:999px; height:4px; margin-top:8px; overflow:hidden; }
        .progress-green { height:4px; background:#4ade80; border-radius:999px; width:75%; }
        .progress-red { height:4px; background:#f87171; border-radius:999px; width:50%; }

        /* Steps indicator */
        .step-badge {
            width:28px; height:28px; border-radius:50%;
            display:flex; align-items:center; justify-content:center;
            font-size:12px; font-weight:700;
        }
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

    {{-- Hero + Preview --}}
    <div style="position:relative; z-index:10;">
        <h1 style="color:white; font-size:36px; font-weight:800; line-height:1.2; margin:0 0 12px;">
            Mulai Perjalanan<br>
            Keuangan Anda<br>
            <span style="color:#93c5fd;">Bersama AI 🚀</span>
        </h1>
        <p style="color:#bfdbfe; font-size:14px; line-height:1.7; margin:0 0 28px; max-width:300px;">
            Daftar gratis dalam 30 detik. Tidak perlu kartu kredit. Mulai lacak keuangan Anda hari ini.
        </p>

        {{-- Step Guide --}}
        <div class="glass-card float" style="padding:20px; max-width:310px; margin-bottom:20px;">
            <p style="color:#93c5fd; font-size:11px; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 16px;">Cara Mulai</p>

            <div style="display:flex; flex-direction:column; gap:14px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div class="step-badge" style="background:#2563eb; color:white; flex-shrink:0;">1</div>
                    <div>
                        <p style="color:white; font-size:13px; font-weight:600; margin:0;">Buat Akun Gratis</p>
                        <p style="color:#93c5fd; font-size:11px; margin:0;">Daftar dengan email Anda</p>
                    </div>
                    <svg width="16" height="16" fill="#4ade80" viewBox="0 0 20 20" style="margin-left:auto; flex-shrink:0;">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>

                <div style="margin-left:14px; width:1px; height:12px; background:rgba(255,255,255,0.2);"></div>

                <div style="display:flex; align-items:center; gap:12px;">
                    <div class="step-badge" style="background:rgba(255,255,255,0.15); color:white; flex-shrink:0; border:1px solid rgba(255,255,255,0.3);">2</div>
                    <div>
                        <p style="color:white; font-size:13px; font-weight:600; margin:0;">Tambah Transaksi</p>
                        <p style="color:#93c5fd; font-size:11px; margin:0;">AI otomatis kategorikan</p>
                    </div>
                </div>

                <div style="margin-left:14px; width:1px; height:12px; background:rgba(255,255,255,0.2);"></div>

                <div style="display:flex; align-items:center; gap:12px;">
                    <div class="step-badge" style="background:rgba(255,255,255,0.15); color:white; flex-shrink:0; border:1px solid rgba(255,255,255,0.3);">3</div>
                    <div>
                        <p style="color:white; font-size:13px; font-weight:600; margin:0;">Lihat Analitik</p>
                        <p style="color:#93c5fd; font-size:11px; margin:0;">Chart & laporan otomatis</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Free Badge --}}
        <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(74,222,128,0.15); border:1px solid rgba(74,222,128,0.3); padding:10px 16px; border-radius:12px; max-width:310px;">
            <svg width="18" height="18" fill="#4ade80" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span style="color:#4ade80; font-size:13px; font-weight:600;">Gratis hingga 30 transaksi/bulan</span>
        </div>
    </div>

    {{-- Feature Pills --}}
    <div style="position:relative; z-index:10; display:flex; flex-wrap:wrap; gap:8px;">
        @foreach(['🤖 AI Kategorisasi','📊 Analitik Real-time','📄 Export Laporan','🔒 Data Aman','⚡ Setup 30 detik'] as $f)
            <span class="pill">{{ $f }}</span>
        @endforeach
    </div>
</div>

{{-- ═══ RIGHT PANEL: Form ═══ --}}
<div style="width:50%; background:#f8fafc; display:flex; align-items:center; justify-content:center; padding:40px 24px; overflow-y:auto;">
    <div style="width:100%; max-width:420px;">

        {{-- Header --}}
        <div style="margin-bottom:28px;">
            <h2 style="font-size:26px; font-weight:800; color:#0f172a; margin:0 0 6px;">Buat Akun Baru 🎉</h2>
            <p style="font-size:14px; color:#64748b; margin:0;">Daftar gratis dan mulai kelola keuangan Anda</p>
        </div>

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

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name --}}
            <div style="margin-bottom:16px;">
                <label class="label" for="name">Nama Lengkap</label>
                <div style="position:relative;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-input" placeholder="Nama lengkap Anda" required autofocus>
                </div>
            </div>

            {{-- Email --}}
            <div style="margin-bottom:16px;">
                <label class="label" for="email">Alamat Email</label>
                <div style="position:relative;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="nama@email.com" required>
                </div>
            </div>

            {{-- Password --}}
            <div style="margin-bottom:16px;">
                <label class="label" for="password">Password</label>
                <div style="position:relative;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input id="password" type="password" name="password" class="form-input" placeholder="Minimal 8 karakter" required>
                </div>
            </div>

            {{-- Confirm Password --}}
            <div style="margin-bottom:24px;">
                <label class="label" for="password_confirmation">Konfirmasi Password</label>
                <div style="position:relative;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password" required>
                </div>
            </div>

            {{-- Free Plan Info --}}
            <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; padding:12px 16px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                <svg width="18" height="18" fill="#2563eb" viewBox="0 0 20 20" style="flex-shrink:0;">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <p style="font-size:12px; color:#1e40af; margin:0; line-height:1.5;">
                    Akun <strong>Free</strong> — 30 transaksi/bulan gratis. Upgrade ke <strong>Premium</strong> kapan saja untuk fitur lengkap.
                </p>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary">
                Buat Akun Sekarang
                <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </button>
        </form>

        {{-- Divider --}}
        <div style="display:flex; align-items:center; gap:16px; margin:20px 0;">
            <div style="flex:1; height:1px; background:#e2e8f0;"></div>
            <span style="font-size:12px; color:#94a3b8; font-weight:500;">atau</span>
            <div style="flex:1; height:1px; background:#e2e8f0;"></div>
        </div>

        {{-- Login --}}
        <p style="text-align:center; font-size:14px; color:#64748b; margin:0;">
            Sudah punya akun?
            <a href="{{ route('login') }}" style="color:#2563eb; font-weight:600; text-decoration:none;">Masuk sekarang →</a>
        </p>
    </div>
</div>

</body>
</html>
