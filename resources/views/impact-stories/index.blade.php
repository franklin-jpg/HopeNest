@extends('layouts.app')

@section('title', 'Impact Stories - See The Difference You Make')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-orange-600 to-orange-600 dark:from-orange-900 dark:to-orange-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6">
                Stories of Impact
            </h1>
            <p class="text-xl md:text-2xl text-indigo-100 dark:text-indigo-200 max-w-3xl mx-auto mb-8">
                Discover how your generosity is transforming lives and creating lasting change in communities around the world.
            </p>
            
            <!-- Search Bar -->
            <form method="GET" action="{{ route('impact-stories.index') }}" class="max-w-2xl mx-auto">
                <div class="flex gap-3">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Search stories..."
                           class="flex-1 px-6 py-4 rounded-lg text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-white dark:focus:ring-indigo-400 focus:border-transparent">
                    <button type="submit" 
                            class="px-8 py-4 bg-white dark:bg-gray-800 text-orange-600 dark:text-orange-400 font-semibold rounded-lg hover:bg-indigo-50 dark:hover:bg-gray-700 transition">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        @if($featuredStories->isNotEmpty())
            <!-- Featured Stories -->
            <div class="mb-16">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Featured Stories</h2>
                    <div class="h-1 flex-1 bg-gradient-to-r from-orange-600 to-transparent dark:from-orange-500 ml-6"></div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($featuredStories as $featured)
                        <a href="{{ route('impact-stories.show', $featured->slug) }}" 
                           class="group bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                            <!-- Image -->
                            <div class="relative h-64 overflow-hidden">
                                <img src="{{ asset('storage/' . $featured->featured_image) }}" 
                                     alt="{{ $featured->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 bg-yellow-500 text-white text-xs font-bold rounded-full">
                                        Featured
                                    </span>
                                </div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <h3 class="text-xl font-bold text-white line-clamp-2">
                                        {{ $featured->title }}
                                    </h3>
                                </div>
                            </div>
                            
                            <!-- Content -->
                            <div class="p-6">
                                <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-3 mb-4">
                                    {{ $featured->excerpt }}
                                </p>
                                
                                @if($featured->campaign)
                                    <div class="flex items-center text-xs text-indigo-600 dark:text-indigo-400 mb-4">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ Str::limit($featured->campaign->title, 30) }}
                                    </div>
                                @endif

                                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $featured->reading_time }} min read
                                    </span>
                                    <span>{{ $featured->published_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-8">
            <form method="GET" action="{{ route('impact-stories.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Filter by Campaign</label>
                    <select name="campaign" 
                            onchange="this.form.submit()"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400">
                        <option value="">All Campaigns</option>
                        @foreach($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ request('campaign') == $campaign->id ? 'selected' : '' }}>
                                {{ $campaign->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if(request()->hasAny(['campaign', 'search']))
                    <div class="flex items-end">
                        <a href="{{ route('impact-stories.index') }}" 
                           class="px-6 py-2 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition">
                            Clear Filters
                        </a>
                    </div>
                @endif
            </form>
        </div>

        <!-- All Stories Grid -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
                @if(request('search'))
                    Search Results for "{{ request('search') }}"
                @elseif(request('campaign'))
                    Stories from {{ $campaigns->firstWhere('id', request('campaign'))->title ?? 'Selected Campaign' }}
                @else
                    All Impact Stories
                @endif
            </h2>

            @if($stories->isEmpty())
                <div class="text-center py-16">
                    <svg class="mx-auto h-16 w-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-4 text-xl font-medium text-gray-900 dark:text-white">No stories found</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Try adjusting your search or filters</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($stories as $story)
                        <article class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                            <!-- Image -->
                            <a href="{{ route('impact-stories.show', $story->slug) }}" class="block relative h-56 overflow-hidden group">
                                <img src="{{ asset('storage/' . $story->featured_image) }}" 
                                     alt="{{ $story->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                            </a>

                            <!-- Content -->
                            <div class="p-6">
                                @if($story->campaign)
                                    <a href="{{ route('show.single', $story->campaign->slug) }}" 
                                       class="inline-flex items-center text-xs font-semibold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 mb-3">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ Str::limit($story->campaign->title, 35) }}
                                    </a>
                                @endif

                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">
                                    <a href="{{ route('impact-stories.show', $story->slug) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                        {{ $story->title }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-3">
                                    {{ $story->excerpt }}
                                </p>

                                <!-- Meta Info -->
                                <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $story->reading_time }} min read
                                    </span>
                                    <span>{{ $story->published_at->format('M d, Y') }}</span>
                                </div>

                                <!-- Read More Button -->
                                <a href="{{ route('impact-stories.show', $story->slug) }}" 
                                   class="inline-flex items-center text-orange-600 dark:text-orange-400 font-semibold hover:text-orange-700 dark:hover:text-orange-300 transition group">
                                    Read Full Story
                                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($stories->hasPages())
                    <div class="mt-12">
                        {{ $stories->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Call to Action -->
        <div class="bg-gradient-to-r from-orange-600 to-orange-600 dark:from-orange-900 dark:to-orange-900 rounded-2xl p-12 text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                Be Part of the Next Success Story
            </h2>
            <p class="text-xl text-indigo-100 dark:text-indigo-200 mb-8 max-w-2xl mx-auto">
                Your donation can create life-changing impact. Join us in transforming lives and building stronger communities.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('all.campaigns') }}" 
                   class="px-8 py-4 bg-white text-orange-600 font-bold rounded-lg hover:bg-indigo-50 transition-all transform hover:scale-105">
                    View Active Campaigns
                </a>
                <a href="{{ route('all.campaigns') }}" 
                   class="px-8 py-4 bg-orange-700 dark:bg-orange-800 text-white font-bold rounded-lg hover:bg-orange-800 dark:hover:bg-orange-700 transition-all transform hover:scale-105">
                    Make a Donation
                </a>
            </div>
        </div>
    </div>
</div>
@endsection