<x-admin-layout>
    <x-slot name="title">Global AI Rules</x-slot>

    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 bg-emerald-900/40 border border-emerald-700/50 text-emerald-300 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 flex items-center gap-3 bg-red-900/40 border border-red-700/50 text-red-300 px-4 py-3 rounded-xl text-sm">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Add Rule Form --}}
        <div class="lg:col-span-1">
            <div class="bg-gray-900 rounded-2xl border border-gray-800 p-6">
                <h3 class="text-sm font-semibold text-white mb-1">Tambah AI Rule Baru</h3>
                <p class="text-xs text-gray-400 mb-5">Kata kunci ini akan berlaku untuk semua pengguna secara global.</p>

                <form action="{{ route('admin.ai-rules.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Kata Kunci</label>
                        <input type="text" name="keyword" placeholder="Contoh: krl, indomaret, alfamart"
                            class="w-full bg-gray-800 border border-gray-700 focus:border-violet-500 focus:ring-0 text-white placeholder-gray-500 rounded-xl px-4 py-2.5 text-sm transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1.5">Kategori Tujuan</label>
                        <select name="category_id" class="w-full bg-gray-800 border border-gray-700 focus:border-violet-500 focus:ring-0 text-white rounded-xl px-4 py-2.5 text-sm transition-colors">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ ucfirst($cat->type) }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-violet-600 hover:bg-violet-700 text-white text-sm font-semibold py-2.5 px-4 rounded-xl transition-colors">
                        Tambah Rule
                    </button>
                </form>
            </div>
        </div>

        {{-- Rules Table --}}
        <div class="lg:col-span-2">
            <div class="bg-gray-900 rounded-2xl border border-gray-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-white">Semua Global AI Rules</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $rules->total() }} rules aktif</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-800">
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kata Kunci</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipe</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-gray-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($rules as $rule)
                            <tr class="hover:bg-gray-800/50 transition-colors">
                                <td class="px-6 py-3">
                                    <code class="bg-gray-800 text-violet-300 text-xs px-2 py-1 rounded-md">{{ $rule->keyword }}</code>
                                </td>
                                <td class="px-6 py-3">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full" style="background-color: {{ $rule->category->color_code ?? '#6b7280' }}"></span>
                                        <span class="text-gray-300">{{ $rule->category->name ?? '–' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-3">
                                    @if($rule->category && $rule->category->type === 'income')
                                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs font-medium px-2 py-0.5 rounded-full">Pemasukan</span>
                                    @else
                                        <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 text-xs font-medium px-2 py-0.5 rounded-full">Pengeluaran</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <form action="{{ route('admin.ai-rules.destroy', $rule->id) }}" method="POST"
                                          onsubmit="return confirm('Hapus rule \'{{ $rule->keyword }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-400 hover:text-red-300 hover:bg-red-900/30 px-2 py-1 rounded-lg transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 text-sm">
                                    Belum ada AI Rules. Tambahkan rule pertama Anda!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($rules->hasPages())
                <div class="px-6 py-4 border-t border-gray-800">{{ $rules->links() }}</div>
                @endif
            </div>
        </div>

    </div>

</x-admin-layout>
