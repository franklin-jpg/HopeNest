@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900">
    <div class="container-fluid px-4 py-8 max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl shadow-lg -mr-4">
                        <i class="fas fa-hand-holding-heart text-3xl text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                            Top Donors
                        </h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            View and manage your most generous supporters
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Users
                </a>
                <a href="{{ route('admin.users.export') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-download mr-2"></i>
                    Export Donors
                </a>
            </div>
        </div>

        <!-- Donor Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Total Donors -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total Donors</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($donorStats['total_donors']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-hand-holding-heart text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Top Donor Amount -->
            <div class="group relative bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl shadow-sm hover:shadow-lg border border-emerald-200/50 dark:border-emerald-800/50 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">Top Donor</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            ₦{{ number_format($donorStats['top_donor_amount'], 0) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-trophy text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Average Donation -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Avg Donation</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            ₦{{ number_format($donorStats['avg_donation_amount'] ?? 0, 0) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="group relative bg-gradient-to-br from-cyan-50 to-blue-100 dark:from-cyan-900/20 dark:to-blue-900/20 rounded-2xl shadow-sm hover:shadow-lg border border-cyan-200/50 dark:border-cyan-800/50 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-cyan-700 dark:text-cyan-400 uppercase tracking-wide">Total Raised</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            ₦{{ number_format(\App\Models\Donation::where('status', 'successful')->sum('total_amount'), 0) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-dollar-sign text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('admin.users.index') }}" 
                   class="group inline-flex items-center px-6 py-4 text-sm font-medium border-b-2 transition-all duration-300 {{ request()->routeIs('admin.users.index') ? 'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-users mr-2"></i>
                    All Users
                </a>
                <a href="{{ route('admin.users.donors') }}" 
                   class="group inline-flex items-center px-6 py-4 text-sm font-medium border-b-2 transition-all duration-300 {{ request()->routeIs('admin.users.donors') ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-hand-holding-heart mr-2"></i>
                    Donors
                </a>
                <a href="{{ route('admin.users.subscribers') }}" 
                   class="group inline-flex items-center px-6 py-4 text-sm font-medium border-b-2 transition-all duration-300 {{ request()->routeIs('admin.users.subscribers') ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-envelope mr-2"></i>
                    Subscribers
                </a>
            </nav>
        </div>

        <!-- Donors Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-trophy mr-3 text-emerald-600 dark:text-emerald-400"></i>
                    Top Donors Ranking
                </h3>
            </div>
            
            <div class="p-6">
                <!-- Advanced Filters -->
                <form method="GET" class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Minimum Amount</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-naira-sign text-gray-400"></i>
                                </div>
                                <input type="number" name="min_amount" step="1000" min="0"
                                       class="w-full pl-10 pr-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-900 dark:text-gray-100" 
                                       placeholder="₦0" 
                                       value="{{ request('min_amount') }}">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Time Period</label>
                            <select name="period" 
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 text-gray-900 dark:text-gray-100">
                                <option value="">All Time</option>
                                <option value="last_30_days" {{ request('period') == 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>This Year</option>
                            </select>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Search Donors</label>
                            <input type="text" name="search" 
                                   class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" 
                                   placeholder="Search by name or email..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-300">
                                <i class="fas fa-search mr-2"></i>Filter Donors
                            </button>
                        </div>
                    </div>
                    
                    <!-- Clear Filters Button -->
                    @if(request()->filled(['min_amount', 'period', 'search']))
                    <div class="mt-6">
                        <a href="{{ route('admin.users.donors') }}" 
                           class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>
                            Clear All Filters
                        </a>
                    </div>
                    @endif
                </form>

                <!-- Donors Table -->
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <span class="flex items-center">
                                        <i class="fas fa-crown mr-2 text-emerald-500"></i>Rank
                                    </span>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Donor</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Total Donated</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Donations</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Avg Donation</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Last Donation</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($donors as $donor)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center justify-center">
                                        @if($loop->first)
                                            <div class="relative">
                                                <i class="fas fa-crown text-2xl text-yellow-500"></i>
                                                <span class="absolute -top-1 -right-1 bg-yellow-400 text-black text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">1</span>
                                            </div>
                                        @elseif($loop->iteration <= 3)
                                            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $loop->iteration }}</div>
                                        @else
                                            <div class="text-lg font-semibold text-gray-600 dark:text-gray-400">#{{ $loop->iteration }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                            {{ strtoupper(substr($donor->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4 flex-1 min-w-0">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $donor->name }}</div>
                                            <div class="flex items-center mt-1">
                                                <i class="fas fa-calendar-check text-emerald-500 text-xs mr-1"></i>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">Since {{ $donor->created_at->format('M Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-50 dark:bg-gray-800 px-3 py-1 rounded-xl truncate max-w-xs">
                                        {{ $donor->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="inline-flex flex-col items-end">
                                        <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">
                                            ₦{{ number_format($donor->donation_sum_total_amount ?? 0, 0) }}
                                        </div>
                                        <div class="w-48 bg-gradient-to-r from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-full h-2 mt-2">
                                            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 h-2 rounded-full transition-all duration-500" 
                                                 style="width: {{ $donorStats['top_donor_amount'] > 0 ? min(($donor->donation_sum_total_amount ?? 0) / $donorStats['top_donor_amount'] * 100, 100) : 0 }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center justify-end">
                                        <i class="fas fa-donate text-emerald-500 mr-2"></i>
                                        {{ $donor->donation_count ?? 0 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold text-emerald-600 dark:text-emerald-400">
                                    ₦{{ number_format($donor->donation_avg_total_amount ?? 0, 0) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                    {{ $donor->donation()->where('status', 'successful')->latest()->first()?->created_at?->format('M d, Y') ?? 'Never' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.users.show', $donor) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-200" 
                                       title="View Profile">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-8 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl shadow-lg">
                                            <i class="fas fa-hand-holding-heart text-8xl text-emerald-500 mb-6"></i>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-4">No donors found</p>
                                        <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-2xl mx-auto">
                                            @if(request('min_amount') || request('period'))
                                                Try adjusting your filters to see more donors
                                            @else
                                                No donors have made successful contributions yet
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($donors->hasPages())
                <div class="px-6 py-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-center">
                        {{ $donors->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection