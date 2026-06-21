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
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow transition">
                    + Buat Grup Baru
                </button>
            </div>

            @if($groups->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-16 text-center mt-6">
                    <p class="text-5xl mb-4">🏠</p>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-2">Belum Ada Grup</h3>
                    <p class="text-gray-400 text-sm">Kamu belum tergabung dalam grup manapun. Buat sekarang!</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                    @foreach($groups as $group)
                        <a href="{{ route('groups.show', $group) }}" class="block bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition overflow-hidden border border-gray-100 dark:border-gray-700 hover:border-indigo-300 dark:hover:border-indigo-700">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                        {{ substr($group->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight">{{ $group->name }}</h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            @if($group->owner_id === auth()->id())
                                                <span class="text-indigo-600 dark:text-indigo-400 font-medium">Pemilik</span>
                                            @else
                                                Anggota
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="flex justify-between items-center text-sm text-gray-600 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4 mt-2">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                        {{ $group->members_count }} Anggota
                                    </span>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-medium group-hover:underline">Buka Grup &rarr;</span>
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
