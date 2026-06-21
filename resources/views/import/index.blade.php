<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">💳 Import Mutasi Bank (AI)</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-xl text-emerald-800 dark:text-emerald-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-300 dark:border-rose-700 rounded-xl text-rose-800 dark:text-rose-300 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-2xl p-8">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Upload Mutasi Bank / Rekening Koran</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        Upload file CSV, TXT, atau Excel dari e-banking. AI akan secara otomatis membaca dan mengekstrak transaksi.
                    </p>
                </div>

                <form method="POST" action="{{ route('import.process') }}" enctype="multipart/form-data" class="space-y-6" id="upload-form">
                    @csrf
                    
                    {{-- Drag & Drop Area --}}
                    <div class="relative group">
                        <input type="file" name="statement_file" id="statement_file" accept=".csv,.txt,.xls,.xlsx" required
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            onchange="updateFileName(this)">
                        
                        <div id="drop-zone" class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-10 text-center transition-colors group-hover:bg-indigo-50 dark:group-hover:bg-indigo-900/10 group-hover:border-indigo-500">
                            <span class="text-4xl mb-3 block">📄</span>
                            <span id="file-name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Klik untuk pilih file atau Drag & Drop ke sini
                            </span>
                            <p class="text-xs text-gray-400 mt-2">Mendukung .csv, .txt, .xls, .xlsx (Max 2MB)</p>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" id="submit-btn" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl shadow transition">
                            <span id="spinner" class="hidden">
                                <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            </span>
                            <span id="btn-text">🚀 Proses dengan AI</span>
                        </button>
                    </div>
                </form>

            </div>

            {{-- Tips --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5 flex gap-4">
                <span class="text-2xl">💡</span>
                <div>
                    <h4 class="font-bold text-blue-800 dark:text-blue-300 text-sm">Bagaimana cara kerjanya?</h4>
                    <ul class="list-disc list-inside text-sm text-blue-700 dark:text-blue-400 mt-2 space-y-1">
                        <li>Sistem menggunakan <strong>Gemini AI</strong> untuk membaca teks mentah dari file.</li>
                        <li>AI akan mengenali tanggal, nominal, dan deskripsi secara cerdas.</li>
                        <li>Transaksi duplikat (tanggal, nominal, deskripsi sama) akan diabaikan.</li>
                        <li>Tidak perlu menyesuaikan kolom atau format manual!</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>

    <script>
        function updateFileName(input) {
            const fileNameSpan = document.getElementById('file-name');
            const dropZone = document.getElementById('drop-zone');
            
            if (input.files && input.files[0]) {
                fileNameSpan.textContent = "Terpilih: " + input.files[0].name;
                fileNameSpan.classList.add('text-indigo-600', 'dark:text-indigo-400', 'font-bold');
                dropZone.classList.add('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            } else {
                fileNameSpan.textContent = "Klik untuk pilih file atau Drag & Drop ke sini";
                fileNameSpan.classList.remove('text-indigo-600', 'dark:text-indigo-400', 'font-bold');
                dropZone.classList.remove('border-indigo-500', 'bg-indigo-50', 'dark:bg-indigo-900/20');
            }
        }

        document.getElementById('upload-form').addEventListener('submit', function() {
            document.getElementById('spinner').classList.remove('hidden');
            document.getElementById('btn-text').textContent = 'AI sedang memproses...';
            document.getElementById('submit-btn').disabled = true;
            document.getElementById('submit-btn').classList.add('opacity-75', 'cursor-not-allowed');
        });
    </script>
</x-app-layout>
