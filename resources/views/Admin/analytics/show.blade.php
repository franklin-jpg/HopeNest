
@extends('layouts.admin')

@section('title', 'Campaign Analytics - ' . $campaign->title)

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
<div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4 mb-6">
    <div class="flex-1">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('admin.analytics.index') }}" 
               class="flex items-center justify-center w-10 h-10 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all duration-200">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $campaign->title }}</h1>
        </div>
        <p class="text-gray-600 dark:text-gray-400 ml-[52px]">Detailed campaign analytics and insights</p>
    </div>
    
    <div class="flex flex-wrap items-center gap-3">
        <!-- Date Range Selector -->
        <div class="relative">
            <select name="date_range" 
                    id="dateRange" 
                    class="appearance-none px-4 py-2.5 pr-10 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200 cursor-pointer">
                <option value="7" {{ $dateRange == 7 ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $dateRange == 30 ? 'selected' : '' }}>Last 30 days</option>
                <option value="90" {{ $dateRange == 90 ? 'selected' : '' }}>Last 90 days</option>
                <option value="365" {{ $dateRange == 365 ? 'selected' : '' }}>All time</option>
            </select>
            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 dark:text-gray-400">
                <i class="fas fa-chevron-down text-xs"></i>
            </div>
        </div>
        
        <!-- Export Dropdown -->
        <div class="relative group">
            <button type="button" 
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-400 dark:hover:border-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent transition-all duration-200 font-medium"
                    onclick="toggleDropdown(event)">
                <i class="fas fa-download"></i>
                <span>Export</span>
                <i class="fas fa-chevron-down text-xs ml-1"></i>
            </button>
            
            <!-- Dropdown Menu -->
            <div class="dropdown-menu-custom hidden absolute right-0 mt-2 w-48 rounded-lg shadow-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden z-50">
                <a href="{{ route('admin.analytics.export', ['campaign' => $campaign->id, 'format' => 'csv']) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    <i class="fas fa-file-csv text-green-600 dark:text-green-400 w-5"></i>
                    <span>Export as CSV</span>
                </a>
                <a href="{{ route('admin.analytics.export', ['campaign' => $campaign->id, 'format' => 'pdf']) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 border-t border-gray-100 dark:border-gray-700">
                    <i class="fas fa-file-pdf text-red-600 dark:text-red-400 w-5"></i>
                    <span>Export as PDF</span>
                </a>
                <a href="{{ route('admin.analytics.export', ['campaign' => $campaign->id, 'format' => 'excel']) }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150 border-t border-gray-100 dark:border-gray-700">
                    <i class="fas fa-file-excel text-emerald-600 dark:text-emerald-400 w-5"></i>
                    <span>Export as Excel</span>
                </a>
            </div>
        </div>
        
        <!-- View Campaign Button -->
        <a href="{{ route('admin.campaigns.show', $campaign->id) }}" 
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-700 text-white font-medium shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900 transition-all duration-200">
            <i class="fas fa-eye"></i>
            <span>View Campaign</span>
        </a>
    </div>
