@extends('layouts.admin')

@section('title', 'View Impact Story')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $impactStory->title }}</h1>
                    <div class="flex items-center gap-3 mt-3">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full
                            {{ $impactStory->status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                            {{ $impactStory->status === 'draft' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                            {{ $impactStory->status === 'archived' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}">
                            {{ ucfirst($impactStory->status) }}
                        </span>
                        @if($impactStory->is_featured)
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Featured
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.impact-stories.edit', $impactStory) }}" 
                       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-500 dark:hover:bg-indigo-600 text-white rounded-lg transition">
                        Edit Story
                    </a>
                    <a href="{{ route('admin.impact-stories.index') }}" 
                       class="px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Analytics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Views</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($analytics['total_views']) }}</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Reading Time</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $analytics['reading_time'] }} min</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-lg">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

         <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 max-w-sm w-full">
    <div class="flex items-center justify-between">
        <div class="min-w-0 flex-1">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Days Published</p>
            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2 truncate">{{ $analytics['published_days'] }}</p>
        </div>
        <div class="flex-shrink-0 p-3 bg-purple-100 dark:bg-purple-900 rounded-lg ml-4">
            <svg class="w-6 h-6 text-purple-600 dark:text-purple-300 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
    </div>
</div>
        </div>

        <!-- Featured Image -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden mb-6">
            <img src="{{ asset('storage/' . $impactStory->featured_image) }}" 
                 alt="{{ $impactStory->title }}"
                 class="w-full h-96 object-cover">
        </div>

        <!-- Story Content -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-8 mb-6">
            <!-- Meta Information -->
            <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-6 text-sm text-gray-600 dark:text-gray-400">
                    @if($impactStory->campaign)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <a href="{{ route('admin.campaigns.show', $impactStory->campaign) }}" 
                               class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                {{ $impactStory->campaign->title }}
                            </a>
                        </div>
                    @endif
                    
                    @if($impactStory->beneficiary_name)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $impactStory->beneficiary_name }}
                        </div>
                    @endif

                    @if($impactStory->beneficiary_location)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $impactStory->beneficiary_location }}
                        </div>
                    @endif

                    @if($impactStory->impact_date)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            {{ $impactStory->impact_date->format('F d, Y') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Excerpt -->
            <div class="mb-8">
                <p class="text-xl text-gray-700 dark:text-gray-300 italic">{{ $impactStory->excerpt }}</p>
            </div>

            <!-- Full Content -->
            <div class="prose prose-lg dark:prose-invert max-w-none dark:text-gray-200">
                {!! \Illuminate\Support\Str::markdown($impactStory->content) !!}
            </div>
        </div>

        <!-- Impact Metrics -->
        @if($impactStory->metrics && count($impactStory->metrics) > 0)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Impact Metrics</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($impactStory->metrics as $metric)
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-lg p-6 border border-indigo-100 dark:border-indigo-800">
                            <p class="text-4xl font-bold text-indigo-600 dark:text-indigo-400 mb-2">{{ $metric['value'] }}</p>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $metric['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Story Actions</h2>
            <div class="flex flex-wrap gap-3">
                <form action="{{ route('admin.impact-stories.toggle-status', $impactStory) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 {{ $impactStory->status === 'published' ? 'bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:hover:bg-yellow-800 text-yellow-700 dark:text-yellow-300' : 'bg-green-100 hover:bg-green-200 dark:bg-green-900 dark:hover:bg-green-800 text-green-700 dark:text-green-300' }} font-medium rounded-lg transition">
                        {{ $impactStory->status === 'published' ? 'Unpublish' : 'Publish' }} Story
                    </button>
                </form>

                <form action="{{ route('admin.impact-stories.toggle-featured', $impactStory) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                            class="px-4 py-2 {{ $impactStory->is_featured ? 'bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300' : 'bg-yellow-100 hover:bg-yellow-200 dark:bg-yellow-900 dark:hover:bg-yellow-800 text-yellow-700 dark:text-yellow-300' }} font-medium rounded-lg transition">
                        {{ $impactStory->is_featured ? 'Remove from Featured' : 'Mark as Featured' }}
                    </button>
                </form>

                <form action="{{ route('admin.impact-stories.destroy', $impactStory) }}" 
                      method="POST" 
                      class="inline"
                      onsubmit="return confirm('Are you sure you want to delete this story?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-4 py-2 bg-red-100 hover:bg-red-200 dark:bg-red-900 dark:hover:bg-red-800 text-red-700 dark:text-red-300 font-medium rounded-lg transition">
                        Delete Story
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection