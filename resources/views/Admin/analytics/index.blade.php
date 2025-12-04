@extends('layouts.admin')

@section('title', 'Campaign Analytics Overview')

@section('content')
<div class="container-fluid px-4 py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Campaign Analytics</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Comprehensive insights across all campaigns</p>
        </div>
        
        <div class="flex gap-3">
            <select name="date_range" id="dateRange" class="form-select rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400">
                <option value="7" {{ $dateRange == 7 ? 'selected' : '' }}>Last 7 days</option>
                <option value="30" {{ $dateRange == 30 ? 'selected' : '' }}>Last 30 days</option>
                <option value="90" {{ $dateRange == 90 ? 'selected' : '' }}>Last 90 days</option>
                <option value="365" {{ $dateRange == 365 ? 'selected' : '' }}>Last year</option>
            </select>
            
            <a href="{{ route('admin.analytics.compare') }}" class="btn btn-outline-primary px-4 py-2 rounded-lg border border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors duration-200 flex items-center">
                <i class="fas fa-balance-scale mr-2"></i>Compare Campaigns
            </a>
        </div>
    </div>

    <!-- Overall Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Raised -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 p-6 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-900 transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-green-100 dark:bg-green-900/30 rounded-lg p-3">
                    <i class="fas fa-dollar-sign text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Raised</h3>
           <p class="text-3xl font-bold text-gray-900 dark:text-white break-words">
    ₦{{ number_format($overallStats['total_raised'], 2) }}
