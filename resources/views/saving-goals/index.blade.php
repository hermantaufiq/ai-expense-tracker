<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">🎯 Target Menabung</h2>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-goal')"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Buat Target Baru
            </button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-xl text-emerald-800 dark:text-emerald-300 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Empty State --}}
            @if($goals->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-16 text-center">
                    <p class="text-5xl mb-4">🐷</p>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Belum ada target menabung</h3>
                    <p class="text-gray-400 text-sm mb-6">Mulai buat target menabungmu sekarang — laptop baru, liburan, atau apapun impianmu!</p>
                    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-goal')"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg shadow transition text-sm">
                        🎯 Buat Target Pertama
                    </button>
                </div>
            @else
                {{-- Active Goals --}}
                @php $activeGoals = $goals->where('is_completed', false); @endphp
                @if($activeGoals->isNotEmpty())
                <div>
                    <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">🎯 Target Aktif ({{ $activeGoals->count() }})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($activeGoals as $goal)
                        @php
                            $pct = $goal->percentage;
                            $daysLeft = $goal->days_left;
                            $monthlyNeeded = $goal->monthly_saving_needed;
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 hover:shadow-lg transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl">{{ $goal->icon }}</span>
                                    <div>
                                        <h4 class="font-bold text-gray-800 dark:text-white">{{ $goal->name }}</h4>
                                        @if($goal->deadline)
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                Deadline: {{ $goal->deadline->format('d M Y') }}
                                                @if($daysLeft !== null)
                                                    <span class="{{ $daysLeft < 30 ? 'text-rose-500 font-semibold' : '' }}">
                                                        ({{ $daysLeft > 0 ? $daysLeft . ' hari lagi' : 'Sudah lewat!' }})
                                                    </span>
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('saving-goals.destroy', $goal) }}" onsubmit="return confirm('Hapus target ini?')">
                                    @csrf @method('DELETE')
                                    <button class="text-gray-300 hover:text-rose-500 transition text-lg">✕</button>
                                </form>
                            </div>

                            {{-- Progress --}}
                            <div class="mb-3">
                                <div class="flex justify-between text-sm mb-1.5">
                                    <span class="text-gray-600 dark:text-gray-400">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</span>
                                    <span class="font-semibold text-gray-800 dark:text-white">{{ $pct }}%</span>
                                    <span class="text-gray-400">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                    <div class="h-3 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 transition-all duration-700"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                            </div>

                            {{-- Stats --}}
                            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                                <span>Sisa: <strong class="text-gray-700 dark:text-gray-300">Rp {{ number_format($goal->remaining, 0, ',', '.') }}</strong></span>
                                @if($monthlyNeeded)
                                    <span>Nabung/bln: <strong class="text-indigo-600 dark:text-indigo-400">Rp {{ number_format($monthlyNeeded, 0, ',', '.') }}</strong></span>
                                @endif
                            </div>

                            {{-- Add Funds Form --}}
                            <form method="POST" action="{{ route('saving-goals.add-funds', $goal) }}" class="flex gap-2">
                                @csrf
                                <input type="number" name="amount" min="1000" step="1000" placeholder="Tambah dana (Rp)"
                                    class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 min-w-0"
                                    required>
                                <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm transition whitespace-nowrap">
                                    + Tabung
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Completed Goals --}}
                @php $completedGoals = $goals->where('is_completed', true); @endphp
                @if($completedGoals->isNotEmpty())
                <div>
                    <h3 class="text-sm font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider mb-3">✅ Sudah Tercapai ({{ $completedGoals->count() }})</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($completedGoals as $goal)
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl p-6 flex items-center gap-4">
                            <span class="text-4xl">{{ $goal->icon }}</span>
                            <div class="flex-1">
                                <h4 class="font-bold text-emerald-800 dark:text-emerald-200">{{ $goal->name }} 🎉</h4>
                                <p class="text-sm text-emerald-600 dark:text-emerald-400">Rp {{ number_format($goal->target_amount, 0, ',', '.') }} — Tercapai!</p>
                                <div class="w-full bg-emerald-200 dark:bg-emerald-800 rounded-full h-2 mt-2">
                                    <div class="h-2 rounded-full bg-emerald-500 w-full"></div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('saving-goals.destroy', $goal) }}" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button class="text-gray-300 hover:text-rose-400 transition">✕</button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            @endif

        </div>
    </div>

    {{-- Modal: Add Goal --}}
    <x-modal name="add-goal" focusable>
        <form method="POST" action="{{ route('saving-goals.store') }}" class="p-6">
            @csrf

            <div class="flex items-center gap-3 mb-5">
                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/40 rounded-lg text-2xl">🎯</div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Buat Target Menabung</h2>
            </div>

            {{-- Icon Picker --}}
            <div class="mb-4">
                <x-input-label value="Pilih Icon" />
                <div class="mt-2 flex flex-wrap gap-2" id="icon-picker">
                    @foreach(['🎯','🏠','🚗','✈️','💻','📱','🎓','👔','🐕','💍','🏖️','🎮','🎸','💪','🏋️'] as $em)
                        <button type="button" onclick="selectIcon('{{ $em }}')"
                            class="icon-btn w-10 h-10 text-xl flex items-center justify-center rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-indigo-500 transition"
                            data-emoji="{{ $em }}">{{ $em }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="icon" id="selected-icon" value="🎯">
            </div>

            <div class="mb-4">
                <x-input-label for="goal-name" value="Nama Target" />
                <x-text-input id="goal-name" name="name" type="text" class="mt-1 block w-full"
                    placeholder="Contoh: Beli Laptop Gaming, Liburan Bali" required />
            </div>

            <div class="mb-4">
                <x-input-label for="goal-target" value="Target Dana (Rp)" />
                <x-text-input id="goal-target" name="target_amount" type="number" min="1000" step="1000"
                    class="mt-1 block w-full" placeholder="10000000" required />
            </div>

            <div class="mb-5">
                <x-input-label for="goal-deadline" value="Deadline (Opsional)" />
                <x-text-input id="goal-deadline" name="deadline" type="date" class="mt-1 block w-full"
                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" />
                <p class="text-xs text-gray-400 mt-1">AI akan menghitung berapa yang perlu ditabung per bulan.</p>
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button>🎯 Buat Target</x-primary-button>
            </div>
        </form>
    </x-modal>

    <script>
    function selectIcon(emoji) {
        document.getElementById('selected-icon').value = emoji;
        document.querySelectorAll('.icon-btn').forEach(btn => {
            btn.classList.toggle('border-indigo-500', btn.dataset.emoji === emoji);
            btn.classList.toggle('bg-indigo-50', btn.dataset.emoji === emoji);
            btn.classList.toggle('dark:bg-indigo-900/30', btn.dataset.emoji === emoji);
            btn.classList.toggle('border-gray-200', btn.dataset.emoji !== emoji);
            btn.classList.toggle('dark:border-gray-700', btn.dataset.emoji !== emoji);
        });
    }
    // Select default
    document.addEventListener('DOMContentLoaded', () => selectIcon('🎯'));
    </script>
</x-app-layout>
