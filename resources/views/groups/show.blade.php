<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('groups.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">&larr; Kembali ke Daftar Grup</a>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight mt-1">
                    {{ $group->name }}
                </h2>
            </div>
            <div class="flex gap-2">
                {{-- Leave Group (non-owner) --}}
                @if($group->owner_id !== auth()->id())
                    <form action="{{ route('groups.leave', $group) }}" method="POST" onsubmit="return confirm('Yakin ingin keluar dari grup ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-white dark:bg-gray-800 border border-rose-300 dark:border-rose-700 text-rose-600 dark:text-rose-400 text-sm font-semibold rounded-xl hover:bg-rose-50 transition">
                            🚪 Keluar Grup
                        </button>
                    </form>
                @else
                    {{-- Delete Group (owner only) --}}
                    <form action="{{ route('groups.destroy', $group) }}" method="POST" onsubmit="return confirm('Yakin ingin MENGHAPUS grup ini? Tindakan ini tidak bisa dibatalkan.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-sm font-semibold rounded-xl shadow-sm transition">
                            🗑️ Hapus Grup
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-2xl text-emerald-800 dark:text-emerald-300 text-sm flex items-center gap-2">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 dark:bg-rose-900/30 border border-rose-300 dark:border-rose-700 rounded-2xl text-rose-800 dark:text-rose-300 text-sm flex items-center gap-2">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Left Panel: Stats & Members --}}
                <div class="space-y-6">
                    {{-- Stats Cards --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-rose-50 dark:bg-rose-900/20 rounded-2xl p-4 border border-rose-100 dark:border-rose-800">
                            <p class="text-xs font-medium text-rose-600 dark:text-rose-400 mb-1">Total Pengeluaran</p>
                            <p class="text-lg font-bold text-rose-700 dark:text-rose-300">Rp {{ number_format($totalExpense, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-2xl p-4 border border-emerald-100 dark:border-emerald-800">
                            <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mb-1">Total Pemasukan</p>
                            <p class="text-lg font-bold text-emerald-700 dark:text-emerald-300">Rp {{ number_format($totalIncome, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Members List --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
                        <div class="flex justify-between items-center mb-5">
                            <h3 class="font-bold text-gray-900 dark:text-white">
                                👥 Anggota <span class="text-gray-400 font-normal">({{ $group->members->count() }})</span>
                            </h3>
                            @if($group->owner_id === auth()->id())
                                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'invite-member')"
                                    class="text-xs bg-blue-600 hover:bg-blue-700 text-white font-semibold px-3 py-1.5 rounded-lg transition">
                                    + Undang
                                </button>
                            @endif
                        </div>
                        <ul class="space-y-3">
                            @foreach($group->members as $member)
                                <li class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-sm font-bold shrink-0">
                                            {{ substr($member->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">{{ $member->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                @if($member->id === $group->owner_id)
                                                    <span class="text-blue-600 dark:text-blue-400 font-medium">👑 Pemilik</span>
                                                @else
                                                    Anggota
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    {{-- Remove member (owner only, not self) --}}
                                    @if($group->owner_id === auth()->id() && $member->id !== auth()->id())
                                        <form action="{{ route('groups.members.remove', [$group, $member]) }}" method="POST"
                                            onsubmit="return confirm('Keluarkan {{ $member->name }} dari grup?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 dark:hover:text-rose-400 font-medium transition" title="Keluarkan anggota">
                                                ✕
                                            </button>
                                        </form>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        {{-- Invite Link Box (owner only) --}}
                        @if($group->owner_id === auth()->id() && $inviteLink)
                        <div class="mt-5 pt-5 border-t border-gray-100 dark:border-gray-700">
                            <p class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">🔗 Link Undangan Grup</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Bagikan link ini ke siapa saja. Mereka perlu login/daftar dahulu, lalu akan otomatis bergabung.</p>
                            <div class="flex gap-2 mb-3">
                                <input
                                    id="invite-link-input"
                                    type="text"
                                    value="{{ $inviteLink }}"
                                    readonly
                                    class="flex-1 text-xs rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 bg-gray-50 focus:border-blue-400 focus:ring-blue-400 py-2 px-3"
                                />
                                <button
                                    onclick="navigator.clipboard.writeText(document.getElementById('invite-link-input').value); this.textContent='✅'; setTimeout(() => this.textContent='📋', 2000);"
                                    class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shrink-0"
                                    title="Salin link">
                                    📋
                                </button>
                            </div>
                            
                            {{-- Social Share Buttons --}}
                            <div class="flex flex-wrap gap-2 mb-4">
                                @php
                                    $shareText = rawurlencode('Bergabunglah ke grup "' . $group->name . '" di AI Expense Tracker melalui link ini: ' . $inviteLink);
                                    $waLink = "https://wa.me/?text=" . $shareText;
                                    $tgLink = "https://t.me/share/url?url=" . urlencode($inviteLink) . "&text=" . rawurlencode('Bergabunglah ke grup "' . $group->name . '"!');
                                    $fbLink = "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($inviteLink);
                                @endphp
                                <a href="{{ $waLink }}" target="_blank" class="px-3 py-1.5 bg-[#25D366] hover:bg-[#128C7E] text-white text-xs font-semibold rounded-lg flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                    WhatsApp
                                </a>
                                <a href="{{ $tgLink }}" target="_blank" class="px-3 py-1.5 bg-[#0088cc] hover:bg-[#0077b3] text-white text-xs font-semibold rounded-lg flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.14.18-.357.223-.548.223l.188-2.85 5.181-4.686c.223-.198-.054-.31-.346-.11l-6.4 4.024-2.76-.86c-.6-.188-.61-.6.126-.89l10.81-4.168c.5-.188.948.11.749.955z"/></svg>
                                    Telegram
                                </a>
                                <a href="{{ $fbLink }}" target="_blank" class="px-3 py-1.5 bg-[#1877F2] hover:bg-[#166fe5] text-white text-xs font-semibold rounded-lg flex items-center gap-1 transition">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.469h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.469h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    Facebook
                                </a>
                            </div>

                            <form action="{{ route('groups.regenerate-invite', $group) }}" method="POST"
                                onsubmit="return confirm('Link lama akan tidak berlaku. Lanjutkan?')"
                                class="mt-2">
                                @csrf
                                <button type="submit" class="text-xs text-gray-400 hover:text-rose-500 dark:hover:text-rose-400 transition underline">
                                    🔄 Buat link baru (nonaktifkan link lama)
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Right Panel: Transactions --}}
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="font-bold text-gray-900 dark:text-white">📋 Transaksi Bersama</h3>
                            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'add-group-trx')"
                                class="px-4 py-2 bg-blue-600 text-white text-xs font-bold rounded-xl hover:bg-blue-700 transition shadow-sm">
                                + Catat Transaksi
                            </button>
                        </div>

                        @if($transactions->isEmpty())
                            <div class="p-12 text-center">
                                <p class="text-4xl mb-3">🤝</p>
                                <h4 class="text-base font-semibold text-gray-700 dark:text-gray-200 mb-1">Belum ada transaksi</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Mulai catat pengeluaran atau pemasukan bersama!</p>
                            </div>
                        @else
                            <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach($transactions as $t)
                                    <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                        <div class="flex justify-between items-center">
                                            <div class="flex items-center gap-4">
                                                <div class="w-11 h-11 rounded-2xl {{ optional($t->category)->type === 'expense' ? 'bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400' : 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400' }} flex items-center justify-center text-xl shrink-0">
                                                    {{ optional($t->category)->icon ?? '💰' }}
                                                </div>
                                                <div>
                                                    <p class="font-semibold text-gray-900 dark:text-white text-sm">{{ $t->description }}</p>
                                                    <div class="flex flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                                        <span>{{ $t->transaction_date->format('d M Y') }}</span>
                                                        <span>&bull;</span>
                                                        <span>{{ optional($t->category)->name ?? 'Tanpa Kategori' }}</span>
                                                        <span>&bull;</span>
                                                        <span class="font-medium text-gray-700 dark:text-gray-300">oleh {{ $t->user->name ?? 'Unknown' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-right shrink-0 ml-4">
                                                <span class="font-bold text-base {{ optional($t->category)->type === 'expense' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                                    {{ optional($t->category)->type === 'expense' ? '-' : '+' }}Rp {{ number_format($t->amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @if($transactions->hasPages())
                                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-700">
                                    {{ $transactions->links() }}
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Invite Member --}}
    @if($group->owner_id === auth()->id())
    <x-modal name="invite-member" focusable>
        <form method="POST" action="{{ route('groups.invite', $group) }}" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-5">
                <div class="p-2.5 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Undang Anggota</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Masukkan email pengguna yang terdaftar di aplikasi ini.</p>
                </div>
            </div>
            <div class="mb-5">
                <x-input-label for="email" value="Alamat Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" placeholder="email@contoh.com" required />
            </div>
            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button>Kirim Undangan</x-primary-button>
            </div>
        </form>
    </x-modal>
    @endif

    {{-- Modal Add Group Transaction --}}
    <x-modal name="add-group-trx" focusable>
        <form method="POST" action="{{ route('groups.transactions.store', $group) }}" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-5">
                <div class="p-2.5 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Catat Transaksi Grup</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Transaksi ini akan terlihat oleh semua anggota grup.</p>
                </div>
            </div>
            <div class="space-y-4">
                <div>
                    <x-input-label value="Kategori" />
                    <select name="category_id" class="mt-1 block w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories->groupBy('type') as $type => $cats)
                            <optgroup label="{{ $type === 'expense' ? '💸 Pengeluaran' : '💰 Pemasukan' }}">
                                @foreach($cats as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->icon }} {{ $cat->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label value="Tanggal" />
                    <x-text-input name="transaction_date" type="date" value="{{ date('Y-m-d') }}" class="mt-1 block w-full" required />
                </div>
                <div>
                    <x-input-label value="Nominal (Rp)" />
                    <x-text-input name="amount" type="number" min="1" class="mt-1 block w-full" placeholder="150000" required />
                </div>
                <div>
                    <x-input-label value="Keterangan" />
                    <x-text-input name="description" type="text" class="mt-1 block w-full" placeholder="Contoh: Belanja Bulanan Bersama" required />
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button>💾 Simpan Transaksi</x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
