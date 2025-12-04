@extends('layouts.admin')

@section('title', 'Volunteer Reports')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-200">Volunteer Reports</h1>
            <p class="mt-1 text-sm text-gray-500">Comprehensive overview of volunteer activity and engagement</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 mt-4 sm:mt-0">
            <a href="{{ route('admin.volunteers-reports.hours') }}" 
               class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300">
                <i class="fas fa-clock mr-2"></i> Hours Report
            </a>
            <a href="{{ route('admin.volunteers-reports.export') }}" 
               class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-green-600 hover:to-green-700 transition-all duration-300">
                <i class="fas fa-download mr-2"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl p-6 shadow-lg border border-orange-50 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Volunteers</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $stats['totalVolunteers'] }}</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl text-white">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-yellow-50 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending Applications</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $stats['pendingVolunteers'] }}</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl text-white">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-green-50 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Hours</p>
                    <p class="text-3xl font-bold text-green-600">{{ number_format($stats['totalHours'], 1) }}</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-xl text-white">
                    <i class="fas fa-stopwatch text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-lg border border-blue-50 hover:shadow-xl transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Campaigns</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $stats['activeCampaigns'] }}</p>
                </div>
                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white">
                    <i class="fas fa-flag text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Top Volunteers -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-trophy mr-3"></i> Top 10 Volunteers
                    </h3>
                </div>
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Volunteer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hours</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaigns</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($topVolunteers as $index => $volunteer)
                                <tr class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full object-cover" src="{{ $volunteer->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($volunteer->user->name) . '&color=ff9500&background=f97316' }}" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $volunteer->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $volunteer->user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                                            {{ number_format($volunteer->approved_hours, 1) }} hrs
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $volunteer->campaigns()->count() }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-chart-line text-4xl mb-4 opacity-50"></i>
                                        <p>No volunteer hours recorded yet</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Hours -->
        <div>
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white flex items-center">
                        <i class="fas fa-history mr-3"></i> Recent Hours
                    </h3>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @forelse($recentHours as $hour)
                    <div class="flex items-center justify-between py-4 border-b border-gray-100 last:border-b-0 hover:bg-orange-50 rounded-xl p-4 transition-all duration-200">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-stopwatch text-white text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <p class="font-medium text-gray-900">{{ $hour->volunteer->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $hour->description }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-orange-600 text-lg">{{ number_format($hour->hours, 1) }}h</p>
                            <p class="text-xs text-gray-500">{{ $hour->date->format('M d') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12 text-gray-500">
                        <i class="fas fa-clock text-4xl mb-4 opacity-50"></i>
                        <p class="text-sm">No recent hours logged</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Volunteers Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-900">All Volunteers</h3>
                <div class="flex items-center gap-3 mt-4 sm:mt-0">
                    <select class="px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        <option {{ $filter === 'all' ? 'selected' : '' }}>All Volunteers</option>
                        <option {{ $filter === 'active' ? 'selected' : '' }}>Active Only</option>
                        <option {{ $filter === 'pending' ? 'selected' : '' }}>Pending Applications</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Hours</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campaigns</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($volunteers as $volunteer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" src="{{ $volunteer->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($volunteer->user->name) . '&color=ff9500&background=f97316' }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $volunteer->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $volunteer->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($volunteer->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-medium">Pending</span>
                            @elseif($volunteer->status === 'approved')
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">Approved</span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-medium">Rejected</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $volunteer->hours()->where('status', 'approved')->sum('hours') }} hrs
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $volunteer->campaigns()->count() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $volunteer->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50">
            {{ $volunteers->links() }}
        </div>
    </div>
</div>
@endsection