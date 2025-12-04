@extends('layouts.admin')

@section('title', 'Volunteer Hours Report')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="p-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl text-white shadow-lg">
                <i class="fas fa-clock text-2xl"></i>
            </div>
            <div class="space-y-2">
                <h1 class="text-3xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                    Hours Report
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    Track and analyze volunteer hours across all campaigns
                </p>
                <div class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold text-sm shadow-lg">
                    <i class="fas fa-chart-line mr-2"></i>
                    {{ number_format($stats['totalHours'], 1) }} total hours logged
                </div>
            </div>
        </div>
        
        <div class="flex flex-wrap gap-4 justify-end">
            <a href="{{ route('admin.volunteers-reports.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-500/20 dark:bg-gray-500/30 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl hover:bg-gray-500/30 dark:hover:bg-gray-500/50 transition-all duration-300">
                <i class="fas fa-arrow-left"></i>
                Back to Reports
            </a>
            <a href="{{ route('admin.volunteers-reports.export') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-teal-700 transition-all duration-300">
                <i class="fas fa-download"></i>
                Export Report
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group bg-gradient-to-br from-blue-50/80 to-indigo-50/80 dark:from-blue-950/20 dark:to-indigo-950/20 rounded-2xl p-8 shadow-xl border-2 border-blue-100/50 dark:border-blue-900/40 hover:shadow-2xl hover:border-blue-200/60 dark:hover:border-blue-800/60 transition-all duration-500 overflow-hidden relative">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/20 to-indigo-500/20 rounded-full -mr-16 -mt-16 blur-xl animate-pulse"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-blue-800 dark:text-blue-300 uppercase tracking-wide">Approved Hours</p>
                            <p class="text-4xl lg:text-5xl font-extrabold text-blue-700 dark:text-blue-400 bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent leading-tight">
                                {{ number_format($stats['totalHours'], 1) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-blue-700 dark:text-blue-400 font-medium">
                        <i class="fas fa-clock text-blue-500"></i>
                        <span class="bg-blue-100 dark:bg-blue-900/40 px-3 py-1 rounded-full text-xs font-semibold">Verified</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-gray-50 dark:border-gray-700/50 hover:shadow-2xl hover:border-gray-200 dark:hover:border-gray-600/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Pending Review</p>
                    <p class="text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ number_format($stats['pendingHours'], 1) }}h
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-hourglass-half text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-gray-50 dark:border-gray-700/50 hover:shadow-2xl hover:border-gray-200 dark:hover:border-gray-600/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Active Volunteers</p>
                    <p class="text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ number_format($stats['volunteersWithHours']) }}
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-gray-50 dark:border-gray-700/50 hover:shadow-2xl hover:border-gray-200 dark:hover:border-gray-600/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Avg Hours/Volunteer</p>
                    <p class="text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ $stats['volunteersWithHours'] > 0 ? number_format($stats['totalHours'] / $stats['volunteersWithHours'], 1) : 0 }}h
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-purple-500 to-violet-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-chart-bar text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50/50 to-indigo-50/50 dark:from-blue-950/10 dark:to-indigo-950/10">
            <form action="{{ route('admin.volunteers-reports.hours') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-end">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Date Range</label>
                    <input type="text" 
                           name="date_range" 
                           id="dateRange"
                           class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300" 
                           value="{{ request('date_range') }}"
                           placeholder="Select date range">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Status</label>
                    <select name="status" class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300">
                        <option value="">All Statuses</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="lg:col-span-2 flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                        <i class="fas fa-filter mr-2"></i>Filter Results
                    </button>
                    <a href="{{ route('admin.volunteers-reports.hours') }}" 
                       class="px-8 py-4 bg-gray-500/20 dark:bg-gray-500/30 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl hover:bg-gray-500/30 dark:hover:bg-gray-500/50 transition-all duration-300">
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Hours Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/20 dark:to-indigo-950/20">
            <div class="flex items-center justify-between px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-list mr-3 text-blue-600"></i>
                    Hours Log
                </h2>
                <div class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-blue-400 to-indigo-500 text-white font-semibold text-sm shadow-lg">
                    <i class="fas fa-clock mr-2"></i>
                    {{ $hours->total() }} records
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Volunteer</th>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaign</th>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($hours as $hour)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 border-b border-gray-100 dark:border-gray-700/50">
                        <td class="px-8 py-8">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('admin.volunteers.show', $hour->volunteer) }}" class="group relative">
                                    <img class="h-12 w-12 rounded-full object-cover ring-4 ring-blue-100/50 dark:ring-blue-900/30 group-hover:ring-blue-200/70 dark:group-hover:ring-blue-800/50 transition-all duration-300" 
                                         src="{{ $hour->volunteer->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($hour->volunteer->user->name) . '&size=96&color=2563eb&background=3b82f6' }}" 
                                         alt="{{ $hour->volunteer->user->name }}">
                                </a>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('admin.volunteers.show', $hour->volunteer) }}" class="block text-sm font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors duration-200 truncate">
                                        {{ $hour->volunteer->user->name }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                        {{ $hour->volunteer->user->email }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-8">
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl flex items-center justify-center text-white mr-4 flex-shrink-0">
                                    <i class="fas fa-project-diagram text-sm"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                        {{ $hour->campaign->title ?? 'General Hours' }}
                                    </div>
                                    @if($hour->campaign->location)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ $hour->campaign->location }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-8 whitespace-nowrap">
                            <div class="space-y-1">
                                <span class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $hour->date->format('M d, Y') }}
                                </span>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700/50 text-gray-600 dark:text-gray-400">
                                    {{ $hour->date->format('l') }}
                                </span>
                            </div>
                        </td>
                        <td class="px-8 py-8 whitespace-nowrap">
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 text-emerald-800 dark:text-emerald-300 shadow-sm">
                                <i class="fas fa-clock mr-2"></i>
                                {{ number_format($hour->hours, 1) }}h
                            </span>
                        </td>
                        <td class="px-8 py-8 whitespace-nowrap">
                            @if($hour->status == 'approved')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-emerald-400 to-teal-600 text-white shadow-sm">
                                    <i class="fas fa-check mr-1"></i>Approved
                                </span>
                            @elseif($hour->status == 'pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-sm">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-red-400 to-rose-500 text-white shadow-sm">
                                    <i class="fas fa-times mr-1"></i>Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-8 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.volunteers.show', $hour->volunteer) }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800/50 rounded-xl transition-all duration-200 hover:scale-110"
                                   title="View Volunteer">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                @if($hour->campaign)
                                <a href="{{ route('admin.campaigns.show', $hour->campaign) }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 hover:bg-purple-200 dark:hover:bg-purple-800/50 rounded-xl transition-all duration-200 hover:scale-110"
                                   title="View Campaign">
                                    <i class="fas fa-external-link-alt text-sm"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-clock text-4xl text-blue-500"></i>
                                </div>
                                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">No Hours Found</h3>
                                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                                    No volunteer hours match your current filters. Try adjusting the date range or status.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($hours->hasPages())
        <div class="px-8 py-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/10 dark:to-indigo-950/10 border-t border-gray-200 dark:border-gray-700">
            {{ $hours->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script>
    flatpickr("#dateRange", {
        mode: "range",
        dateFormat: "M d, Y",
        conjunction: " - ",
        defaultDate: "{{ request('date_range') }}",
        theme: 'light', // or 'dark' based on your preference
        onReady: function(selectedDates, dateStr, instance) {
            instance.calendarContainer.classList.add('!bg-white', '!text-gray-900', 'dark:!bg-gray-800', 'dark:!text-gray-100');
        }
    });

    // Auto-hide success messages
    @if(session('success'))
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) alert.remove();
        }, 5000);
    @endif
</script>
@endpush
@endsection