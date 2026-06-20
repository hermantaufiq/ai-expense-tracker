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
        body { font-family: 'Inter', sans-serif; }
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 40%, #2563eb 70%, #3b82f6 100%);
        }
        .card-glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .animate-float {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .animate-pulse-slow {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        .dot-pattern {
            background-image: radial-gradient(circle, rgba(255,255,255,0.15) 1px, transparent 1px);
            background-size: 24px 24px;
        }
    </style>
</head>
<body class="min-h-screen flex">

    {{-- LEFT PANEL: Branding --}}
    <div class="hidden lg:flex lg:w-1/2 gradient-bg dot-pattern relative overflow-hidden flex-col justify-between p-12">

        {{-- Decorative circles --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-blue-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -right-20 w-80 h-80 bg-indigo-500/30 rounded-full blur-3xl"></div>

        {{-- Top: Logo --}}
        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center border border-white/30">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-white font-bold text-lg">AI Expense Tracker</span>
            </div>
        </div>

        {{-- Middle: Hero Text + Preview Cards --}}
        <div class="relative z-10 space-y-8">
            <div>
                <h1 class="text-4xl font-bold text-white leading-snug">
                    Kelola Keuangan<br>
                    Anda Lebih<br>
                    <span class="text-blue-200">Cerdas dengan AI</span>
                </h1>
                <p class="text-blue-100 text-sm mt-4 leading-relaxed max-w-xs">
                    Kategorisasi otomatis, analitik visual, dan laporan keuangan lengkap — semua dalam satu platform.
                </p>
            </div>

            {{-- Mock Dashboard Preview Cards --}}
            <div class="space-y-3 animate-float">
                {{-- Balance Card --}}
                <div class="card-glass rounded-2xl p-4 max-w-xs">
                    <p class="text-blue-200 text-xs font-medium mb-1">Saldo Bulan Ini</p>
                    <p class="text-white text-2xl font-bold">Rp 4.250.000</p>
                    <div class="flex items-center gap-1 mt-1">
                        <svg class="w-3 h-3 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-300 text-xs font-medium">+12.5% dari bulan lalu</span>
                    </div>
                </div>

                {{-- Mini Stats Row --}}
                <div class="flex gap-3 max-w-xs">
                    <div class="stat-card rounded-xl p-3 flex-1">
                        <p class="text-blue-200 text-xs">Pemasukan</p>
                        <p class="text-white font-bold text-sm mt-0.5">Rp 8.5jt</p>
                        <div class="w-full bg-white/10 rounded-full h-1 mt-2">
                            <div class="bg-green-400 h-1 rounded-full w-3/4"></div>
                        </div>
                    </div>
                    <div class="stat-card rounded-xl p-3 flex-1">
                        <p class="text-blue-200 text-xs">Pengeluaran</p>
                        <p class="text-white font-bold text-sm mt-0.5">Rp 4.25jt</p>
                        <div class="w-full bg-white/10 rounded-full h-1 mt-2">
                            <div class="bg-rose-400 h-1 rounded-full w-1/2"></div>
                        </div>
                    </div>
                </div>

                {{-- Recent Transactions --}}
                <div class="card-glass rounded-2xl p-4 max-w-xs space-y-2">
                    <p class="text-blue-200 text-xs font-medium mb-2">Transaksi Terakhir</p>
                    @foreach([['Kopi Starbucks','Makanan','- Rp 50rb','text-rose-300'],['Gaji Juni','Gaji','+ Rp 8jt','text-green-300'],['Gojek ke Kantor','Transportasi','- Rp 25rb','text-rose-300']] as $t)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-white/10 flex items-center justify-center">
                                <div class="w-2 h-2 rounded-full bg-blue-300"></div>
                            </div>
                            <div>
                                <p class="text-white text-xs font-medium leading-none">{{ $t[0] }}</p>
                                <p class="text-blue-300 text-xs">{{ $t[1] }}</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold {{ $t[3] }}">{{ $t[2] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Bottom: Feature Pills --}}
        <div class="relative z-10 flex flex-wrap gap-2">
            @foreach(['🤖 AI Kategorisasi','📊 Analitik Real-time','📄 Export PDF & Excel','🔒 Data Aman'] as $f)
            <span class="bg-white/10 border border-white/20 text-white text-xs font-medium px-3 py-1.5 rounded-full">{{ $f }}</span>
            @endforeach
        </div>
    </div>

    {{-- RIGHT PANEL: Login Form --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center bg-gray-50 p-6">
        <div class="w-full max-w-md">

            {{-- Mobile Logo --}}
            <div class="flex items-center gap-2 mb-8 lg:hidden">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-gray-800">AI Expense Tracker</span>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-1">Selamat datang kembali! 👋</h2>
            <p class="text-sm text-gray-500 mb-8">Masuk untuk melanjutkan ke dashboard Anda</p>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                            placeholder="nama@email.com"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition-all">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex justify-between items-center mb-1.5">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-700 font-medium">Lupa password?</a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <input id="password" type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 rounded-xl text-sm text-gray-900 placeholder-gray-400 outline-none transition-all">
                    </div>
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input id="remember_me" type="checkbox" name="remember"
                        class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                    <label for="remember_me" class="text-sm text-gray-600">Ingat saya selama 30 hari</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/25 text-sm mt-2 flex items-center justify-center gap-2">
                    Masuk ke Dashboard
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-4 my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="text-xs text-gray-400 font-medium">atau</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            {{-- Register Link --}}
            <p class="text-center text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-700 font-semibold">Daftar gratis →</a>
            </p>

            {{-- Admin Link --}}
            <p class="text-center text-xs text-gray-400 mt-4">
                Seorang Admin?
                <a href="{{ route('admin.login') }}" class="text-gray-500 hover:text-gray-700 underline">Masuk ke Admin Panel</a>
            </p>
        </div>
    </div>

</body>
</html>
