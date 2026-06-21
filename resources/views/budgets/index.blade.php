<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">💰 Budget Bulanan</h2>
            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $now->format('F Y') }}</span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-xl text-emerald-800 dark:text-emerald-300 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Add Budget Form --}}
            @if($availableCategories->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">➕ Tambah Budget Kategori</h3>
                <form method="POST" action="{{ route('budgets.store') }}" class="flex flex-wrap gap-3 items-end">
                    @csrf
                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori</label>
                        <select name="category_id" class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($availableCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 min-w-[160px]">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Limit Budget (Rp)</label>
                        <input type="number" name="amount" min="1000" step="1000" placeholder="1500000"
                            class="block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                    </div>
                    <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition text-sm">
                        Simpan
                    </button>
                </form>
            </div>
            @endif

            {{-- Budget Cards --}}
            @if($budgets->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-12 text-center">
                    <p class="text-4xl mb-3">📊</p>
                    <p class="text-gray-500 dark:text-gray-400">Belum ada budget bulan ini. Tambahkan budget per kategori di atas!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    @foreach($budgets as $budget)
                    @php
                        $pct = $budget->percentage;
                        $color = $pct >= 90 ? 'rose' : ($pct >= 70 ? 'amber' : 'emerald');
                    @endphp
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-l-4 {{ $pct >= 90 ? 'border-rose-500' : ($pct >= 70 ? 'border-amber-500' : 'border-emerald-500') }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="font-semibold text-gray-800 dark:text-white">{{ $budget->category->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    Terpakai: <span class="font-medium text-gray-700 dark:text-gray-300">Rp {{ number_format($budget->spent, 0, ',', '.') }}</span>
                                    / Rp {{ number_format($budget->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($pct >= 90)
                                    <span class="text-xs font-semibold bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300 px-2 py-0.5 rounded-full">⚠ Hampir Habis!</span>
                                @elseif($pct >= 70)
                                    <span class="text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300 px-2 py-0.5 rounded-full">⚡ Perhatian</span>
                                @endif
                                <form method="POST" action="{{ route('budgets.destroy', $budget) }}" onsubmit="return confirm('Hapus budget ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-400 hover:text-rose-500 transition text-xs">✕</button>
                                </form>
                            </div>
                        </div>
                        {{-- Progress Bar --}}
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                            <div class="h-3 rounded-full transition-all duration-500 {{ $color === 'rose' ? 'bg-rose-500' : ($color === 'amber' ? 'bg-amber-400' : 'bg-emerald-500') }}"
                                style="width: {{ $pct }}%"></div>
                        </div>
                        <div class="flex justify-between mt-1.5">
                            <span class="text-xs font-semibold {{ $color === 'rose' ? 'text-rose-600 dark:text-rose-400' : ($color === 'amber' ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') }}">{{ $pct }}%</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Sisa: Rp {{ number_format($budget->remaining, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
