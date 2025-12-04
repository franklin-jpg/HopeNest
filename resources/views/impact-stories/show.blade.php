@extends('layouts.app')

@section('title', $story->title . ' - Impact Story')

@push('meta')
<meta name="description" content="{{ $story->excerpt }}">
<meta property="og:title" content="{{ $story->title }}">
<meta property="og:description" content="{{ $story->excerpt }}">
<meta property="og:image" content="{{ asset('storage/' . $story->featured_image) }}">
<meta property="og:type" content="article">
<meta name="twitter:card" content="summary_large_image">
@endpush

@section('content')
<div class="bg-gray-50 dark:bg-gray-900 min-h-screen">
    <!-- Hero Section with Image -->
    <div class="relative h-[60vh] md:h-[70vh] overflow-hidden">
        <img src="{{ asset('storage/' . $story->featured_image) }}" 
             alt="{{ $story->title }}"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>
        
        <!-- Breadcrumb & Back Button -->
        <div class="absolute top-8 left-0 right-0">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <a href="{{ route('impact-stories.index') }}" 
                   class="inline-flex items-center text-white hover:text-indigo-200 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Stories
                </a>
            </div>
        </div>
        
        <!-- Title & Meta -->
        <div class="absolute bottom-0 left-0 right-0 pb-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($story->campaign)
                    <a href="{{ route('show.single', $story->campaign->slug) }}" 
                       class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-full mb-4 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        {{ $story->campaign->title }}
                    </a>
                @endif
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 leading-tight">
                    {{ $story->title }}
                </h1>
                
                <div class="flex flex-wrap items-center gap-6 text-white/90">
                    @if($story->beneficiary_name)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            {{ $story->beneficiary_name }}
                        </div>
                    @endif
                    
                    @if($story->beneficiary_location)
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                            {{ $story->beneficiary_location }}
                        </div>
                    @endif
                    
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $story->published_at->format('F d, Y') }}
                    </div>
                    
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $story->reading_time }} min read
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Excerpt -->
        <div class="bg-orange-50 dark:bg-indigo-900/20 border-l-4 border-orange-600 dark:border-orange-400 p-6 mb-12 rounded-r-lg">
            <p class="text-xl text-gray-800 dark:text-gray-200 italic leading-relaxed">
                {{ $story->excerpt }}
            </p>
        </div>

        <!-- Story Content -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 md:p-12 mb-12">
            <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:text-gray-900 dark:prose-headings:text-white prose-p:text-gray-700 dark:prose-p:text-gray-300 prose-a:text-indigo-600 dark:prose-a:text-indigo-400 prose-strong:text-gray-900 dark:prose-strong:text-white">
                {!! \Illuminate\Support\Str::markdown($story->content) !!}
            </div>
        </div>

        <!-- Impact Metrics -->
        @if($story->metrics && count($story->metrics) > 0)
            <div class="bg-gradient-to-br from-orange-600 to-orange-600 dark:from-orange-900 dark:to-orange-900 rounded-xl shadow-lg p-8 md:p-12 mb-12">
                <h2 class="text-3xl font-bold text-white text-center mb-8">Impact by Numbers</h2>
                <div class="grid grid-cols-1 md:grid-cols-{{ min(count($story->metrics), 3) }} gap-6">
                    @foreach($story->metrics as $metric)
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 text-center border border-white/20">
                            <p class="text-5xl font-bold text-white mb-3">{{ $metric['value'] }}</p>
                            <p class="text-lg text-white/90 font-medium">{{ $metric['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Campaign CTA -->
        @if($story->campaign)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 mb-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            Support This Cause
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400">
                            Help us create more success stories like this one by contributing to the <strong>{{ $story->campaign->title }}</strong> campaign.
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('show.single', $story->campaign->slug) }}" 
                           class="px-6 py-3 bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-white font-semibold rounded-lg transition">
                            View Campaign
                        </a>
                        <a href="{{ route('form.donation', $story->campaign->slug) }}" 
                           class="px-6 py-3 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white font-semibold rounded-lg transition">
                            Donate Now
                        </a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Share Section -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 mb-12">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 text-center">
                Share This Story
            </h3>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('impact-stories.show', $story->slug)) }}" 
                   target="_blank"
                   class="flex items-center px-6 py-3 bg-[#1877F2] hover:bg-[#166FE5] text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('impact-stories.show', $story->slug)) }}&text={{ urlencode($story->title) }}" 
                   target="_blank"
                   class="flex items-center px-6 py-3 bg-[#1DA1F2] hover:bg-[#1A8CD8] text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                    Twitter
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(route('impact-stories.show', $story->slug)) }}" 
                   target="_blank"
                   class="flex items-center px-6 py-3 bg-[#0A66C2] hover:bg-[#095196] text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                    </svg>
                    LinkedIn
                </a>
                <button onclick="copyToClipboard()" 
                        class="flex items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-semibold rounded-lg transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                    </svg>
                    Copy Link
                </button>
            </div>
        </div>

        <!-- Related Stories -->
        @if($relatedStories->isNotEmpty())
            <div class="mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">More Impact Stories</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($relatedStories as $related)
                        <a href="{{ route('impact-stories.show', $related->slug) }}" 
                           class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-all">
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ asset('storage/' . $related->featured_image) }}" 
                                     alt="{{ $related->title }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition">
                                    {{ $related->title }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $related->excerpt }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500 mt-3">
                                    {{ $related->published_at->format('M d, Y') }}
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Back to Stories Button -->
        <div class="text-center">
            <a href="{{ route('impact-stories.index') }}" 
               class="inline-flex items-center px-8 py-4 bg-orange-600 hover:bg-orange-700 dark:bg-orange-500 dark:hover:bg-orange-600 text-white font-bold rounded-lg transition-all transform hover:scale-105">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                View All Impact Stories
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyToClipboard() {
    const url = window.location.href;
    navigator.clipboard.writeText(url).then(() => {
        alert('Link copied to clipboard!');
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
@endpush
@endsection