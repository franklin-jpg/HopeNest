@extends('layouts.admin')

@section('title', 'Story Analytics - ' . $impactStory->title)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Story Analytics</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $impactStory->title }}</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.impact-stories.show', $impactStory) }}" 
                       class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        View Story
                    </a>
                    <a href="{{ route('admin.impact-stories.index') }}" 
                       class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Back to Stories
                    </a>
                </div>
            </div>
        </div>

        <!-- Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Views</h3>
                    <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($analytics['views']['total']) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    Avg. {{ number_format($analytics['views']['avg_per_day'], 1) }}/day
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Reading Time</h3>
                    <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $analytics['engagement']['reading_time'] }} min</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ number_format($analytics['engagement']['word_count']) }} words
                </p>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Publication Status</h3>
                    <div class="p-2 {{ $analytics['status']['is_published'] ? 'bg-green-100 dark:bg-green-900' : 'bg-gray-100 dark:bg-gray-700' }} rounded-lg">
                        <svg class="w-6 h-6 {{ $analytics['status']['is_published'] ? 'text-green-600 dark:text-green-300' : 'text-gray-600 dark:text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $analytics['status']['is_published'] ? 'Published' : 'Draft' }}
                </p>
                @if($analytics['status']['published_at'])
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                        {{ $analytics['status']['published_at']->format('M d, Y') }}
                    </p>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Days Published</h3>
                    <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $analytics['status']['days_published'] }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ $analytics['status']['is_featured'] ? 'Featured Story' : 'Regular Story' }}
                </p>
            </div>
        </div>

        <!-- Story Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Story Information -->
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Story Information</h2>
                
                <div class="space-y-4">
                    <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Title</span>
                        <span class="text-sm text-gray-900 dark:text-white font-medium">{{ $impactStory->title }}</span>
                    </div>

                    @if($impactStory->campaign)
                        <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Linked Campaign</span>
                            <a href="{{ route('admin.campaigns.show', $impactStory->campaign) }}" 
                               class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ Str::limit($impactStory->campaign->title, 40) }}
                            </a>
                        </div>
                    @endif

                    @if($impactStory->beneficiary_name)
                        <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Beneficiary</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $impactStory->beneficiary_name }}</span>
                        </div>
                    @endif

                    @if($impactStory->beneficiary_location)
                        <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Location</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $impactStory->beneficiary_location }}</span>
                        </div>
                    @endif

                    @if($impactStory->impact_date)
                        <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Impact Date</span>
                            <span class="text-sm text-gray-900 dark:text-white">{{ $impactStory->impact_date->format('F d, Y') }}</span>
                        </div>
                    @endif

                    <div class="flex justify-between py-3 border-b border-gray-200 dark:border-gray-700">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Created</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $impactStory->created_at->format('M d, Y h:i A') }}</span>
                    </div>

                    <div class="flex justify-between py-3">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Updated</span>
                        <span class="text-sm text-gray-900 dark:text-white">{{ $impactStory->updated_at->format('M d, Y h:i A') }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Quick Stats</h2>
                
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Status</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full
                                {{ $impactStory->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                {{ $impactStory->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                                {{ $impactStory->status === 'archived' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                                {{ ucfirst($impactStory->status) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Featured</span>
                            <span class="text-sm font-bold {{ $impactStory->is_featured ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-500 dark:text-gray-500' }}">
                                {{ $impactStory->is_featured ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Word Count</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ number_format($analytics['engagement']['word_count']) }}
                            </span>
                        </div>
                    </div>

                    @if($impactStory->metrics && count($impactStory->metrics) > 0)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Impact Metrics</span>
                                <span class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ count($impactStory->metrics) }}
                                </span>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-8">
                    <a href="{{ route('admin.impact-stories.edit', $impactStory) }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white font-medium rounded-lg transition">
                        Edit Story
                    </a>
                </div>
            </div>
        </div>

        <!-- Impact Metrics Display -->
        @if($impactStory->metrics && count($impactStory->metrics) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Impact Metrics</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($impactStory->metrics as $metric)
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-lg p-6 border border-indigo-100 dark:border-indigo-800">
                            <p class="text-3xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">{{ $metric['value'] }}</p>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $metric['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection