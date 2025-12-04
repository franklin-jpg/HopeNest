@extends('layouts.admin')
@section('content')
<main class="flex-1 overflow-y-auto p-6">

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Donations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Total Donations</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        ${{ number_format($stats['total_donations'], 2) }}
                    </h3>
                    <p class="text-{{ $stats['donations_growth'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $stats['donations_growth'] >= 0 ? 'green' : 'red' }}-400 text-sm mt-2">
                        <i class="fas fa-arrow-{{ $stats['donations_growth'] >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['donations_growth']) }}% from last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Total Users -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Total Users</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ number_format($stats['total_users']) }}
                    </h3>
                    <p class="text-{{ $stats['users_growth'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $stats['users_growth'] >= 0 ? 'green' : 'red' }}-400 text-sm mt-2">
                        <i class="fas fa-arrow-{{ $stats['users_growth'] >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['users_growth']) }}% from last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>

        <!-- Active Campaigns -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Active Campaigns</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ number_format($stats['active_campaigns']) }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">
                        {{ $campaignsEndingSoon }} ending this week
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
        </div>

        <!-- Volunteers -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Active Volunteers</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ number_format($stats['total_volunteers']) }}
                    </h3>
                    <p class="text-{{ $stats['volunteers_growth'] >= 0 ? 'green' : 'red' }}-600 dark:text-{{ $stats['volunteers_growth'] >= 0 ? 'green' : 'red' }}-400 text-sm mt-2">
                        <i class="fas fa-arrow-{{ $stats['volunteers_growth'] >= 0 ? 'up' : 'down' }}"></i> 
                        {{ abs($stats['volunteers_growth']) }}% from last month
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-friends text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
        </div>
    </div>

    
    <!-- Charts Section -->
    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <!-- Donation Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Donation Trends (Last 7 Days)</h3>
            </div>
            <div class="h-64">
                <canvas id="donationChart"></canvas>
            </div>
        </div>

        <!-- Recent Activities -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Activities</h3>
            <div class="space-y-4 max-h-64 overflow-y-auto">
                @forelse($recentActivities as $activity)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-{{ str_replace('blue-600', 'blue-100 dark:bg-blue-900/30', $activity['color']) }}/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fas {{ $activity['icon'] }} text-{{ $activity['color'] }} dark:text-{{ $activity['color'] }} text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 dark:text-white">{{ $activity['title'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $activity['description'] }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $activity['time_human'] }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No recent activities</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Recent Donations -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/40">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Recent Donations</h3>
                <a href="{{ route('admin.donations.index') }}" class="text-primary hover:underline text-sm">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Donor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Campaign</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentDonations as $donation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200">{{ $donation['donor_name'] }}</td>
                                <td class="px-6 py-4 text-sm font-medium text-green-600 dark:text-green-400">${{ number_format($donation['amount'], 2) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ Str::limit($donation['campaign'], 20) }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">{{ $donation['created_at'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">No donations yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Campaigns -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/40">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Top Campaigns</h3>
                <a href="{{ route('admin.campaigns.index') }}" class="text-primary hover:underline text-sm">View All</a>
            </div>
            <div class="p-6 space-y-4">
                @forelse($topCampaigns as $campaign)
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ Str::limit($campaign['title'], 30) }}</p>
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $campaign['progress_percentage'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="bg-primary h-2 rounded-full" style="width: {{ $campaign['progress_percentage'] }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            ${{ number_format($campaign['total_raised'], 2) }} of ${{ number_format($campaign['goal_amount'], 2) }}
                        </p>
                    </div>
                @empty
                    <p class="text-gray-500 dark:text-gray-400 text-sm text-center py-4">No campaigns yet</p>
                @endforelse
            </div>
        </div>
    </div>

</main>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Donation Trend Chart
    const ctx = document.getElementById('donationChart').getContext('2d');
    const donationData = @json($donationTrend);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: donationData.map(d => d.date),
            datasets: [{
                label: 'Donation Amount',
                data: donationData.map(d => d.total),
                borderColor: '#10B981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection