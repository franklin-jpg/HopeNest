@extends('layouts.admin')

@section('title', 'Campaign Reports')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Campaign Performance Reports</h1>
                <p class="text-gray-600 dark:text-gray-400">Analyze success rates, engagement, and fundraising efficiency</p>
            </div>
          <div class="flex gap-4">
    <!-- Export Excel Button -->
    <a href="{{ route('admin.reports.campaigns.export') }}"
       class="inline-flex items-center gap-2.5 px-5 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-sm rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
        <i class="fas fa-file-excel text-lg"></i>
        <span>Export Excel</span>
    </a>

    <!-- Export PDF Button -->
    <a href="{{ route('admin.reports.campaigns.export', ['format' => 'pdf']) }}"
       class="inline-flex items-center gap-2.5 px-5 py-3 bg-rose-600 hover:bg-rose-700 text-white font-medium text-sm rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
        <i class="fas fa-file-pdf text-lg"></i>
        <span>Export PDF</span>
    </a>
</div>
        </div>

       <!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Success Rate -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Success Rate</p>
                <p class="mt-2 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                    {{ $report['success_rate'] }}%
                </p>
            </div>
            <div class="p-4 bg-emerald-100 dark:bg-emerald-900 rounded-full">
                <i class="fas fa-trophy text-2xl text-emerald-600 dark:text-emerald-300"></i>
            </div>
        </div>
    </div>

    <!-- Avg Time to Goal -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Time to Goal</p>
                <p class="mt-2 text-3xl font-bold text-blue-600 dark:text-blue-400">
                    {{ $report['avg_time_to_goal_days'] }} <span class="text-lg">days</span>
                </p>
            </div>
            <div class="p-4 bg-blue-100 dark:bg-blue-900 rounded-full">
                <i class="fas fa-clock text-2xl text-blue-600 dark:text-blue-300"></i>
            </div>
        </div>
    </div>

    <!-- Goal Achieved -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Goal Achieved</p>
                <p class="mt-2 text-3xl font-bold text-purple-600 dark:text-purple-400">
                    {{ $report['goal_achieved_count'] }} / {{ $report['total_campaigns'] }}
                </p>
            </div>
            <div class="p-4 bg-purple-100 dark:bg-purple-900 rounded-full">
                <i class="fas fa-check-circle text-2xl text-purple-600 dark:text-purple-300"></i>
            </div>
        </div>
    </div>

    <!-- Total Raised -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 hover:shadow-xl transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Raised</p>
                <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">
                    ₦{{ number_format($report['total_raised']) }}
                </p>
            </div>
            <div class="p-4 bg-green-100 dark:bg-green-900 rounded-full">
                <i class="fas fa-donate text-2xl text-green-600 dark:text-green-300"></i>
            </div>
        </div>
    </div>
</div>

        <!-- Top Performers -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Top Performing Campaigns</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Campaign</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Raised</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Goal</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Progress</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Donations</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($report['top_performers'] as $c)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">{{ Str::limit($c['title'], 40) }}</div>
                                    <div class="text-sm text-gray-500">{{ $c['category'] ?? 'Uncategorized' }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center font-bold text-green-600">₦{{ number_format($c['raised']) }}</td>
                            <td class="px-6 py-4 text-center dark:text-gray-300">₦{{ number_format($c['goal']) }}</td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center">
                                    <span class="font-bold {{ $c['progress'] >= 100 ? 'text-emerald-600' : 'text-blue-600' }}">{{ $c['progress'] }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center dark:text-gray-300">{{ $c['donations'] }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $c['goal_achieved'] ? 'bg-emerald-100 text-emerald-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $c['goal_achieved'] ? 'Achieved' : 'Ongoing' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection