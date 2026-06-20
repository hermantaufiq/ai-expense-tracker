<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Panel – AI Expense Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-950 text-gray-100 antialiased">

<div class="flex h-screen overflow-hidden">

    {{-- SIDEBAR --}}
    <aside id="admin-sidebar" class="w-64 bg-gray-900 border-r border-gray-800 flex flex-col flex-shrink-0 transition-all duration-300">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-800">
            <div class="w-9 h-9 bg-gradient-to-br from-violet-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold text-white">Admin Panel</p>
                <p class="text-xs text-gray-400">AI Expense Tracker</p>
            </div>
        </div>

        {{-- Nav Links --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider px-2 mb-3">Menu Utama</p>

            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
               {{ request()->routeIs('admin.dashboard') ? 'bg-violet-600/20 text-violet-400 border border-violet-500/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
               {{ request()->routeIs('admin.users.*') ? 'bg-violet-600/20 text-violet-400 border border-violet-500/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Kelola Pengguna
            </a>

            <a href="{{ route('admin.ai-rules.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-150
               {{ request()->routeIs('admin.ai-rules.*') ? 'bg-violet-600/20 text-violet-400 border border-violet-500/30' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
                Global AI Rules
            </a>
        </nav>

        {{-- Bottom: Profile & Back --}}
        <div class="px-4 py-4 border-t border-gray-800 space-y-2">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:bg-gray-800 hover:text-white transition-all duration-150">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"/>
                </svg>
                Kembali ke Aplikasi
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-all duration-150">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- TOPBAR --}}
        <header class="h-16 bg-gray-900 border-b border-gray-800 flex items-center justify-between px-6 flex-shrink-0">
            <div>
                <h1 class="text-base font-semibold text-white">{{ $title ?? 'Admin Panel' }}</h1>
                <p class="text-xs text-gray-400">AI Expense Tracker Control Center</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="text-right hidden sm:block">
                        <p class="text-xs font-semibold text-white">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-violet-400">Super Admin</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        <main class="flex-1 overflow-y-auto bg-gray-950 p-6">
            {{ $slot }}
        </main>
    </div>

</div>

</body>
</html>
