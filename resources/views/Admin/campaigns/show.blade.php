@extends('layouts.admin')

@section('title', 'Campaign Analytics - ' . $campaign->title)

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <nav class="flex mb-2" aria-label="breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.campaigns.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary">
                            Campaigns
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-gray-500 dark:text-gray-400">Analytics</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $campaign->title }}</h2>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.campaigns.edit', $campaign->id) }}" class="px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-colors inline-flex items-center gap-2">
                <i class="fas fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ route('admin.campaigns.updates.create', $campaign->id) }}" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-secondary transition-colors inline-flex items-center gap-2">
                <i class="fas fa-bullhorn"></i>
                <span>Add Update</span>
            </a>
        </div>
    </div>

    <!-- Campaign Status Banner -->
    <div class="mb-6 p-4 rounded-lg {{ $campaign->status == 'active' ? 'bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700' : ($campaign->status == 'draft' ? 'bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-400 dark:border-yellow-700' : 'bg-blue-100 dark:bg-blue-900/30 border border-blue-400 dark:border-blue-700') }}">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="text-gray-900 dark:text-gray-100">
                <strong>Status:</strong> <span class="capitalize">{{ $campaign->status }}</span>
                @if($campaign->end_date)
                    <span class="ml-4"><strong>Days Remaining:</strong> {{ $campaign->daysRemaining() ?? 0 }}</span>
                @endif
            </div>
            <div>
                <form action="{{ route('admin.campaigns.toggle-status', $campaign->id) }}" method="POST" class="inline">
                    @csrf
                    @method('PATCH')
                    @if($campaign->status == 'active')
                        <button type="submit" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-pause"></i>
                            <span>Pause</span>
                        </button>
                    @elseif($campaign->status == 'draft')
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors inline-flex items-center gap-2">
                            <i class="fas fa-play"></i>
                            <span>Activate</span>
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Raised -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h6 class="text-gray-600 dark:text-gray-400 text-sm mb-2">Total Raised</h6>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($analytics['total_raised'], 2) }}</h3>
                </div>
                <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                    <i class="fas fa-hand-holding-usd text-green-600 dark:text-green-400 text-2xl"></i>
                </div>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-2">
                <div class="bg-green-600 dark:bg-green-500 h-2 rounded-full transition-all duration-500" style="width: {{ $analytics['progress_percentage'] }}%"></div>
            </div>
            <small class="text-gray-600 dark:text-gray-400 text-xs">
                {{ $analytics['progress_percentage'] }}% of ₦{{ number_format($analytics['goal_amount'], 2) }} goal
            </small>
        </div>

        <!-- Total Donors -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 dark:text-gray-400 text-sm mb-2">Total Donors</h6>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $analytics['total_donors'] }}</h3>
                    <small class="text-gray-600 dark:text-gray-400 text-xs mt-2 block">Unique contributors</small>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                    <i class="fas fa-users text-blue-600 dark:text-blue-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Donation -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 dark:text-gray-400 text-sm mb-2">Average Donation</h6>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($analytics['average_donation'], 2) }}</h3>
                    <small class="text-gray-600 dark:text-gray-400 text-xs mt-2 block">Per donation</small>
                </div>
                <div class="bg-cyan-100 dark:bg-cyan-900/30 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-cyan-600 dark:text-cyan-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Largest Donation -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h6 class="text-gray-600 dark:text-gray-400 text-sm mb-2">Largest Donation</h6>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white">₦{{ number_format($analytics['largest_donation'], 2) }}</h3>
                    <small class="text-gray-600 dark:text-gray-400 text-xs mt-2 block">Top contribution</small>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
                    <i class="fas fa-trophy text-yellow-600 dark:text-yellow-400 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content Area (Charts & Donations) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Donation Timeline Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Donation Timeline (Last 30 Days)</h5>
                </div>
                <div class="p-6">
                    <canvas id="donationChart" class="w-full" style="max-height: 300px;"></canvas>
                </div>
            </div>

            <!-- Recent Donations -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700 p-4 flex justify-between items-center">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Donations</h5>
                    <a href="{{ route('admin.donations.campaign', $campaign->id) }}" class="px-3 py-1 text-sm border border-primary text-primary rounded hover:bg-primary hover:text-white transition-colors">
                        View All
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Donor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($analytics['recent_donations'] as $donation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-200">
                                    @if($donation->is_anonymous)
                                        <span class="text-gray-500 dark:text-gray-400">Anonymous</span>
                                    @else
                                        {{ $donation->donor_name }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                    ₦{{ number_format($donation->amount, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                    {{ $donation->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No donations yet
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Campaign Details -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Campaign Details</h5>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $campaign->featured_image) }}" 
                             alt="{{ $campaign->title }}" 
                             class="w-full rounded-lg object-cover">
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <label class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">Category</label>
                            <p class="text-gray-900 dark:text-white">{{ $campaign->campaignCategory->name }}</p>
                        </div>
                        
                        <div>
                            <label class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">Location</label>
                            <p class="text-gray-900 dark:text-white">{{ $campaign->location ?? 'Not specified' }}</p>
                        </div>
                        
                        <div>
                            <label class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">Start Date</label>
                            <p class="text-gray-900 dark:text-white">{{ $campaign->start_date->format('M d, Y') }}</p>
                        </div>
                        
                        @if($campaign->end_date)
                        <div>
                            <label class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">End Date</label>
                            <p class="text-gray-900 dark:text-white">{{ $campaign->end_date->format('M d, Y') }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <label class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide">Minimum Donation</label>
                            <p class="text-gray-900 dark:text-white">₦{{ number_format($campaign->minimum_donation, 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700">
                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                    <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Quick Actions</h5>
                </div>
                <div class="p-4">
                    <div class="space-y-2">
                        <a href="{{ route('admin.campaigns.updates.create', $campaign->id) }}" 
                           class="w-full px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-colors inline-flex items-center justify-center gap-2">
                            <i class="fas fa-bullhorn"></i>
                            <span>Add Campaign Update</span>
                        </a>
                        
                        <a href="{{ route('admin.donations.campaign', $campaign->id) }}" 
                           class="w-full px-4 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-colors inline-flex items-center justify-center gap-2">
                            <i class="fas fa-list"></i>
                            <span>View All Donations</span>
                        </a>
                        
                        <a href="{{ route('admin.campaigns.duplicate', $campaign->id) }}" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors inline-flex items-center justify-center gap-2"
                           onclick="event.preventDefault(); document.getElementById('duplicate-form').submit();">
                            <i class="fas fa-copy"></i>
                            <span>Duplicate Campaign</span>
                        </a>
                        
                        <form id="duplicate-form" action="{{ route('admin.campaigns.duplicate', $campaign->id) }}" method="POST" class="hidden">
                            @csrf
                        </form>
                        
                        <!-- Social Share -->
                        <hr class="my-4 border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Share Campaign:</p>
                        
                        <div class="grid grid-cols-2 gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('admin.campaigns.show', $campaign->slug)) }}" 
                               target="_blank" 
                               class="px-3 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors inline-flex items-center justify-center gap-2">
                                <i class="fab fa-facebook-f"></i>
                                <span>Facebook</span>
                            </a>
                            
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('admin.campaigns.show', $campaign->slug)) }}&text={{ urlencode($campaign->title) }}" 
                               target="_blank" 
                               class="px-3 py-2 bg-sky-500 text-white text-sm rounded-lg hover:bg-sky-600 transition-colors inline-flex items-center justify-center gap-2">
                                <i class="fab fa-twitter"></i>
                                <span>Twitter</span>
                            </a>
                            
                            <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . route('admin.campaigns.show', $campaign->slug)) }}" 
                               target="_blank" 
                               class="px-3 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors inline-flex items-center justify-center gap-2">
                                <i class="fab fa-whatsapp"></i>
                                <span>WhatsApp</span>
                            </a>
                            
                            <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('admin.campaigns.show', $campaign->slug)) }}" 
                               target="_blank" 
                               class="px-3 py-2 bg-blue-700 text-white text-sm rounded-lg hover:bg-blue-800 transition-colors inline-flex items-center justify-center gap-2">
                                <i class="fab fa-linkedin-in"></i>
                                <span>LinkedIn</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('donationChart');
    if (!ctx) return;
    
    const isDark = document.documentElement.classList.contains('dark');
    const textColor = isDark ? '#9ca3af' : '#4b5563';
    const gridColor = isDark ? '#374151' : '#e5e7eb';
    
    const donations = @json($analytics['donations_by_day']);
    const labels = donations.map(d => d.date).reverse();
    const amounts = donations.map(d => parseFloat(d.total)).reverse();
    const counts = donations.map(d => parseInt(d.count)).reverse();
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Amount (₦)',
                data: amounts,
                borderColor: '#10b981',
                backgroundColor: isDark ? 'rgba(16, 185, 129, 0.1)' : 'rgba(16, 185, 129, 0.2)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#10b981'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { 
                    display: true, 
                    position: 'top',
                    labels: {
                        color: textColor
                    }
                },
                tooltip: {
                    backgroundColor: isDark ? '#1f2937' : '#fff',
                    titleColor: isDark ? '#fff' : '#111827',
                    bodyColor: isDark ? '#d1d5db' : '#374151',
                    borderColor: isDark ? '#374151' : '#e5e7eb',
                    borderWidth: 1,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) label += ': ';
                            label += '₦' + context.parsed.y.toLocaleString();
                            const index = context.dataIndex;
                            label += ` (${counts[index]} donation${counts[index] !== 1 ? 's' : ''})`;
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: textColor,
                        callback: value => '₦' + value.toLocaleString()
                    },
                    grid: {
                        color: gridColor
                    }
                },
                x: {
                    ticks: {
                        color: textColor
                    },
                    grid: {
                        color: gridColor
                    }
                }
            }
        }
    });
    
    // Update chart colors when dark mode changes
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.attributeName === 'class') {
                location.reload(); // Reload to update chart colors
            }
        });
    });
    
    observer.observe(document.documentElement, {
        attributes: true
    });
});
</script>
@endpush
@endsection