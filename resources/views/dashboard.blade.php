<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Manajemen Keuangan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Balance Card -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl shadow-soft-lg p-6 text-white hover:scale-[1.02] transition-transform duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-100">Total Balance</p>
                            <p class="text-3xl font-bold mt-2">Rp {{ number_format($balance, 2, ',', '.') }}</p>
                        </div>
                        <div class="p-4 bg-white/20 rounded-full backdrop-blur-sm">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Income Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6 hover:shadow-soft-lg transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Income</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($totalIncome, 2, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-2xl dark:bg-emerald-900/30">
                            <svg class="w-6 h-6 text-emerald-500 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Expense Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6 hover:shadow-soft-lg transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Expense</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">Rp {{ number_format($totalExpense, 2, ',', '.') }}</p>
                        </div>
                        <div class="p-3 bg-rose-50 rounded-2xl dark:bg-rose-900/30">
                            <svg class="w-6 h-6 text-rose-500 dark:text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Line Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Pengeluaran 6 Bulan Terakhir</h3>
                    <div class="relative h-72 w-full">
                        <canvas id="monthlyExpenseChart"></canvas>
                    </div>
                </div>

                <!-- Pie Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Distribusi Pengeluaran</h3>
                    <div class="relative h-72 w-full flex justify-center">
                        <canvas id="categoryPieChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-soft overflow-hidden mb-8">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-white dark:bg-gray-800">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Transaksi Terbaru</h3>
                    <a href="{{ route('transactions.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">Lihat Semua &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kategori</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($recentTransactions as $transaction)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $transaction->transaction_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $transaction->description }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" style="background-color: {{ $transaction->category ? $transaction->category->color_code . '33' : '#e5e7eb' }}; color: {{ $transaction->category ? $transaction->category->color_code : '#374151' }};">
                                            {{ $transaction->category ? $transaction->category->name : 'Uncategorized' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium {{ $transaction->category && $transaction->category->type == 'income' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $transaction->category && $transaction->category->type == 'income' ? '+' : '-' }}Rp {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center dark:text-gray-400">Belum ada transaksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if dark mode is active to adjust chart colors
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#e5e7eb' : '#374151';
            const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

            Chart.defaults.color = textColor;
            Chart.defaults.font.family = "'Figtree', sans-serif";

            // Monthly Expense Line Chart
            const ctxLine = document.getElementById('monthlyExpenseChart').getContext('2d');
            window.lineChart = new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyExpenses['labels'] ?? []) !!},
                    datasets: [{
                        label: 'Pengeluaran',
                        data: {!! json_encode($monthlyExpenses['data'] ?? []) !!},
                        borderColor: '#2563eb', // Primary-600
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#2563eb',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: gridColor, drawBorder: false },
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', { notation: "compact" }).format(value);
                                }
                            }
                        },
                        x: {
                            grid: { display: false, drawBorder: false }
                        }
                    }
                }
            });

            // Category Pie Chart
            const ctxPie = document.getElementById('categoryPieChart').getContext('2d');
            window.pieChart = new Chart(ctxPie, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($pieChart['labels'] ?? []) !!},
                    datasets: [{
                        data: {!! json_encode($pieChart['data'] ?? []) !!},
                        backgroundColor: {!! json_encode($pieChart['colors'] ?? []) !!},
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed !== null) {
                                        label += 'Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed);
                                    }
                                    return label;
                                }
                            }
                        }
                    }
                }
            });

            // Listen for theme changes to update charts dynamically
            window.addEventListener('theme-toggled', function(e) {
                const isDark = e.detail.isDark;
                const newTextColor = isDark ? '#e5e7eb' : '#374151';
                const newGridColor = isDark ? '#374151' : '#e5e7eb';

                Chart.defaults.color = newTextColor;

                if (window.lineChart) {
                    window.lineChart.options.scales.y.grid.color = newGridColor;
                    window.lineChart.update();
                }

                if (window.pieChart) {
                    window.pieChart.update();
                }
            });
        });
    </script>
</x-app-layout>
