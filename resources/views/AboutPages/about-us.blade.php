{{-- resources/views/pages/about-us.blade.php --}}
@extends('layouts.app')
@section('title', 'About Us - Making a Difference Together')
@section('content')

<!-- Hero Section -->
<div class="bg-gradient-to-br from-orange-600 via-orange-500 to-amber-600 dark:from-orange-900 dark:via-orange-800 dark:to-amber-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017a2 2 0 01-1.789-1.106l-3.5-7a2 2 0 011.789-2.894H12m-4 0V6a2 2 0 012-2h4a2 2 0 012 2v4m-8 0h8"/>
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                About Us
            </h1>
            <p class="text-xl md:text-2xl text-orange-100 dark:text-orange-200 max-w-4xl mx-auto leading-relaxed">
                We connect generous hearts with meaningful causes — creating real, lasting impact in communities around the world.
            </p>
        </div>
    </div>
</div>

<!-- Mission & Vision -->
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-6">Our Mission</h2>
                <p class="text-lg text-gray-600 dark:text-gray-300 leading-relaxed mb-6">
                    To empower individuals and communities by providing transparent, efficient, and trustworthy platforms for fundraising, volunteering, and social impact initiatives.
                </p>
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                    We believe everyone deserves the opportunity to make a difference — whether through donating, volunteering time and skills, or starting their own campaign for change.
                </p>
            </div>
            <div class="bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/30 dark:to-amber-900/30 rounded-2xl p-10 shadow-xl">
                <h3 class="text-3xl font-bold text-orange-600 dark:text-orange-400 mb-6">Our Vision</h3>
                <p class="text-xl text-gray-800 dark:text-gray-200 leading-relaxed">
                    A world where every act of kindness finds its purpose, every need finds support, and every community thrives through collective compassion.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Cards -->
<section class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white">Our Impact So Far</h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mt-4">Together, we've achieved incredible things</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($stats['total_campaigns']) }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Total Campaigns</p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($stats['active_campaigns']) }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Active Now</p>
            </div>
            {{-- <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">${{ number_format($stats['total_raised']) }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Raised</p>
            </div> --}}
            {{-- <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($stats['total_donors']) }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Donors</p>
            </div> --}}
            <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($stats['impact_stories']) }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Impact Stories</p>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-orange-600 dark:text-orange-400">{{ number_format($stats['volunteers']) }}</div>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Volunteers</p>
            </div>
        </div>
    </div>
</section>

<!-- Our Journey / Milestones -->
<section class="py-20 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white">Our Journey</h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mt-4">Key milestones that define who we are</p>
        </div>

        <div class="relative">
            <!-- Timeline Line -->
            <div class="absolute left-1/2 transform -translate-x-1/2 w-1 bg-orange-200 dark:bg-orange-900 h-full"></div>

            @foreach($milestones as $index => $milestone)
                @php
                    // Force 2025 to be the first (newest) item in the loop
                    $displayYear = $milestone['year'] === '2025' ? '2025' : $milestone['year'];
                    $isEven = $index % 2 === 0;
                @endphp

                <div class="relative flex items-center mb-16 {{ $isEven ? 'justify-start' : 'justify-end' }} md:w-1/2 {{ $isEven ? 'md:pr-10' : 'md:pl-10 md:left-1/2' }}">
                    <!-- Milestone Card -->
                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 w-full max-w-md border border-gray-200 dark:border-gray-700 
                        hover:shadow-2xl transition-all duration-300 
                        {{ $milestone['year'] === '2025' ? 'ring-4 ring-orange-500/20 bg-orange-50/50 dark:bg-orange-900/20' : '' }}">
                        
                        <!-- Optional "NEW" badge for 2025 -->
                        @if($milestone['year'] === '2025')
                            <span class="absolute -top-3 -right-3 bg-orange-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg animate-pulse">
                                NEW
                            </span>
                        @endif

                        <div class="flex items-center mb-4">
                            <span class="text-4xl font-bold text-orange-600 dark:text-orange-400">
                                {{ $milestone['year'] }}
                            </span>
                            <div class="ml-5">
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $milestone['title'] }}
                                </h3>
                            </div>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                            {{ $milestone['description'] }}
                        </p>
                    </div>

                    <!-- Timeline Dot -->
                    <div class="absolute left-1/2 transform -translate-x-1/2 w-8 h-8 
                        {{ $milestone['year'] === '2025' ? 'bg-orange-600 ring-4 ring-orange-600/30 animate-ping' : 'bg-orange-600' }} 
                        rounded-full border-4 border-white dark:border-gray-900 shadow-xl z-10">
                    </div>
                    <!-- Static dot behind the pulsing one (only for 2025) -->
                    @if($milestone['year'] === '2025')
                        <div class="absolute left-1/2 transform -translate-x-1/2 w-8 h-8 bg-orange-600 rounded-full border-4 border-white dark:border-gray-900 shadow-xl"></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>