</div>


    <!-- Key Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Raised -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                    <i class="fas fa-dollar-sign text-2xl text-green-600 dark:text-green-400"></i>
                </div>
                @if($growthRate != 0)
                <span class="text-sm font-semibold {{ $growthRate > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    <i class="fas fa-arrow-{{ $growthRate > 0 ? 'up' : 'down' }}"></i> {{ abs($growthRate) }}%
                </span>
                @endif
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Raised</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">₦{{ number_format($metrics['total_raised'], 2) }}</p>
            <div class="mt-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600 dark:text-gray-400">Goal: ₦{{ number_format($metrics['goal_amount'], 0) }}</span>
                    <span class="font-semibold text-blue-600 dark:text-blue-400">{{ number_format($metrics['progress_percentage'], 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="bg-green-600 dark:bg-green-500 h-2 rounded-full transition-all duration-300" style="width: {{ min($metrics['progress_percentage'], 100) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Total Donors -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                    <i class="fas fa-users text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Donors</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($metrics['total_donors']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ number_format($metrics['total_donations']) }} total donations</p>
        </div>

        <!-- Average Donation -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-3">
                    <i class="fas fa-chart-line text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Average Donation</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">₦{{ number_format($metrics['average_donation'], 2) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Median: ₦{{ number_format($metrics['median_donation'], 2) }}</p>
        </div>

        <!-- Conversion Rate -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-orange-100 dark:bg-orange-900/30 rounded-lg p-3">
                    <i class="fas fa-percentage text-2xl text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Conversion Rate</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($conversionRate, 2) }}%</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ number_format($viewStats['unique_views']) }} unique views</p>
        </div>
    </div>

    <!-- Additional Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Largest Donation</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">₦{{ number_format($metrics['largest_donation'], 2) }}</p>
                </div>
                <i class="fas fa-trophy text-yellow-500 dark:text-yellow-400 text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Views</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($viewStats['total_views']) }}</p>
                </div>
                <i class="fas fa-eye text-blue-500 dark:text-blue-400 text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Views (7 days)</p>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($viewStats['views_last_7_days']) }}</p>
                </div>
                <i class="fas fa-calendar-week text-green-500 dark:text-green-400 text-2xl"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Projected Completion</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $projectedCompletion }}</p>
                </div>
                <i class="fas fa-flag-checkered text-purple-500 dark:text-purple-400 text-2xl"></i>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Donation Timeline -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Donation Timeline</h3>
            <canvas id="donationTimelineChart" height="80"></canvas>
        </div>

        <!-- Donation Distribution -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Donation Distribution</h3>
            <canvas id="donationDistributionChart"></canvas>
            <div class="mt-4 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Under ₦50</span>
                    <span class="font-semibold dark:text-white">{{ $donationDistribution['under_50'] }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">₦50 - ₦100</span>
                    <span class="font-semibold dark:text-white">{{ $donationDistribution['50_to_100'] }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">₦100 - ₦500</span>
                    <span class="font-semibold dark:text-white">{{ $donationDistribution['100_to_500'] }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">₦500 - ₦1,000</span>
                    <span class="font-semibold dark:text-white">{{ $donationDistribution['500_to_1000'] }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">Over ₦1,000</span>
                    <span class="font-semibold dark:text-white">{{ $donationDistribution['over_1000'] }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Hourly Pattern -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Donations by Hour of Day</h3>
            <canvas id="hourlyPatternChart" height="100"></canvas>
        </div>

        <!-- Day of Week Pattern -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Donations by Day of Week</h3>
            <canvas id="dayPatternChart" height="100"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Traffic Sources -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Traffic Sources</h3>
            @if($trafficSources->isEmpty())
                <p class="text-gray-500 dark:text-gray-400 text-center py-8">No traffic data available yet</p>
            @else
                <div class="space-y-4">
                    @foreach($trafficSources as $source)
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $source['source'] }}</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ number_format($source['views']) }} views</span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            @php
                                $maxViews = $trafficSources->max('views');
                                $percentage = $maxViews > 0 ? ($source['views'] / $maxViews) * 100 : 0;
                            @endphp
                            <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ number_format($source['unique_views']) }} unique visitors</p>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Device Statistics -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Device Statistics</h3>
            <canvas id="deviceChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top Donors -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Top Donors</h3>
            </div>
            <div class="p-6">
                @if($topDonors->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No donors yet</p>
                @else
                    <div class="space-y-4">
                        @foreach($topDonors as $index => $donor)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="bg-gradient-to-br from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-full h-10 w-10 flex items-center justify-center text-white font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $donor->user->name ?? 'Anonymous' }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $donor->donation_count }} donations</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-green-600 dark:text-green-400">₦{{ number_format($donor->total_donated, 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Donations -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Donations</h3>
            </div>
            <div class="p-6">
                @if($recentDonations->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No donations yet</p>
                @else
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        @foreach($recentDonations as $donation)
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-full h-8 w-8 flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $donation->is_anonymous ? 'Anonymous' : $donation->user->name }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $donation->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <div class="text-sm font-bold text-green-600 dark:text-green-400">
                                ₦{{ number_format($donation->amount, 2) }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Additional Insights -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Anonymity Stats -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Donor Privacy</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Public</span>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $anonymityStats['named'] }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Anonymous</span>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ $anonymityStats['anonymous'] }}</span>
                </div>
            </div>
        </div>

        <!-- Campaign Status -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Campaign Info</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Status</span>
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        {{ $campaign->status === 'active' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400' : '' }}
                        {{ $campaign->status === 'draft' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400' : '' }}
                        {{ $campaign->status === 'completed' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' : '' }}">
                        {{ ucfirst($campaign->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Category</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $campaign->campaignCategory->name }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Started</span>
                    <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $campaign->start_date->format('M d, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Donation Timeline Chart
    new Chart(document.getElementById('donationTimelineChart'), {
        type: 'line',
        data: {
            labels: @json($donationTimeline->pluck('date')),
            datasets: [{
                label: 'Amount Raised (₦)',
                data: @json($donationTimeline->pluck('total')),
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: true },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₦' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });

    // Donation Distribution Pie Chart
    new Chart(document.getElementById('donationDistributionChart'), {
        type: 'doughnut',
        data: {
            labels: ['Under ₦50', '₦50-₦100', '₦100-₦500', '₦500-₦1K', 'Over ₦1K'],
            datasets: [{
                data: [
                    {{ $donationDistribution['under_50'] }},
                    {{ $donationDistribution['50_to_100'] }},
                    {{ $donationDistribution['100_to_500'] }},
                    {{ $donationDistribution['500_to_1000'] }},
                    {{ $donationDistribution['over_1000'] }}
                ],
                backgroundColor: [
                    'rgb(239, 68, 68)',
                    'rgb(251, 146, 60)',
                    'rgb(34, 197, 94)',
                    'rgb(59, 130, 246)',
                    'rgb(168, 85, 247)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Hourly Pattern Chart
    new Chart(document.getElementById('hourlyPatternChart'), {
        type: 'bar',
        data: {
            labels: @json($hourlyPattern->pluck('hour')->map(fn($h) => $h . ':00')),
            datasets: [{
                label: 'Donations',
                data: @json($hourlyPattern->pluck('count')),
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } }
        }
    });

    // Day Pattern Chart
    new Chart(document.getElementById('dayPatternChart'), {
        type: 'bar',
        data: {
            labels: @json($dayPattern->pluck('day')),
            datasets: [{
                label: 'Donations',
                data: @json($dayPattern->pluck('count')),
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { display: false } }
        }
    });

    // Device Chart
    new Chart(document.getElementById('deviceChart'), {
        type: 'pie',
        data: {
            labels: @json($deviceStats->pluck('device_type')),
            datasets: [{
                data: @json($deviceStats->pluck('count')),
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(16, 185, 129)',
                    'rgb(251, 146, 60)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Date range filter
    document.getElementById('dateRange').addEventListener('change', function() {
        window.location.href = '{{ route("admin.analytics.show", $campaign->id) }}?date_range=' + this.value;
    });
</script>


<script>
function toggleDropdown(event) {
    event.stopPropagation();
    const button = event.currentTarget;
    const dropdown = button.nextElementSibling;
    const allDropdowns = document.querySelectorAll('.dropdown-menu-custom');
    
    // Close all other dropdowns
    allDropdowns.forEach(menu => {
        if (menu !== dropdown) {
            menu.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('.dropdown-menu-custom');
    dropdowns.forEach(dropdown => {
        if (!dropdown.classList.contains('hidden')) {
            dropdown.classList.add('hidden');
        }
    });
});

// Prevent dropdown from closing when clicking inside
document.querySelectorAll('.dropdown-menu-custom').forEach(dropdown => {
    dropdown.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});
</script>

<style>
/* Custom scrollbar for select dropdown */
select#dateRange {
    background-image: none;
}

/* Smooth dropdown animation */
.dropdown-menu-custom {
    animation: slideDown 0.2s ease-out;
    transform-origin: top;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Ensure proper z-index layering */
.relative.group {
    position: relative;
    z-index: 10;
}

.dropdown-menu-custom {
    z-index: 50;
}
</style>

@endpush
@endsection