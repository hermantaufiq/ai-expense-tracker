<x-admin-layout>
    <x-slot name="title">Dashboard Admin</x-slot>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        {{-- Total Users --}}
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Pengguna</p>
                <p class="text-3xl font-bold text-white mt-0.5">{{ $totalUsers }}</p>
            </div>
        </div>

        {{-- Premium Users --}}
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-violet-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Pengguna Premium</p>
                <p class="text-3xl font-bold text-violet-400 mt-0.5">{{ $premiumUsers }}</p>
            </div>
        </div>

        {{-- Total Transactions --}}
        <div class="bg-gray-900 rounded-2xl border border-gray-800 p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-500/15 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-400 font-medium uppercase tracking-wider">Total Transaksi</p>
                <p class="text-3xl font-bold text-emerald-400 mt-0.5">{{ $totalTransactions }}</p>
            </div>
        </div>
    </div>

    {{-- Conversion Rate --}}
    <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6 mb-8">
        <h3 class="text-sm font-semibold text-gray-300 mb-3">Rasio Konversi Free → Premium</h3>
        @php
            $rate = $totalUsers > 0 ? round(($premiumUsers / $totalUsers) * 100, 1) : 0;
        @endphp
        <div class="flex items-center gap-4">
            <div class="flex-1 bg-gray-800 rounded-full h-3">
                <div class="bg-gradient-to-r from-violet-600 to-indigo-500 h-3 rounded-full transition-all duration-700"
                     style="width: {{ $rate }}%"></div>
            </div>
            <span class="text-sm font-bold text-violet-400 w-12 text-right">{{ $rate }}%</span>
        </div>
        <p class="text-xs text-gray-500 mt-2">{{ $premiumUsers }} dari {{ $totalUsers }} pengguna telah upgrade ke Premium</p>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <a href="{{ route('admin.users.index') }}" class="bg-gray-900 border border-gray-800 hover:border-violet-500/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-10 h-10 bg-violet-500/10 group-hover:bg-violet-500/20 rounded-xl flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Kelola Pengguna</p>
                <p class="text-xs text-gray-400 mt-0.5">Ban, upgrade, dan atur akun pengguna</p>
            </div>
            <svg class="w-5 h-5 text-gray-600 group-hover:text-violet-400 ml-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>

        <a href="{{ route('admin.ai-rules.index') }}" class="bg-gray-900 border border-gray-800 hover:border-indigo-500/50 rounded-2xl p-5 flex items-center gap-4 transition-all group">
            <div class="w-10 h-10 bg-indigo-500/10 group-hover:bg-indigo-500/20 rounded-xl flex items-center justify-center transition-colors">
                <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                </svg>
            </div>
            <div>
                <p class="text-sm font-semibold text-white">Global AI Rules</p>
                <p class="text-xs text-gray-400 mt-0.5">Tambah kata kunci AI untuk semua pengguna</p>
            </div>
            <svg class="w-5 h-5 text-gray-600 group-hover:text-indigo-400 ml-auto transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </a>
    </div>

</x-admin-layout>
