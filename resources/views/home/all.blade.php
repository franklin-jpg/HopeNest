@extends('layouts.app')

@section('title', 'All Campaigns - HOPENEST')

@section('content')
<!-- UPDATED: Clean Tailwind CSS Progress Bars - No JavaScript Conflicts -->
<!-- Hero Section -->
<section class="relative bg-cover bg-center py-28 md:py-36"
         style="background-image: url({{ asset('images/background/page-title.jpg') }});">
    <div class="absolute inset-0 bg-black/60"></div>
    <div class="container mx-auto px-4 relative z-10 text-center">
        <h2 class="text-4xl sm:text-5xl md:text-6xl font-bold text-white mb-8">
            All <span class="text-orange-500">Campaigns</span>
        </h2>

        <a href="{{ route('welcome') }}" 
           class="inline-flex items-center gap-3 px-8 py-4 bg-orange-500 hover:bg-orange-600 text-white font-bold text-lg rounded-full shadow-xl hover:shadow-2xl transform hover:-translate-y-1 transition-all duration-300 group">
            <svg class="w-6 h-6 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Home
        </a>
    </div>
</section>

<!-- Campaigns Grid -->
<section class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-10">
            @forelse($campaigns as $campaign)
                @php
                    $progress = round(($campaign->raised_amount / max($campaign->goal_amount, 1)) * 100);
                @endphp

                <article class="group bg-white dark:bg-gray-800 rounded-3xl overflow-hidden shadow-xl hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-4 border border-gray-200 dark:border-gray-700">
                    <!-- Image + Hover Donate Button -->
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $campaign->featured_image ? asset('storage/' . $campaign->featured_image) : asset('images/cause/default.jpg') }}"
                             alt="{{ $campaign->title }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end justify-center pb-10">
                            <a href="{{ route('form.donation', $campaign->slug) }}">
                                <button type="button" 
                                        class="btn btn-primary bg-orange-400 hover:bg-orange-500 px-8 py-4 rounded-full font-bold text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all">
                                    <i class="fa fa-heart mr-2"></i> Donate Now
                                </button>
                            </a>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-7 lg:p-9">
                        <a href="{{ route('show.single', $campaign->slug) }}" class="block mb-4">
                            <h3 class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white group-hover:text-orange-500 transition-colors line-clamp-2 leading-tight" style="word-wrap: break-word; word-break: break-word;">
                                {{ $campaign->title }}
                            </h3>
                        </a>

                        <p class="text-gray-600 dark:text-gray-300 text-base mb-10 line-clamp-3 leading-relaxed" style="word-wrap: break-word; word-break: break-word; white-space: normal;">
                            {{ $campaign->short_description }}
                        </p>

                        <!-- CLEAN TAILWIND PROGRESS BAR -->
                        <div class="mb-10">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase">Progress</span>
                                <span class="text-3xl font-bold text-orange-500">{{ $progress }}%</span>
                            </div>
                            
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 overflow-hidden">
                                <div class="bg-gradient-to-r from-orange-500 to-red-500 h-4 rounded-full transition-all duration-1000" 
                                     style="width: {{ $progress }}%"></div>
                            </div>

                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Goal: <span class="font-bold text-gray-900 dark:text-white">${{ number_format($campaign->goal_amount) }}</span>
                                </p>
                                <p class="text-sm text-orange-500 font-bold mt-1">
                                    Raised: ${{ number_format($campaign->raised_amount) }}
                                </p>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-6 text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Raised</p>
                                <p class="text-2xl lg:text-3xl font-bold text-orange-500 mt-1">
                                    ${{ number_format($campaign->raised_amount) }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">Goal</p>
                                <p class="text-2xl lg:text-3xl font-bold text-gray-900 dark:text-white mt-1">
                                    ${{ number_format($campaign->goal_amount) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-32">
                    <div class="inline-block p-16 bg-gray-100 dark:bg-gray-800 rounded-3xl shadow-2xl">
                        <div class="w-32 h-32 mx-auto mb-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">No Active Campaigns</h3>
                        <p class="text-xl text-gray-500 dark:text-gray-400">New causes will be added soon. Stay tuned!</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($campaigns->hasPages())
            <div class="mt-20 flex justify-center">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl px-8 py-6 border border-gray-200 dark:border-gray-700">
                    {{ $campaigns->links() }}
                </div>
            </div>
        @endif

        <!-- Bottom "Back to Home" Button -->
        <div class="mt-20 text-center">
            <a href="{{ route('welcome') }}" 
               class="inline-flex items-center gap-4 px-10 py-5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold text-xl rounded-2xl shadow-2xl hover:shadow-3xl transform hover:scale-105 transition-all duration-400 group">
                <svg class="w-8 h-8 transition-transform group-hover:-translate-x-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Back to Home Page</span>
            </a>
        </div>
    </div>
</section>

@endsection