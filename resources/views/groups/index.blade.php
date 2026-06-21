<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">👨‍👩‍👧 Mode Keluarga & Grup</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Catat dan pantau pengeluaran bersama pasangan atau teman.</p>
                <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-group')"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-md transition">
                    + Buat Grup Baru
                </button>
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
            <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-4">Buat Grup Baru</h2>
            
            <div class="mb-5">
                <x-input-label for="name" value="Nama Grup" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" placeholder="Contoh: Keluarga Cemara, Geng Liburan" required />
            </div>

            <div class="flex justify-end gap-3">
                <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
                <x-primary-button>Buat</x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
