<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 dark:text-white leading-tight">👨‍👩‍👧 Grup Keluarga</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Catat dan pantau pengeluaran bersama keluarga atau teman.</p>
            </div>
            <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-group')"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md hover:scale-105 transition-all flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Grup Baru
            </button>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            {{-- Info Banner --}}
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-4 flex items-start gap-3">
                <div class="text-blue-600 dark:text-blue-400 text-xl shrink-0">💡</div>
                <div>
                    <p class="text-sm text-blue-800 dark:text-blue-300 font-semibold">Cara Kerja Grup Keluarga</p>
                    <p class="text-xs text-blue-700 dark:text-blue-400 mt-0.5">Buat grup lalu undang anggota keluarga. Setiap anggota bisa mencatat transaksi bersama yang bisa dipantau oleh semua pihak dalam grup.</p>
                </div>
            </div>

            @if($groups->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-soft p-16 text-center mt-6">
                    <p class="text-5xl mb-4">🏠</p>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">Belum Ada Grup</h3>
                    <p class="text-gray-500 text-sm">Kamu belum tergabung dalam grup manapun. Buat sekarang!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    @foreach($groups as $group)
                        <a href="{{ route('groups.show', $group) }}" class="block bg-white dark:bg-gray-800 rounded-2xl shadow-soft hover:shadow-soft-lg transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 hover:border-blue-300 dark:hover:border-blue-700 hover:-translate-y-1">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-xl shadow-sm">
                                        {{ substr($group->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">{{ $group->name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            @if($group->owner_id === auth()->id())
                                                <span class="text-blue-600 dark:text-blue-400 font-semibold bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-md">Pemilik</span>
                                            @else
                                                <span class="bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded-md font-medium text-gray-600 dark:text-gray-300">Anggota</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4 mt-2">
                                    <span class="flex items-center gap-1.5 font-medium">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        {{ $group->members_count }} Anggota
                                    </span>
                                    <span class="text-blue-600 dark:text-blue-400 font-bold group-hover:translate-x-1 transition-transform">Buka &rarr;</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    {{-- Modal Create Group --}}
    <x-modal name="create-group" focusable>
        <form method="POST" action="{{ route('groups.store') }}" class="p-6">
            @csrf
            <div class="flex items-center gap-3 mb-5">
                <div class="p-2.5 bg-blue-100 dark:bg-blue-900/40 rounded-xl">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">Buat Grup Baru</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Anda akan menjadi pemilik grup ini.</p>
                </div>
            </div>

            <div class="mb-5">
                <x-input-label for="name" value="Nama Grup" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Contoh: Keluarga Cemara, Tim Kantor" required />
                <p class="mt-1.5 text-xs text-gray-400">Maksimal 100 karakter.</p>
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button>🚀 Buat Grup</x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
