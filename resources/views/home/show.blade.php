@extends('layouts.app')

@section('title', $campaign->title . ' - HOPENEST')

@section('content')

<!-- Hero Section -->
<!-- Success Message -->
@if(session('success'))
<div class="fixed inset-0 z-50 flex items-start justify-center pt-20 px-4 pointer-events-none">
    <div class="max-w-sm w-full bg-green-600 text-white rounded-lg shadow-2xl p-6 animate-bounce-in pointer-events-auto">
        <div class="flex items-center gap-4">
            <div class="flex-shrink-0">
                <i class="fas fa-check-circle text-3xl"></i>
            </div>
            <div>
                <h3 class="text-xl font-bold">Message Sent Successfully!</h3>
                <p class="mt-1 text-green-50">
                    {{ session('success') }}
                </p>
            </div>
            <button type="button" onclick="this.parentElement.parentElement.remove()"
                    class="ml-auto text-green-200 hover:text-white">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>
</div>

<!-- Add this CSS for the animation -->
<style>
    @keyframes bounceIn {
        0% { transform: translateY(-100px); opacity: 0; }
        60% { transform: translateY(30px); }
        80% { transform: translateY(-10px); }
        100% { transform: translateY(0); opacity: 1; }
    }
    .animate-bounce-in {
        animation: bounceIn 0.8s ease-out forwards;
    }
</style>
@endif
<section class="relative bg-cover bg-center py-28 md:py-36"
         style="background-image: url('{{ $campaign->featured_image ? asset('storage/' . $campaign->featured_image) : asset('images/cause/default.jpg') }}');">
    <div class="absolute inset-0 bg-black/70"></div>
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl mx-auto text-center text-white">
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-bold mb-6 text-gray-300">
                {{ $campaign->title }}
            </h1>
            <p class="text-xl md:text-2xl opacity-90">
                <strong>Location:</strong> {{ $campaign->location ?? 'Global' }} 
                @if($campaign->end_date)
                 • <strong>Ends:</strong> {{ $campaign->end_date->format('F d, Y') }}
                @endif
            </p>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="py-16 lg:py-24 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 lg:gap-12">
                
                <!-- Left: Description, Gallery & Video -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Full Description -->
                    <article class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 lg:p-12 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">About This Campaign</h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-200 leading-relaxed">
                            {!! nl2br(e($campaign->full_description)) !!}
                        </div>
                    </article>

                    <!-- Video Section -->
                <!-- Video Section -->
@if($campaign->video_url)
<div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 lg:p-12 border border-gray-200 dark:border-gray-700">
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Campaign Video</h2>
    
    @php
        // Extract YouTube ID from various URL formats
        $videoId = null;
        $url = trim($campaign->video_url);
        
        // Debug: Uncomment to see the stored URL
        // dd($url);
        
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/embed\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/v\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        } elseif (preg_match('/youtube\.com\/shorts\/([^?]+)/', $url, $matches)) {
            $videoId = $matches[1];
        }
    @endphp
    
    @if($videoId)
        <div class="relative w-full overflow-hidden rounded-2xl" style="padding-bottom: 56.25%;">
            <iframe 
                class="absolute top-0 left-0 w-full h-full"
                src="https://www.youtube.com/embed/{{ $videoId }}" 
                title="Campaign Video"
                frameborder="0" 
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                allowfullscreen>
            </iframe>
        </div>
    @else
        <!-- Debug Display -->
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 p-6 rounded-2xl">
            <p class="text-red-600 dark:text-red-400 font-semibold mb-2">Invalid YouTube URL format</p>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                <strong>URL stored:</strong> {{ $campaign->video_url }}
            </p>
            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                Supported formats: youtube.com/watch?v=..., youtu.be/..., youtube.com/embed/..., youtube.com/shorts/...
            </p>
        </div>
    @endif
