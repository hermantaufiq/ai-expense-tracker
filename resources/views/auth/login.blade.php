<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login – AI Expense Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; }

        .left-panel {
            background: linear-gradient(160deg, #0f172a 0%, #1e3a8a 35%, #1d4ed8 70%, #2563eb 100%);
            position: relative;
            overflow: hidden;
        }
        .dot-bg {
            background-image: radial-gradient(circle, rgba(255,255,255,0.08) 1px, transparent 1px);
            background-size: 26px 26px;
            position: absolute; inset: 0;
        }
        .blob1 {
            position: absolute; top: -80px; left: -80px;
            width: 320px; height: 320px;
            background: rgba(99,102,241,0.35);
            border-radius: 50%; filter: blur(80px);
        }
        .blob2 {
            position: absolute; bottom: -100px; right: -60px;
            width: 280px; height: 280px;
            background: rgba(59,130,246,0.4);
            border-radius: 50%; filter: blur(70px);
        }
        .glass-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
        }
        .mini-stat {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 12px;
        }
        @keyframes floatAnim {
            0%,100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float { animation: floatAnim 4s ease-in-out infinite; }

        .form-input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 1.5px solid #e5e7eb;
            border-radius: 12px;
            font-size: 14px;
            color: #111827;
            background: #fff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'Inter', sans-serif;
        }
        .form-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
        }
        .form-input::placeholder { color: #9ca3af; }
        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; width: 18px; height: 18px;
        }
        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: white;
            font-weight: 600;
            font-size: 15px;
            padding: 14px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 8px 24px rgba(37,99,235,0.35);
            font-family: 'Inter', sans-serif;
        }
        .btn-primary:hover { background: linear-gradient(135deg, #1e40af, #1d4ed8); transform: translateY(-1px); box-shadow: 0 12px 30px rgba(37,99,235,0.4); }
        .btn-primary:active { transform: translateY(0); }
        .label { display: block; font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 6px; }
        .pill { background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); color: #fff; font-size: 11px; font-weight: 500; padding: 6px 12px; border-radius: 999px; }
        .progress-bar { width: 100%; background: rgba(255,255,255,0.12); border-radius: 999px; height: 4px; margin-top: 8px; overflow: hidden; }
        .progress-fill-green { height: 4px; background: #4ade80; border-radius: 999px; width: 75%; }
        .progress-fill-red { height: 4px; background: #f87171; border-radius: 999px; width: 50%; }
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
        <h1 style="color:white; font-size:38px; font-weight:800; line-height:1.2; margin:0 0 12px;">
            Kelola Keuangan<br>
            Anda Lebih<br>
            <span style="color:#93c5fd;">Cerdas dengan AI</span>
        </h1>
        <p style="color:#bfdbfe; font-size:14px; line-height:1.7; margin:0 0 32px; max-width:300px;">
            Kategorisasi otomatis, analitik visual, dan laporan keuangan — semua dalam satu platform pintar.
        </p>

        {{-- Dashboard Mock Cards --}}
        <div class="float" style="max-width:320px;">
            {{-- Balance Card --}}
            <div class="glass-card" style="padding:20px; margin-bottom:12px;">
                <p style="color:#93c5fd; font-size:11px; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 6px;">Saldo Bulan Ini</p>
                <p style="color:white; font-size:28px; font-weight:800; margin:0 0 6px;">Rp 4.250.000</p>
                <div style="display:flex; align-items:center; gap:6px;">
                    <svg width="14" height="14" fill="#4ade80" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <span style="color:#4ade80; font-size:12px; font-weight:600;">+12.5% dari bulan lalu</span>
                </div>
            </div>

            {{-- Mini Stats --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:12px;">
                <div class="mini-stat" style="padding:14px;">
                    <p style="color:#93c5fd; font-size:11px; margin:0 0 4px;">Pemasukan</p>
                    <p style="color:white; font-size:16px; font-weight:700; margin:0;">Rp 8.5jt</p>
                    <div class="progress-bar"><div class="progress-fill-green"></div></div>
                </div>
                <div class="mini-stat" style="padding:14px;">
                    <p style="color:#93c5fd; font-size:11px; margin:0 0 4px;">Pengeluaran</p>
                    <p style="color:white; font-size:16px; font-weight:700; margin:0;">Rp 4.25jt</p>
                    <div class="progress-bar"><div class="progress-fill-red"></div></div>
                </div>
            </div>

            {{-- Recent Transactions --}}
            <div class="glass-card" style="padding:16px;">
                <p style="color:#93c5fd; font-size:11px; font-weight:600; letter-spacing:0.06em; text-transform:uppercase; margin:0 0 12px;">Transaksi Terakhir</p>
                @foreach([['Kopi Starbucks','Makanan & Minuman','- Rp 50.000','#f87171'],['Gaji Juni','Gaji','+ Rp 8.000.000','#4ade80'],['Gojek','Transportasi','- Rp 25.000','#f87171']] as $t)
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:30px; height:30px; border-radius:50%; background:rgba(255,255,255,0.12); display:flex; align-items:center; justify-content:center;">
                            <div style="width:8px; height:8px; border-radius:50%; background:#60a5fa;"></div>
                        </div>
                        <div>
                            <p style="color:white; font-size:12px; font-weight:600; margin:0;">{{ $t[0] }}</p>
                            <p style="color:#93c5fd; font-size:11px; margin:0;">{{ $t[1] }}</p>
                        </div>
                    </div>
                    <span style="font-size:12px; font-weight:700; color:{{ $t[3] }};">{{ $t[2] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Feature Pills --}}
    <div style="position:relative; z-index:10; display:flex; flex-wrap:wrap; gap:8px;">
        @foreach(['🤖 AI Kategorisasi','📊 Analitik Real-time','📄 Export PDF & Excel','🔒 Data Aman'] as $f)
            <span class="pill">{{ $f }}</span>
        @endforeach
    </div>
</div>

{{-- ═══ RIGHT PANEL: Form ═══ --}}
<div style="width:50%; background:#f8fafc; display:flex; align-items:center; justify-content:center; padding:40px 24px;">
    <div style="width:100%; max-width:400px;">

        {{-- Header --}}
        <div style="margin-bottom:32px;">
            <h2 style="font-size:26px; font-weight:800; color:#0f172a; margin:0 0 6px;">Selamat datang kembali! 👋</h2>
            <p style="font-size:14px; color:#64748b; margin:0;">Masuk untuk melanjutkan ke dashboard Anda</p>
        </div>

        {{-- Status / Error --}}
        @if (session('status'))
            <div style="background:#f0fdf4; border:1px solid #86efac; color:#166534; padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:20px;">
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div style="background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; padding:12px 16px; border-radius:10px; font-size:13px; margin-bottom:20px; display:flex; align-items:start; gap:10px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div style="margin-bottom:18px;">
                <label class="label" for="email">Alamat Email</label>
                <div style="position:relative;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" placeholder="nama@email.com" required autofocus>
                </div>
            </div>

            {{-- Password --}}
            <div style="margin-bottom:18px;">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                    <label class="label" for="password" style="margin:0;">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:12px; color:#2563eb; font-weight:500; text-decoration:none;">Lupa password?</a>
                    @endif
                </div>
                <div style="position:relative;">
                    <svg class="input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <input id="password" type="password" name="password" class="form-input" placeholder="Masukkan password" required>
                </div>
            </div>

            {{-- Remember --}}
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:24px;">
                <input id="remember_me" type="checkbox" name="remember" style="width:16px; height:16px; accent-color:#2563eb; border-radius:4px;">
                <label for="remember_me" style="font-size:13px; color:#475569; cursor:pointer;">Ingat saya selama 30 hari</label>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-primary">
                Masuk ke Dashboard
                <svg width="16" height="16" fill="none" stroke="white" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </button>
        </form>

        {{-- Divider --}}
        <div style="display:flex; align-items:center; gap:16px; margin:24px 0;">
            <div style="flex:1; height:1px; background:#e2e8f0;"></div>
            <span style="font-size:12px; color:#94a3b8; font-weight:500;">atau</span>
            <div style="flex:1; height:1px; background:#e2e8f0;"></div>
        </div>

        {{-- Register --}}
        <p style="text-align:center; font-size:14px; color:#64748b; margin:0 0 12px;">
            Belum punya akun?
            <a href="{{ route('register') }}" style="color:#2563eb; font-weight:600; text-decoration:none;">Daftar gratis →</a>
        </p>

        {{-- Admin Link --}}
        <p style="text-align:center; font-size:12px; color:#94a3b8; margin:0;">
            Seorang Admin?
            <a href="{{ route('admin.login') }}" style="color:#6366f1; font-weight:500; text-decoration:none;">Masuk ke Admin Panel</a>
        </p>
    </div>
</div>

{{-- Hide left panel on mobile --}}
<style>
@media (max-width: 1024px) {
    .left-panel { display: none !important; }
    body > div:last-child { width: 100% !important; }
}
</style>

</body>
</html>
