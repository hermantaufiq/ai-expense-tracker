<x-admin-layout>
    <x-slot name="title">Kelola Pengguna</x-slot>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-900/40 border border-emerald-700/50 text-emerald-300 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-white">Daftar Pengguna</h2>
                <p class="text-xs text-gray-400 mt-0.5">Kelola status akun dan langganan semua pengguna</p>
            </div>
            <span class="bg-gray-800 text-gray-300 text-xs font-medium px-3 py-1.5 rounded-full">
                {{ $users->total() }} pengguna
            </span>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-800">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Langganan</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Bergabung</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($users as $user)
                    <tr class="hover:bg-gray-800/50 transition-colors">
                        {{-- Avatar + Name --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="font-medium text-white">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-400">{{ $user->email }}</td>
                        {{-- Subscription Badge --}}
                        <td class="px-6 py-4">
                            @if($user->is_premium)
                                <span class="inline-flex items-center gap-1.5 bg-violet-500/10 text-violet-400 border border-violet-500/20 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Premium
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-gray-700/60 text-gray-400 text-xs font-medium px-2.5 py-1 rounded-full">
                                    Free
                                </span>
                            @endif
                        </td>
                        {{-- Status Badge --}}
                        <td class="px-6 py-4">
                            @if($user->is_banned)
                                <span class="inline-flex items-center gap-1 bg-red-500/10 text-red-400 border border-red-500/20 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    Banned
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-medium px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                    Aktif
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-end gap-2">
                                {{-- Toggle Premium --}}
                                <form action="{{ route('admin.users.toggle-premium', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="{{ $user->is_premium ? 'bg-orange-500/10 text-orange-400 hover:bg-orange-500/20 border-orange-500/30' : 'bg-violet-500/10 text-violet-400 hover:bg-violet-500/20 border-violet-500/30' }} border text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                        {{ $user->is_premium ? 'Downgrade' : 'Upgrade' }}
                                    </button>
                                </form>
                                {{-- Toggle Ban --}}
                                <form action="{{ route('admin.users.toggle-ban', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="{{ $user->is_banned ? 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 border-emerald-500/30' : 'bg-red-500/10 text-red-400 hover:bg-red-500/20 border-red-500/30' }} border text-xs font-medium px-3 py-1.5 rounded-lg transition-colors">
                                        {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            Belum ada pengguna terdaftar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $users->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