</div>
@endif

                    <!-- Gallery Section -->
                    @if($campaign->gallery_images && count($campaign->gallery_images) > 0)
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-xl p-8 border border-gray-200 dark:border-gray-700">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Campaign Gallery</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($campaign->gallery_images as $index => $image)
                                <div class="group relative aspect-square overflow-hidden rounded-2xl cursor-pointer"
                                     onclick="openLightbox({{ $index }})">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="Gallery Image {{ $index + 1 }}"
                                         class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <i class="fas fa-search-plus text-white text-3xl"></i>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right: Progress + Donate Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl p-8 lg:p-10 border border-gray-200 dark:border-gray-700 sticky top-24">
                        @php
                            $progress = round(($campaign->raised_amount / max($campaign->goal_amount, 1)) * 100);
                        @endphp

                        <!-- Title -->
                        <div class="text-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                Campaign Progress
                            </h3>
                        </div>

                        <!-- CLEAN TAILWIND PROGRESS BAR -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Progress</span>
                                <span class="text-4xl font-bold text-orange-500">{{ $progress }}%</span>
                            </div>
                            
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-5 overflow-hidden">
                                <div class="bg-gradient-to-r from-orange-500 to-red-500 h-5 rounded-full transition-all duration-1000" 
                                     style="width: {{ $progress }}%"></div>
                            </div>
                        </div>

                        <!-- Goal & Raised Info -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-6 mb-8">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Goal:</span>
                                    <span class="text-2xl font-bold text-gray-900 dark:text-white">
                                        ${{ number_format($campaign->goal_amount) }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Raised:</span>
                                    <span class="text-2xl font-bold text-orange-500">
                                        ${{ number_format($campaign->raised_amount) }}
                                    </span>
                                </div>
                                <div class="pt-4 border-t border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600 dark:text-gray-400">Remaining:</span>
                                        <span class="text-xl font-semibold text-gray-700 dark:text-gray-300">
                                            ${{ number_format($campaign->goal_amount - $campaign->raised_amount) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Donate Button -->
                        <a href="{{ route('form.donation', $campaign->slug) }}" class="block w-full">
                            <button type="button" class="w-full px-8 py-5 bg-gradient-to-r from-orange-500 to-red-500 hover:from-orange-600 hover:to-red-600 text-white font-bold text-xl rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl">
                                <i class="fa fa-heart mr-2"></i> Donate Now
                            </button>
                        </a>

                        <!-- Campaign Stats -->
                        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <div class="grid grid-cols-2 gap-4 text-center">
                                <div>
                                    <p class="text-3xl font-bold text-orange-500">{{ $campaign->donorsCount() }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Donors</p>
                                </div>
                                <div>
                                    <p class="text-3xl font-bold text-blue-500">{{ $campaign->donations_count ?? 0 }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Donations</p>
                                </div>
                            </div>
                        </div>

                        <!-- Share -->
                        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400 text-center mb-4">Share this campaign:</p>
                            <div class="flex justify-center gap-3">
                                <a href="https://facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                                   target="_blank"
                                   class="w-12 h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full flex items-center justify-center transition">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($campaign->title) }}" 
                                   target="_blank"
                                   class="w-12 h-12 bg-sky-500 hover:bg-sky-600 text-white rounded-full flex items-center justify-center transition">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($campaign->title . ' - ' . url()->current()) }}" 
                                   target="_blank"
                                   class="w-12 h-12 bg-green-500 hover:bg-green-600 text-white rounded-full flex items-center justify-center transition">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Lightbox for Gallery Images -->
@if($campaign->gallery_images && count($campaign->gallery_images) > 0)
<div id="lightbox" class="hidden fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" onclick="closeLightbox()">
    <button onclick="closeLightbox()" class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300">&times;</button>
    <button onclick="prevImage(); event.stopPropagation()" class="absolute left-4 text-white text-4xl hover:text-gray-300">‹</button>
    <button onclick="nextImage(); event.stopPropagation()" class="absolute right-4 text-white text-4xl hover:text-gray-300">›</button>
    <img id="lightbox-img" src="" alt="Gallery Image" class="max-w-full max-h-full rounded-2xl" onclick="event.stopPropagation()">
</div>

<script>
    const images = @json(array_map(fn($img) => asset('storage/' . $img), $campaign->gallery_images));
    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        document.getElementById('lightbox').classList.remove('hidden');
        document.getElementById('lightbox-img').src = images[currentIndex];
    }

    function closeLightbox() {
        document.getElementById('lightbox').classList.add('hidden');
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        document.getElementById('lightbox-img').src = images[currentIndex];
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        document.getElementById('lightbox-img').src = images[currentIndex];
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (!document.getElementById('lightbox').classList.contains('hidden')) {
            if (e.key === 'ArrowRight') nextImage();
            if (e.key === 'ArrowLeft') prevImage();
            if (e.key === 'Escape') closeLightbox();
        }
    });
</script>
@endif


@endsection