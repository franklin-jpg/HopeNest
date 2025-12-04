@extends('layouts.admin')

@section('title', 'Donation Reports')

@push('styles')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }
    .dark .glass-card {
        background: rgba(30, 41, 59, 0.4);
        border-color: rgba(100, 116, 139, 0.3);
    }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 px-4 sm:px-6 lg:px-8 transition-colors duration-300">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Donation Reports</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    From {{ $report['period']['start'] }} to {{ $report['period']['end'] }}
                </p>
            </div>

            <div class="flex gap-3">
                <form method="GET" class="flex items-center">
                    <select name="period" onchange="this.form.submit()"
                            class="rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2.5 text-sm font-medium focus:ring-4 focus:ring-primary-300 dark:focus:ring-primary-800 focus:outline-none transition-all">
                        <option value="daily" {{ $period == 'daily' ? 'selected' : '' }}>Today</option>
                        <option value="weekly" {{ $period == 'weekly' ? 'selected' : '' }}>This Week</option>
                        <option value="monthly" {{ $period == 'monthly' ? 'selected' : '' }}>This Month</option>
                        <option value="yearly" {{ $period == 'yearly' ? 'selected' : '' }}>This Year</option>
                    </select>
                </form>

                <div class="flex gap-2">
                    <a href="{{ route('admin.reports.donations.export', ['period' => $period]) }}"
                       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-file-excel"></i> Excel
                    </a>
                    <a href="{{ route('admin.reports.donations.export', ['period' => $period, 'format' => 'pdf']) }}"
                       class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="glass-card rounded-2xl p-6 shadow-xl border border-gray-200 dark:border-gray-700 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Revenue</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            ₦{{ number_format($report['summary']['total_revenue']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <i class="fas fa-donate text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 shadow-xl border border-gray-200 dark:border-gray-700 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Net Revenue</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            ₦{{ number_format($report['summary']['net_revenue']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <i class="fas fa-wallet text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 shadow-xl border border-gray-200 dark:border-gray-700 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Donations</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($report['summary']['total_donations']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <i class="fas fa-heart text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>
            </div>

            <div class="glass-card rounded-2xl p-6 shadow-xl border border-gray-200 dark:border-gray-700 transform hover:scale-105 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg. Donation</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            ₦{{ number_format($report['summary']['avg_donation']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                        <i class="fas fa-chart-line text-2xl text-yellow-600 dark:text-yellow-400"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Tables -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Top Campaigns -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top Campaigns</h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-3">Campaign</th>
                                    <th class="px-4 py-3 text-center">Donations</th>
                                    <th class="px-4 py-3 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($report['by_campaign'] as $c)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <td class="px-4 py-4 font-medium text-gray-900 dark:text-white">{{ $c['campaign'] }}</td>
                                    <td class="px-4 py-4 text-center dark:text-gray-300">{{ $c['count'] }}</td>
                                    <td class="px-4 py-4 text-right font-semibold text-green-600 dark:text-green-400">
                                        ₦{{ number_format($c['total']) }}
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center py-8 text-gray-500">No donations yet</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Payment Methods</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($report['by_payment_method'] as $pm)
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center">
                                    <i class="fas fa-credit-card text-primary-600 dark:text-orange-400"></i>
                                </div>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $pm['method'] }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $pm['count'] }} donations</p>
                                <p class="font-bold text-green-600 dark:text-green-400">₦{{ number_format($pm['total']) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Optional: Add Chart Here Later -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Revenue Trend (Coming Soon)</h3>
            <div class="h-64 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-300 dark:border-gray-600">
                <p class="text-gray-500 dark:text-gray-400">Chart visualization coming soon</p>
            </div>
        </div>
    </div>
</div>
@endsection