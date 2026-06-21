<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">📊 Laporan Keuangan</h2>
            <form method="GET" action="{{ route('reports.index') }}" class="flex items-center gap-2">
                <label class="text-sm text-gray-600 dark:text-gray-400">Tahun:</label>
                <select name="year" onchange="this.form.submit()" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($availableYears as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Comparison Cards: This Month vs Last Month --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Expense Comparison --}}
                @php
                    $expenseDiff = $thisExpense - $lastExpense;
                    $expensePct = $lastExpense > 0 ? round(abs($expenseDiff) / $lastExpense * 100, 1) : 0;
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pengeluaran Bulan Ini vs Bulan Lalu</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($thisExpense, 0, ',', '.') }}</p>
                    <div class="mt-2 flex items-center gap-1 text-sm font-medium {{ $expenseDiff > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                        @if($expenseDiff > 0)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            +{{ $expensePct }}% dari bulan lalu
                        @elseif($expenseDiff < 0)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            -{{ $expensePct }}% dari bulan lalu 🎉
                        @else
                            Sama dengan bulan lalu
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Bulan lalu: Rp {{ number_format($lastExpense, 0, ',', '.') }}</p>
                </div>

                {{-- Income Comparison --}}
                @php
                    $incomeDiff = $thisIncome - $lastIncome;
                    $incomePct = $lastIncome > 0 ? round(abs($incomeDiff) / $lastIncome * 100, 1) : 0;
                @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pemasukan Bulan Ini vs Bulan Lalu</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($thisIncome, 0, ',', '.') }}</p>
                    <div class="mt-2 flex items-center gap-1 text-sm font-medium {{ $incomeDiff >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        @if($incomeDiff > 0)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            +{{ $incomePct }}% dari bulan lalu 🎉
                        @elseif($incomeDiff < 0)
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                            -{{ $incomePct }}% dari bulan lalu
                        @else
                            Sama dengan bulan lalu
                        @endif
                    </div>
                    <p class="text-xs text-gray-400 mt-1">Bulan lalu: Rp {{ number_format($lastIncome, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Bar Chart: Monthly Income vs Expense --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Tren Bulanan {{ $year }}</h3>
                <div class="relative h-80">
                    <canvas id="monthlyBarChart"></canvas>
                </div>
            </div>

            {{-- Top Categories & Monthly Table --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- Top 5 Boros --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">🔥 Kategori Paling Boros ({{ $year }})</h3>
                    @if($topCategories->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-4">Belum ada data.</p>
                    @else
                    @php $maxTotal = $topCategories->max('total'); @endphp
                    <div class="space-y-4">
                        @foreach($topCategories as $i => $cat)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-medium text-gray-700 dark:text-gray-300">
                                    {{ ['🥇','🥈','🥉','4️⃣','5️⃣'][$i] }} {{ $cat->category?->name ?? 'Lainnya' }}
                                </span>
                                <span class="text-gray-500 dark:text-gray-400">Rp {{ number_format($cat->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full bg-rose-500" style="width: {{ $maxTotal > 0 ? round($cat->total / $maxTotal * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                {{-- Monthly Summary Table --}}
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Ringkasan per Bulan {{ $year }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                            <thead class="bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Bulan</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Masuk</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Keluar</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Net</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($monthlySummary as $row)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="px-4 py-2 font-medium text-gray-800 dark:text-white">{{ $row['month'] }}</td>
                                    <td class="px-4 py-2 text-right text-emerald-600 dark:text-emerald-400">{{ $row['income'] > 0 ? 'Rp '.number_format($row['income'], 0, ',', '.') : '-' }}</td>
                                    <td class="px-4 py-2 text-right text-rose-600 dark:text-rose-400">{{ $row['expense'] > 0 ? 'Rp '.number_format($row['expense'], 0, ',', '.') : '-' }}</td>
                                    <td class="px-4 py-2 text-right font-semibold {{ $row['net'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $row['income'] > 0 || $row['expense'] > 0 ? 'Rp '.number_format($row['net'], 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = isDark ? '#374151' : '#e5e7eb';
        const textColor = isDark ? '#9ca3af' : '#6b7280';

        const labels = @json(array_column($monthlySummary, 'month'));
        const incomes = @json(array_column($monthlySummary, 'income'));
        const expenses = @json(array_column($monthlySummary, 'expense'));

        new Chart(document.getElementById('monthlyBarChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: incomes,
                        backgroundColor: 'rgba(16,185,129,0.7)',
                        borderRadius: 6,
                    },
                    {
                        label: 'Pengeluaran',
                        data: expenses,
                        backgroundColor: 'rgba(239,68,68,0.7)',
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: textColor } },
                    tooltip: {
                        callbacks: {
                            label: ctx => ctx.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(ctx.parsed.y)
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: gridColor },
                        ticks: {
                            color: textColor,
                            callback: v => 'Rp ' + new Intl.NumberFormat('id-ID', { notation: 'compact' }).format(v)
                        }
                    },
                    x: { grid: { display: false }, ticks: { color: textColor } }
                }
            }
        });
    });
    </script>
</x-app-layout>
