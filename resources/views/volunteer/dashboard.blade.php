@extends('layouts.volunteer')

@section('content')
       <main class="flex-1 overflow-y-auto p-6">

                <!-- Welcome Banner -->
                <div class="bg-gradient-to-r from-primary to-secondary rounded-lg shadow-lg p-6 mb-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="text-white/90">Thank you for dedicating your time to help our community</p>
                        </div>
                        <div class="hidden md:block">
                            <i class="fas fa-user-friends text-6xl opacity-20"></i>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <!-- Total Hours -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Total Hours</p>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ number_format($stats['total_hours'], 1) }}</h3>
                                <p class="text-green-600 dark:text-green-400 text-sm mt-2">
                                    <i class="fas fa-arrow-up"></i> {{ number_format($stats['hours_this_month'], 1) }} this month
                                </p>
                            </div>
                            <div class="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center">
                                <i class="fas fa-clock text-2xl text-primary"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Completed -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Tasks Completed</p>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $stats['tasks_completed'] }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">{{ $stats['tasks_pending'] }} active</p>
                            </div>
                            <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-check-circle text-2xl text-green-600 dark:text-green-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Events Attended -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Events Attended</p>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ $stats['events_attended'] }}</h3>
                                <p class="text-blue-600 dark:text-blue-400 text-sm mt-2">{{ $stats['upcoming_events'] }} upcoming</p>
                            </div>
                            <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-calendar-check text-2xl text-blue-600 dark:text-blue-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Impact Score -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-500">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Impact Score</p>
                                <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-2">{{ number_format($stats['impact_score']) }}</h3>
                                <p class="text-purple-600 dark:text-purple-400 text-sm mt-2">Top {{ 100 - $percentile }}% volunteer</p>
                            </div>
                            <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                                <i class="fas fa-star text-2xl text-purple-600 dark:text-purple-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="grid lg:grid-cols-3 gap-6 mb-6">
                    
                    <!-- My Tasks -->
                   <!-- My Tasks -->
<div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-500">
    <div class="p-6 border-b dark:border-gray-700 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">My Assigned Campaigns</h3>
        <a href="{{ route('volunteer.assigned-campaigns.index') }}" class="text-primary hover:underline text-sm">
            View All →
        </a>
    </div>
    <div class="p-6 space-y-4">
        @forelse($activeTasks as $campaign)
            <div class="flex items-start gap-4 p-4 border dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <div class="flex-1">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="font-semibold text-gray-800 dark:text-gray-100">{{ $campaign->title }}</h4>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($campaign->pivot->status === 'active') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                            @elseif($campaign->pivot->status === 'assigned') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                            @endif">
                            {{ ucfirst($campaign->pivot->status) }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ Str::limit($campaign->short_description, 80) }}</p>
                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                        @if($campaign->end_date)
                            <span><i class="fas fa-calendar mr-1"></i>Due: {{ $campaign->end_date->format('M d, Y') }}</span>
                        @endif
                        @if($campaign->location)
                            <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $campaign->location }}</span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="fas fa-inbox text-4xl mb-3"></i>
                <p>No campaigns assigned yet</p>
                <a href="{{ route('volunteer.assigned-campaigns.index') }}" class="text-primary text-sm">View All →</a>
            </div>
        @endforelse
    </div>
</div>

                    <!-- Sidebar Content -->
                    <div class="space-y-6">
                        <!-- Upcoming Events -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-500">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Upcoming Events</h3>
                            <div class="space-y-3">
                                @forelse($upcomingEvents as $event)
                                    <div class="p-3 bg-primary/5 dark:bg-primary/10 border border-primary/20 rounded-lg">
                                        <div class="flex items-start gap-3">
                                            <div class="w-12 h-12 bg-primary rounded-lg flex flex-col items-center justify-center text-white flex-shrink-0">
                                                <span class="text-xs">{{ $event->start_date->format('M') }}</span>
                                                <span class="text-lg font-bold">{{ $event->start_date->format('d') }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-800 dark:text-gray-100 text-sm">{{ Str::limit($event->title, 40) }}</h4>
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ $event->start_date->format('g:i A') }}</p>
                                                @if($event->location)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        <i class="fas fa-map-marker-alt mr-1"></i>{{ $event->location }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-4 text-gray-500 dark:text-gray-400 text-sm">
                                        No upcoming events
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Hours This Month -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-500">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Hours This Month</h3>
                            <div class="text-center">
                                <div class="text-5xl font-bold text-primary mb-2">{{ number_format($stats['hours_this_month'], 1) }}</div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">hours contributed</p>
                                @if($stats['pending_hours'] > 0)
                                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                                        <p class="text-sm text-yellow-800 dark:text-yellow-300">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ number_format($stats['pending_hours'], 1) }} hours pending approval
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Recent Achievements -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 transition-colors duration-500">
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Achievements</h3>
                            <div class="space-y-3">
                                @if($stats['total_hours'] >= 100)
                                    <div class="flex items-center gap-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                                        <div class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-trophy text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 dark:text-gray-100 text-sm">Century Club</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">100+ hours contributed</p>
                                        </div>
                                    </div>
                                @endif
                                @if($stats['events_attended'] >= 10)
                                    <div class="flex items-center gap-3 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                            <i class="fas fa-star text-white"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-800 dark:text-gray-100 text-sm">Event Master</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">10+ events attended</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow transition-colors duration-500">
                    <div class="p-6 border-b dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">Recent Activity</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($recentActivity as $activity)
                                <div class="flex gap-4">
                                    <div class="flex flex-col items-center">
                                        <div class="w-10 h-10 rounded-full 
                                            @if($activity->status === 'approved') bg-green-100 dark:bg-green-900/30
                                            @elseif($activity->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30
                                            @else bg-red-100 dark:bg-red-900/30
                                            @endif flex items-center justify-center">
                                            <i class="fas fa-clock 
                                                @if($activity->status === 'approved') text-green-600 dark:text-green-400
                                                @elseif($activity->status === 'pending') text-yellow-600 dark:text-yellow-400
                                                @else text-red-600 dark:text-red-400
                                                @endif"></i>
                                        </div>
                                        @if(!$loop->last)
                                            <div class="w-px h-12 bg-gray-200 dark:bg-gray-700"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-4">
                                        <div class="flex items-center justify-between mb-1">
                                            <h4 class="font-semibold text-gray-800 dark:text-gray-100">
                                                Logged {{ number_format($activity->hours, 1) }} hours
                                            </h4>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                        @if($activity->campaign)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $activity->campaign->title }}</p>
                                        @endif
                                        @if($activity->description)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ Str::limit($activity->description, 100) }}</p>
                                        @endif
                                        <span class="inline-block mt-2 px-2 py-1 text-xs rounded-full
                                            @if($activity->status === 'approved') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                            @elseif($activity->status === 'pending') bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300
                                            @else bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                            @endif">
                                            {{ ucfirst($activity->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-history text-4xl mb-3"></i>
                                    <p>No recent activity</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </main>
@endsection