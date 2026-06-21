<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">🤖 Analisis AI Keuangan</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Roast Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-rose-100 dark:bg-rose-900/40 rounded-lg">
                        <span class="text-2xl">🔥</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white">Roast Keuangan Saya</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">AI akan menganalisis dan "mem-roast" kebiasaan keuanganmu secara jujur dan lucu.</p>
                    </div>
                </div>

                <div id="roast-result" class="mt-4 hidden">
                    <div class="p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl text-gray-800 dark:text-gray-200 text-sm leading-relaxed whitespace-pre-wrap" id="roast-text"></div>
                </div>
                <div id="roast-error" class="mt-4 hidden p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-300 text-sm rounded-lg"></div>

                <button id="roast-btn" onclick="fetchRoast()"
                    class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-rose-500 to-orange-500 hover:from-rose-600 hover:to-orange-600 text-white font-semibold rounded-lg shadow transition">
                    <span id="roast-spinner" class="hidden">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </span>
                    <span id="roast-label">🔥 Roast Keuangan Saya!</span>
                </button>
            </div>

            {{-- Saving Tips Section --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg">
                        <span class="text-2xl">💡</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-800 dark:text-white">Saran Hemat AI</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">AI akan memberikan 3 saran hemat spesifik berdasarkan pengeluaran bulan ini.</p>
                    </div>
                </div>

                <div id="tips-result" class="mt-4 hidden">
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-gray-800 dark:text-gray-200 text-sm leading-relaxed whitespace-pre-wrap" id="tips-text"></div>
                </div>
                <div id="tips-error" class="mt-4 hidden p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-700 dark:text-rose-300 text-sm rounded-lg"></div>

                <button id="tips-btn" onclick="fetchTips()"
                    class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold rounded-lg shadow transition">
                    <span id="tips-spinner" class="hidden">
                        <svg class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </span>
                    <span id="tips-label">💡 Dapatkan Saran Hemat</span>
                </button>
            </div>

        </div>
    </div>

    <script>
    function setLoading(type, loading) {
        document.getElementById(type + '-spinner').classList.toggle('hidden', !loading);
        document.getElementById(type + '-btn').disabled = loading;
        document.getElementById(type + '-label').textContent = loading
            ? 'AI sedang berpikir...'
            : (type === 'roast' ? '🔥 Roast Keuangan Saya!' : '💡 Dapatkan Saran Hemat');
    }

    async function fetchRoast() {
        setLoading('roast', true);
        document.getElementById('roast-result').classList.add('hidden');
        document.getElementById('roast-error').classList.add('hidden');
        try {
            const res = await fetch('{{ route("analysis.roast") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.result) {
                document.getElementById('roast-text').textContent = data.result;
                document.getElementById('roast-result').classList.remove('hidden');
            } else {
                document.getElementById('roast-error').textContent = data.error || 'Terjadi kesalahan.';
                document.getElementById('roast-error').classList.remove('hidden');
            }
        } catch(e) {
            document.getElementById('roast-error').textContent = 'Gagal terhubung ke server.';
            document.getElementById('roast-error').classList.remove('hidden');
        }
        setLoading('roast', false);
    }

    async function fetchTips() {
        setLoading('tips', true);
        document.getElementById('tips-result').classList.add('hidden');
        document.getElementById('tips-error').classList.add('hidden');
        try {
            const res = await fetch('{{ route("analysis.tips") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json();
            if (data.result) {
                document.getElementById('tips-text').textContent = data.result;
                document.getElementById('tips-result').classList.remove('hidden');
            } else {
                document.getElementById('tips-error').textContent = data.error || 'Terjadi kesalahan.';
                document.getElementById('tips-error').classList.remove('hidden');
            }
        } catch(e) {
            document.getElementById('tips-error').textContent = 'Gagal terhubung ke server.';
            document.getElementById('tips-error').classList.remove('hidden');
        }
        setLoading('tips', false);
    }
    </script>
</x-app-layout>
