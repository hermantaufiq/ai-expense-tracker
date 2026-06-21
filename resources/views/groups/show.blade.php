<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Grup: {{ $group->name }}
            </h2>
            <a href="{{ route('groups.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline">&larr; Kembali</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Left: Group Summary & Members --}}
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
                        <h3 class="font-bold text-gray-900 dark:text-white mb-4">Ringkasan Grup</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-rose-50 dark:bg-rose-900/20 rounded-lg">
                                <span class="text-sm text-rose-700 dark:text-rose-400">Total Pengeluaran</span>
                                <span class="font-bold text-rose-700 dark:text-rose-400">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg">
                                <span class="text-sm text-emerald-700 dark:text-emerald-400">Total Pemasukan</span>
                                <span class="font-bold text-emerald-700 dark:text-emerald-400">Rp {{ number_format($totalIncome, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-bold text-gray-900 dark:text-white">Anggota ({{ $group->members->count() }})</h3>
                            @if($group->owner_id === auth()->id())
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'invite-member')" class="text-xs text-primary-600 dark:text-primary-400 font-semibold hover:underline bg-primary-50 dark:bg-primary-900/30 px-2.5 py-1 rounded-md">+ Undang</button>
                            @endif
                        </div>
                        <ul class="space-y-3">
                            @foreach($group->members as $member)
                                <li class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-xs font-bold">
                                        {{ substr($member->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $member->id === $group->owner_id ? 'Pemilik' : 'Anggota' }}</p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Right: Transactions List & Add Form --}}
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 dark:text-white">Transaksi Bersama</h3>
                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-group-trx')" class="px-4 py-2 bg-primary-600 text-white text-xs font-bold rounded-xl hover:bg-primary-700 transition shadow-sm">
                                + Catat Transaksi
                            </button>
                        </div>
                        
                        @if($transactions->isEmpty())
                            <div class="p-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                                Belum ada transaksi bersama di grup ini.
                            </div>
                        @else
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($transactions as $t)
                                    <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-full {{ $t->category->type === 'expense' ? 'bg-rose-100 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400' : 'bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400' }} flex items-center justify-center text-lg">
                                                    {{ $t->category->icon ?? '💰' }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $t->description }}</p>
                                                    <div class="flex gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                        <span>{{ $t->transaction_date->format('d M Y') }}</span>
                                                        <span>&bull;</span>
                                                        <span>Oleh: <strong class="text-gray-700 dark:text-gray-300">{{ $t->user->name }}</strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="font-bold {{ $t->category->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                                {{ $t->category->type === 'expense' ? '-' : '+' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Invite --}}
    @if($group->owner_id === auth()->id())
    <x-modal name="invite-member" focusable>
        <form method="POST" action="{{ route('groups.invite', $group) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Undang Anggota Grup</h2>
            <p class="text-sm text-gray-500 mb-4">Masukkan email pengguna yang sudah terdaftar di aplikasi ini.</p>
            
            <div class="mb-5">
                <x-input-label for="email" value="Alamat Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" placeholder="email@contoh.com" required />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button>Undang</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif

    {{-- Modal Add Transaction --}}
    <x-modal name="add-group-trx" focusable>
        <form method="POST" action="{{ route('groups.transactions.store', $group) }}" class="p-6">
            @csrf
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Catat Transaksi Grup</h2>
            
            <div class="space-y-4">
                <div>
                    <x-input-label value="Kategori" />
                    <select name="category_id" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" required>
                        <option value="">-- Pilih --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }} ({{ ucfirst($cat->type) }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <x-input-label value="Tanggal" />
                    <x-text-input name="transaction_date" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full" required />
                </div>
                
                <div>
                    <x-input-label value="Nominal (Rp)" />
                    <x-text-input name="amount" type="number" min="1" class="mt-1 block w-full" required />
                </div>
                
                <div>
                    <x-input-label value="Keterangan" />
                    <x-text-input name="description" type="text" class="mt-1 block w-full" placeholder="Contoh: Belanja Bulanan" required />
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button>Simpan Transaksi</x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
