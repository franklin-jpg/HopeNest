@extends('layouts.admin')

@section('title', 'Compare Campaigns')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Campaign Comparison</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Compare performance metrics across multiple campaigns</p>
        </div>
        <a href="{{ route('admin.analytics.index') }}" class="btn btn-outline-primary dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-400 dark:hover:text-white">
            <i class="fas fa-arrow-left mr-2"></i>Back to Analytics
        </a>
    </div>

    <!-- Campaign Selector -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 mb-8">
        <form action="{{ route('admin.analytics.compare') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Campaigns (2-5)</label>
                    <select name="campaigns[]" multiple class="form-select w-full bg-white dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white" size="6" required>
                        @foreach(App\Models\Campaign::where('status', '!=', 'draft')->get() as $camp)
                        <option value="{{ $camp->id }}" {{ in_array($camp->id, request()->get('campaigns', [])) ? 'selected' : '' }}>
                            {{ $camp->title }}
                        </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Hold Ctrl (Cmd on Mac) to select multiple</p>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary w-full dark:bg-blue-600 dark:hover:bg-blue-700">
                        <i class="fas fa-balance-scale mr-2"></i>Compare Selected
                    </button>
                </div>
            </div>
        </form>
    </div>

    @if(isset($comparison) && $comparison->count() > 0)
    <!-- Comparison Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-{{ min($comparison->count(), 4) }} gap-6 mb-8">
        @foreach($comparison as $camp)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-400 transition-colors">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 truncate">{{ $camp['title'] }}</h3>
            
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Total Raised</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">₦{{ number_format($camp['total_raised'], 0) }}</p>
                </div>
                
                <div>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Goal</p>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">₦{{ number_format($camp['goal_amount'], 0) }}</p>
                </div>
                
                <div>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Progress</p>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-1">
                        <div class="bg-blue-600 dark:bg-blue-500 h-2 rounded-full" style="width: {{ min($camp['progress_percentage'], 100) }}%"></div>
                    </div>
                    <p class="text-xs font-semibold text-blue-600 dark:text-blue-400">{{ number_format($camp['progress_percentage'], 1) }}%</p>
                </div>
                
                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between mb-2">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Donors:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($camp['total_donors']) }}</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Donations:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ number_format($camp['total_donations']) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Avg Donation:</span>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">₦{{ number_format($camp['average_donation'], 0) }}</span>
                    </div>
                </div>
            </div>
            
            <a href="{{ route('admin.analytics.show', $camp['id']) }}" class="btn btn-sm btn-outline-primary w-full mt-4 dark:border-blue-400 dark:text-blue-400 dark:hover:bg-blue-400 dark:hover:text-white">
                View Details
            </a>
        </div>
        @endforeach
    </div>

    <!-- Detailed Comparison Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Detailed Comparison</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Metric</th>
                        @foreach($comparison as $camp)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            {{ Str::limit($camp['title'], 20) }}
                        </th>
                        @endforeach
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Leader</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Total Raised -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Total Raised</td>
                        @foreach($comparison as $camp)
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $camp['total_raised'] == $comparison->max('total_raised') ? 'font-bold text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                            ₦{{ number_format($camp['total_raised'], 0) }}
                        </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <i class="fas fa-trophy text-yellow-500 dark:text-yellow-400"></i>
                        </td>
                    </tr>

                    <!-- Progress Percentage -->
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Progress %</td>
                        @foreach($comparison as $camp)
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $camp['progress_percentage'] == $comparison->max('progress_percentage') ? 'font-bold text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ number_format($camp['progress_percentage'], 1) }}%
                        </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <i class="fas fa-trophy text-yellow-500 dark:text-yellow-400"></i>
                        </td>
                    </tr>

                    <!-- Total Donors -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Total Donors</td>
                        @foreach($comparison as $camp)
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $camp['total_donors'] == $comparison->max('total_donors') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ number_format($camp['total_donors']) }}
                        </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <i class="fas fa-trophy text-yellow-500 dark:text-yellow-400"></i>
                        </td>
                    </tr>

                    <!-- Total Donations -->
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Total Donations</td>
                        @foreach($comparison as $camp)
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $camp['total_donations'] == $comparison->max('total_donations') ? 'font-bold text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400' }}">
                            {{ number_format($camp['total_donations']) }}
                        </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <i class="fas fa-trophy text-yellow-500 dark:text-yellow-400"></i>
                        </td>
                    </tr>

                    <!-- Average Donation -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Avg Donation</td>
                        @foreach($comparison as $camp)
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $camp['average_donation'] == $comparison->max('average_donation') ? 'font-bold text-purple-600 dark:text-purple-400' : 'text-gray-500 dark:text-gray-400' }}">
                            ₦{{ number_format($camp['average_donation'], 0) }}
                        </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <i class="fas fa-trophy text-yellow-500 dark:text-yellow-400"></i>
                        </td>
                    </tr>

                    <!-- Days Active -->
                    <tr class="bg-gray-50 dark:bg-gray-900">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Days Active</td>
                        @foreach($comparison as $camp)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $camp['days_active'] }} days
                        </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap text-sm">-</td>
                    </tr>

                    <!-- Goal Amount -->
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">Goal Amount</td>
                        @foreach($comparison as $camp)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            ₦{{ number_format($camp['goal_amount'], 0) }}
                        </td>
                        @endforeach
                        <td class="px-6 py-4 whitespace-nowrap text-sm">-</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Visual Comparisons -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Total Raised Comparison -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Total Raised Comparison</h3>
            <canvas id="raisedComparisonChart"></canvas>
        </div>

        <!-- Progress Comparison -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Progress Comparison</h3>
            <canvas id="progressComparisonChart"></canvas>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Donors Comparison -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Donors vs Donations</h3>
            <canvas id="donorsComparisonChart"></canvas>
        </div>

        <!-- Average Donation Comparison -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4">Average Donation Comparison</h3>
            <canvas id="avgDonationChart"></canvas>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        const campaignTitles = @json($comparison->pluck('title')->map(fn($t) => Str::limit($t, 25)));
        const colors = [
            'rgba(59, 130, 246, 0.8)',
            'rgba(16, 185, 129, 0.8)',
            'rgba(251, 146, 60, 0.8)',
            'rgba(168, 85, 247, 0.8)',
            'rgba(236, 72, 153, 0.8)'
        ];

        // Detect dark mode
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#e5e7eb' : '#374151';
        const gridColor = isDarkMode ? '#374151' : '#e5e7eb';

        const defaultOptions = {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: textColor
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: textColor },
                    grid: { color: gridColor }
                },
                y: {
                    ticks: { color: textColor },
                    grid: { color: gridColor }
                }
            }
        };

        // Total Raised Comparison
        new Chart(document.getElementById('raisedComparisonChart'), {
            type: 'bar',
            data: {
                labels: campaignTitles,
                datasets: [{
                    label: 'Total Raised (₦)',
                    data: @json($comparison->pluck('total_raised')),
                    backgroundColor: colors,
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    legend: { display: false },
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

        // Progress Comparison
        new Chart(document.getElementById('progressComparisonChart'), {
            type: 'bar',
            data: {
                labels: campaignTitles,
                datasets: [{
                    label: 'Progress %',
                    data: @json($comparison->pluck('progress_percentage')),
                    backgroundColor: colors,
                }]
            },
            options: {
                ...defaultOptions,
                plugins: { legend: { display: false } },
                scales: {
                    ...defaultOptions.scales,
                    y: {
                        ...defaultOptions.scales.y,
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Donors Comparison
        new Chart(document.getElementById('donorsComparisonChart'), {
            type: 'bar',
            data: {
                labels: campaignTitles,
                datasets: [
                    {
                        label: 'Donors',
                        data: @json($comparison->pluck('total_donors')),
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    },
                    {
                        label: 'Donations',
                        data: @json($comparison->pluck('total_donations')),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                    }
                ]
            },
            options: defaultOptions
        });

        // Average Donation Comparison
        new Chart(document.getElementById('avgDonationChart'), {
            type: 'bar',
            data: {
                labels: campaignTitles,
                datasets: [{
                    label: 'Average Donation (₦)',
                    data: @json($comparison->pluck('average_donation')),
                    backgroundColor: colors,
                }]
            },
            options: {
                ...defaultOptions,
                plugins: {
                    legend: { display: false },
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
    </script>
    @endpush
    @endif
</div>
@endsection