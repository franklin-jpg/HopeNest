@extends('layouts.volunteer')

@section('title', 'My Assigned Campaigns')

@section('content')
<main class="flex-1 overflow-y-auto p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">My Assigned Campaigns</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">View all campaigns you've been assigned to by administrators.</p>
    </div>

    <!-- Filter Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="border-b dark:border-gray-700">
            <div class="flex flex-wrap gap-2 p-4">
                @php $statuses = ['all' => 'All', 'assigned' => 'Assigned', 'active' => 'Active', 'completed' => 'Completed', 'removed' => 'Removed']; @endphp
                @foreach($statuses as $key => $label)
                    <a href="{{ route('volunteer.assigned-campaigns.index', ['status' => $key === 'all' ? null : $key]) }}"
                       class="px-4 py-2 rounded-lg text-sm font-medium transition
                       {{ is_null($status) && $key === 'all' || $status === $key 
                          ? 'bg-primary text-white' 
                          : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Campaigns List -->
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @forelse($campaigns as $campaign)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
                            {{ $campaign->title }}
                        </h3>
                        <span class="px-3 py-1 text-xs font-medium rounded-full
                            @if($campaign->pivot->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                            @elseif($campaign->pivot->status === 'assigned') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                            @elseif($campaign->pivot->status === 'completed') bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @elseif($campaign->pivot->status === 'removed') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300
                            @endif">
                            {{ ucfirst($campaign->pivot->status) }}
                        </span>
                    </div>

                    @if($campaign->short_description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ Str::limit($campaign->short_description, 100) }}
                        </p>
                    @endif

                    <div class="space-y-2 text-xs text-gray-500 dark:text-gray-400">
                        @if($campaign->location)
                            <div><i class="fas fa-map-marker-alt mr-2"></i>{{ $campaign->location }}</div>
                        @endif
                        <div><i class="fas fa-calendar mr-2"></i>
                            {{ $campaign->start_date->format('M d') }}
                            @if($campaign->end_date)
                                - {{ $campaign->end_date->format('M d, Y') }}
                            @endif
                        </div>
                        @if($campaign->campaignCategory)
                            <div class="inline-block px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-xs">
                                {{ $campaign->campaignCategory->name }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-4 text-xs text-gray-500 dark:text-gray-400">
                       Assigned on: {{ \Carbon\Carbon::parse($campaign->pivot->assigned_at)->format('M d, Y') }}
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <i class="fas fa-inbox text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <p class="text-gray-500 dark:text-gray-400">No campaigns assigned yet.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $campaigns->appends(request()->query())->links() }}
    </div>
</main>
@endsection