</p>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Across {{ $overallStats['active_campaigns'] }} active campaigns</p>
        </div>

        <!-- Total Donors -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 p-6 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-900 transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-lg p-3">
                    <i class="fas fa-users text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Donors</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($overallStats['total_donors']) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ number_format($overallStats['total_donations']) }} donations made</p>
        </div>

        <!-- Average Donation -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 p-6 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-900 transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-purple-100 dark:bg-purple-900/30 rounded-lg p-3">
                    <i class="fas fa-chart-line text-2xl text-purple-600 dark:text-purple-400"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Average Donation</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">₦{{ number_format($overallStats['average_donation'], 2) }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Per transaction</p>
        </div>

        <!-- Total Campaigns -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 p-6 border border-gray-200 dark:border-gray-700 hover:shadow-md dark:hover:shadow-gray-900 transition-shadow duration-200">
            <div class="flex items-center justify-between mb-4">
                <div class="bg-orange-100 dark:bg-orange-900/30 rounded-lg p-3">
                    <i class="fas fa-bullhorn text-2xl text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
            <h3 class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Total Campaigns</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $overallStats['total_campaigns'] }}</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $overallStats['active_campaigns'] }} currently active</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Donations Timeline Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Donations Timeline</h3>
            <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
                <canvas id="donationsChart" height="80"></canvas>
            </div>
        </div>

        <!-- Category Performance -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Category Performance</h3>
            <div class="space-y-4">
                @foreach($categoryPerformance as $category)
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $category->category_name }}</span>
                        <span class="text-sm font-bold text-gray-900 dark:text-white">₦{{ number_format($category->total_raised, 0) }}</span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        @php
                            $maxRaised = $categoryPerformance->max('total_raised');
                            $percentage = $maxRaised > 0 ? ($category->total_raised / $maxRaised) * 100 : 0;
                        @endphp
                        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $category->campaigns_count }} campaigns • {{ $category->donations_count }} donations</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Top Performing Campaigns -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700 mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Top Performing Campaigns</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaign</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Goal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Raised</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Donors</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($topCampaigns as $campaign)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                            {{ Str::limit($campaign['title'], 40) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            ₦{{ number_format($campaign['goal_amount'], 0) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600 dark:text-green-400">
                            ₦{{ number_format($campaign['total_raised'], 0) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2 mr-3">
                                    <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full transition-all duration-500" style="width: {{ min($campaign['progress_percentage'], 100) }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ number_format($campaign['progress_percentage'], 1) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ number_format($campaign['donors_count']) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.analytics.show', $campaign['id']) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium transition-colors duration-150">
                                View Details <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Recent High-Value Donations -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm dark:shadow-gray-900/50 border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent High-Value Donations</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Donor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaign</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($recentHighValueDonations as $donation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="bg-blue-100 dark:bg-blue-900/30 rounded-full h-10 w-10 flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600 dark:text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $donation->is_anonymous ? 'Anonymous' : $donation->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $donation->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-white">
                                @if($donation->campaign)
                                    {{ Str::limit($donation->campaign->title, 30) }}
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 italic">[Campaign Deleted]</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-green-600 dark:text-green-400">₦{{ number_format($donation->amount, 2) }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $donation->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Detect dark mode and set colors dynamically
    function getChartColors() {
        const isDarkMode = document.documentElement.classList.contains('dark') ||
                          window.matchMedia('(prefers-color-scheme: dark)').matches;

        return {
            gridColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
            textColor: isDarkMode ? '#e5e7eb' : '#4b5563',
            borderColor: isDarkMode ? '#374151' : '#e5e7eb',
            tooltipBg: isDarkMode ? '#1f2937' : '#ffffff',
            tooltipBorder: isDarkMode ? '#374151' : '#e5e7eb',
            isDarkMode: isDarkMode
        };
    }

    let donationsChart;

    function initChart() {
        const colors = getChartColors();
        const ctx = document.getElementById('donationsChart').getContext('2d');
        
        // Destroy existing chart if it exists
        if (donationsChart) {
            donationsChart.destroy();
        }

        donationsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($donationsTimeline->pluck('date')),
                datasets: [
                    {
                        label: 'Amount Raised',
                        data: @json($donationsTimeline->pluck('total')),
                        borderColor: colors.isDarkMode ? 'rgb(96, 165, 250)' : 'rgb(59, 130, 246)',
                        backgroundColor: colors.isDarkMode ? 'rgba(96, 165, 250, 0.15)' : 'rgba(59, 130, 246, 0.15)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: colors.isDarkMode ? 'rgb(96, 165, 250)' : 'rgb(59, 130, 246)',
                        pointBorderColor: colors.isDarkMode ? '#1f2937' : '#ffffff',
                        pointBorderWidth: 2,
                    },
                    {
                        label: 'Number of Donations',
                        data: @json($donationsTimeline->pluck('count')),
                        borderColor: colors.isDarkMode ? 'rgb(52, 211, 153)' : 'rgb(16, 185, 129)',
                        backgroundColor: colors.isDarkMode ? 'rgba(52, 211, 153, 0.15)' : 'rgba(16, 185, 129, 0.15)',
                        tension: 0.4,
                        fill: true,
                        yAxisID: 'y1',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointHoverRadius: 5,
                        pointBackgroundColor: colors.isDarkMode ? 'rgb(52, 211, 153)' : 'rgb(16, 185, 129)',
                        pointBorderColor: colors.isDarkMode ? '#1f2937' : '#ffffff',
                        pointBorderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { 
                        labels: { 
                            color: colors.textColor,
                            padding: 15,
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: colors.tooltipBg,
                        titleColor: colors.textColor,
                        bodyColor: colors.textColor,
                        borderColor: colors.tooltipBorder,
                        borderWidth: 1,
                        padding: 12,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += context.datasetIndex === 0
                                        ? '₦' + context.parsed.y.toLocaleString()
                                        : context.parsed.y;
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    x: { 
                        grid: { 
                            color: colors.gridColor,
                            drawBorder: false
                        }, 
                        ticks: { 
                            color: colors.textColor,
                            font: {
                                size: 11
                            }
                        },
                        border: {
                            color: colors.borderColor
                        }
                    },
                    y: {
                        grid: { 
                            color: colors.gridColor,
                            drawBorder: false
                        },
                        ticks: { 
                            color: colors.textColor,
                            font: {
                                size: 11
                            },
                            callback: function(value) {
                                return '₦' + value.toLocaleString();
                            }
                        },
                        title: { 
                            display: true, 
                            text: 'Amount (₦)', 
                            color: colors.textColor,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        },
                        border: {
                            color: colors.borderColor
                        }
                    },
                    y1: {
                        position: 'right',
                        grid: { 
                            drawOnChartArea: false, 
                            color: colors.gridColor,
                            drawBorder: false
                        },
                        ticks: { 
                            color: colors.textColor,
                            font: {
                                size: 11
                            }
                        },
                        title: { 
                            display: true, 
                            text: 'Number of Donations', 
                            color: colors.textColor,
                            font: {
                                size: 12,
                                weight: '600'
                            }
                        },
                        border: {
                            color: colors.borderColor
                        }
                    }
                }
            }
        });
    }

    // Initialize chart on page load
    initChart();

    // Listen for system dark mode changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        initChart();
    });

    // Listen for manual dark mode toggle (if you have one)
    // You can trigger this with: document.dispatchEvent(new Event('darkModeToggle'));
    document.addEventListener('darkModeToggle', function() {
        initChart();
    });

    // Date range filter
    document.getElementById('dateRange').addEventListener('change', function() {
        window.location.href = '{{ route("admin.analytics.index") }}?date_range=' + this.value;
    });
</script>
@endpush
@endsection