<!-- Meet Our Team -->
<section class="py-20 bg-gray-50 dark:bg-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white">Meet Our Team</h2>
            <p class="text-lg text-gray-600 dark:text-gray-400 mt-4">Passionate people driving change every day</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($teamMembers as $member)
                <div class="group bg-white dark:bg-gray-900 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <!-- Team Member Photo -->
                    <div class="relative h-64 bg-gray-100 dark:bg-gray-800 overflow-hidden">
                        @if(isset($member['image']) && $member['image'])
                            <img src="{{ asset('assets/teamImage/person-03-135x135.jpg' . $member['image']) }}"
                                 alt="{{ $member['name'] }}"
                                 class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-110">
                        @else
                            <!-- Fallback avatar -->
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-amber-100 dark:from-orange-900/50 dark:to-amber-900/50">
                                <div class="text-6xl font-bold text-orange-600 dark:text-orange-400">
                                    {{ substr($member['name'], 0, 1) }}
                                </div>
                            </div>
                        @endif

                        <!-- Optional overlay on hover -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    </div>

                    <!-- Member Info -->
                    <div class="p-6 text-center">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $member['name'] }}</h3>
                        <p class="text-orange-600 dark:text-orange-400 font-medium mt-1">{{ $member['position'] }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-3 leading-relaxed">{{ $member['bio'] }}</p>

                        <!-- Social Links -->
                        @if(isset($member['social']) && count($member['social']) > 0)
                            <div class="flex justify-center space-x-4 mt-5">
                                @if(isset($member['social']['twitter']))
                                    <a href="{{ $member['social']['twitter'] }}" target="_blank" rel="noopener"
                                       class="text-gray-500 hover:text-orange-600 dark:hover:text-orange-400 transition transform hover:scale-125">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.9 3.9c-1.7 1-3.6 1.7-5.6 2-.5-2.1-2-3.6-4-3.6C6.8 2.3 5 4.1 5 6.5c0 .8.2 1.5.5 2.2C3.4 8.5 1.8 7.5 1 5.9c-.9 1.5-.5 3.5 1 4.5-.8 0-1.5-.2-2.1-.6 0 2 1.4 3.7 3.3 4.1-.4.1-.8.2-1.2.2-.3 0-.6 0-.9-.1.6 2 2.4 3.4 4.5 3.4-1.7 1.3-3.8 2.1-6 2.1-.4 0-.8 0-1.2-.1 2.2 1.4 4.8 2.2 7.6 2.2 9.1 0 14.1-7.5 14.1-14 0-.2 0-.4 0-.6 1-1.7 1.8-3.1 2.5-4.1z"/>
                                        </svg>
                                    </a>
                                @endif
                                @if(isset($member['social']['linkedin']))
                                    <a href="{{ $member['social']['linkedin'] }}" target="_blank" rel="noopener"
                                       class="text-gray-500 hover:text-orange-600 dark:hover:text-orange-400 transition transform hover:scale-125">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14zm-9.5 6.5c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm-1 4h2v7h-2v-7zm4 0h2v7h-2v-7zm0-3h2v2.5h-2V10.5z"/>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Final CTA -->
<section class="py-20 bg-gradient-to-br from-orange-600 to-amber-600 dark:from-orange-900 dark:to-amber-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Be Part of the Change</h2>
        <p class="text-xl text-orange-100 dark:text-orange-200 mb-10 max-w-2xl mx-auto">
            Whether you donate, volunteer, or start a campaign — together, we can create a better tomorrow.
        </p>
        <div class="flex flex-col sm:flex-row gap-6 justify-center">
            <a href="{{ route('all.campaigns') }}" class="px-10 py-4 bg-white text-orange-600 font-bold rounded-lg hover:bg-orange-50 transition transform hover:scale-105 shadow-xl">
                Explore Campaigns
            </a>
            <a href="{{ route('register') }}" class="px-10 py-4 bg-orange-700 hover:bg-orange-800 text-white font-bold rounded-lg transition transform hover:scale-105 shadow-xl">
                Get in Touch
            </a>
        </div>
    </div>
</section>

@endsection