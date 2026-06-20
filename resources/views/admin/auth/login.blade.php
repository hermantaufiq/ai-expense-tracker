<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login – AI Expense Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">

        {{-- Logo / Brand --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-2xl shadow-violet-500/30">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-white">Admin Panel</h1>
            <p class="text-sm text-gray-400 mt-1">AI Expense Tracker – Control Center</p>
        </div>

        {{-- Card --}}
        <div class="bg-gray-900 rounded-2xl border border-gray-800 shadow-2xl p-8">
            <h2 class="text-lg font-semibold text-white mb-1">Selamat Datang, Admin</h2>
            <p class="text-sm text-gray-400 mb-6">Masuk khusus untuk Super Admin</p>

            {{-- Error Message --}}
            @if($errors->any())
                <div class="mb-5 flex items-start gap-3 bg-red-900/40 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
                    <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-xs font-medium text-gray-400 mb-1.5">Email Admin</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        placeholder="admin@example.com"
                        class="w-full bg-gray-800 border border-gray-700 focus:border-violet-500 focus:ring-0 text-white placeholder-gray-500 rounded-xl px-4 py-3 text-sm transition-colors outline-none">
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-xs font-medium text-gray-400 mb-1.5">Password</label>
                    <input id="password" type="password" name="password" required
                        placeholder="••••••••"
                        class="w-full bg-gray-800 border border-gray-700 focus:border-violet-500 focus:ring-0 text-white placeholder-gray-500 rounded-xl px-4 py-3 text-sm transition-colors outline-none">
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center gap-2">
                    <input id="remember" type="checkbox" name="remember"
                        class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-violet-600 focus:ring-violet-500">
                    <label for="remember" class="text-xs text-gray-400">Ingat saya</label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 text-white font-semibold py-3 px-4 rounded-xl transition-all duration-200 shadow-lg shadow-violet-500/20 text-sm">
                    Masuk ke Admin Panel
                </button>
            </form>
        </div>

        {{-- Link kembali --}}
        <p class="text-center text-xs text-gray-500 mt-6">
            Bukan Admin?
            <a href="{{ route('login') }}" class="text-violet-400 hover:text-violet-300 transition-colors">
                Login sebagai Pengguna →
            </a>
        </p>

    </div>

</body>
</html>
