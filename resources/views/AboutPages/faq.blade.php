@extends('layouts.app')

@section('title', 'Frequently Asked Questions (FAQ)')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-orange-600 via-orange-500 to-amber-600 dark:from-orange-900 dark:via-orange-800 dark:to-amber-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold text-white mb-6">
                Frequently Asked Questions
            </h1>
            <p class="text-xl md:text-2xl text-orange-100 dark:text-orange-200 max-w-3xl mx-auto mb-8">
                Find answers to common questions about donations, campaigns, volunteering, and more.
            </p>
            
            <!-- Search Box -->
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" 
                           id="faq-search"
                           placeholder="Search for answers..."
                           class="w-full px-6 py-4 pl-14 rounded-lg text-gray-900 dark:text-white bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 focus:border-transparent shadow-xl">
                    <svg class="absolute left-5 top-1/2 transform -translate-y-1/2 w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Links -->
<div class="bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex overflow-x-auto py-4 gap-4 scrollbar-hide">
            @foreach($faqs as $key => $category)
                <a href="#{{ $key }}" 
                   class="whitespace-nowrap px-6 py-2 bg-orange-50 hover:bg-orange-100 dark:bg-orange-900/20 dark:hover:bg-orange-900/30 text-orange-600 dark:text-orange-400 font-semibold rounded-full transition smooth-scroll">
                    {{ $category['title'] }}
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- FAQ Content -->
<div class="bg-gray-50 dark:bg-gray-900 py-16">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @foreach($faqs as $key => $category)
            <div id="{{ $key }}" class="mb-16 scroll-mt-32">
                <!-- Category Header -->
                <div class="flex items-center mb-8">
                    <div class="flex-1">
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $category['title'] }}</h2>
                    </div>
                    <div class="h-1 flex-1 bg-gradient-to-r from-orange-600 to-transparent dark:from-orange-500 ml-6"></div>
                </div>

                <!-- Questions -->
                <div class="space-y-4">
                    @foreach($category['questions'] as $index => $faq)
                        <div class="faq-item bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-md transition-shadow">
                            <button type="button"
                                    class="faq-question w-full px-6 py-5 text-left flex items-center justify-between group"
                                    onclick="toggleFaq(this)">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white pr-8 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition">
                                    {{ $faq['question'] }}
                                </span>
                                <svg class="faq-icon w-6 h-6 text-orange-600 dark:text-orange-400 transform transition-transform flex-shrink-0" 
                                     fill="none" 
                                     stroke="currentColor" 
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="faq-answer hidden px-6 pb-5">
                                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                                    <p class="text-gray-600 dark:text-gray-400 leading-relaxed">
                                        {{ $faq['answer'] }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Still Have Questions Section -->
<section class="py-20 bg-gradient-to-br from-orange-600 to-amber-600 dark:from-orange-900 dark:to-amber-900">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full mb-6">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <h2 class="text-4xl font-bold text-white mb-4">Still Have Questions?</h2>
        <p class="text-xl text-orange-100 dark:text-orange-200 mb-8">
            Can't find the answer you're looking for? Our support team is here to help.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="" 
               class="inline-flex items-center px-8 py-4 bg-white hover:bg-orange-50 text-orange-600 font-bold rounded-lg transition-all transform hover:scale-105 shadow-xl">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Contact Support
            </a>
            <a href="mailto:support@yoursite.com" 
               class="inline-flex items-center px-8 py-4 bg-orange-700 hover:bg-orange-800 dark:bg-orange-800 dark:hover:bg-orange-700 text-white font-bold rounded-lg transition-all transform hover:scale-105 shadow-xl">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Live Chat
            </a>
        </div>

        <!-- Contact Info -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                <svg class="w-8 h-8 text-white mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="text-white font-semibold mb-1">Email Us</h3>
                <p class="text-orange-100 dark:text-orange-200 text-sm">support@yoursite.com</p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                <svg class="w-8 h-8 text-white mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <h3 class="text-white font-semibold mb-1">Call Us</h3>
                <p class="text-orange-100 dark:text-orange-200 text-sm">+1 (555) 123-4567</p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 border border-white/20">
                <svg class="w-8 h-8 text-white mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h3 class="text-white font-semibold mb-1">Office Hours</h3>
                <p class="text-orange-100 dark:text-orange-200 text-sm">Mon-Fri: 9AM - 6PM</p>
            </div>
        </div>
    </div>
</section>

<!-- Popular Resources -->
<section class="py-16 bg-white dark:bg-gray-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Helpful Resources</h2>
            <p class="text-gray-600 dark:text-gray-400">Explore more ways to get involved and stay informed</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <a href="{{ route('all.campaigns') }}" 
               class="group bg-gray-50 dark:bg-gray-800 rounded-xl p-8 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition">
                    Browse Campaigns
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Discover active campaigns and choose a cause to support
                </p>
            </a>

            <a href="{{ route('impact-stories.index') }}" 
               class="group bg-gray-50 dark:bg-gray-800 rounded-xl p-8 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition">
                    Impact Stories
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Read inspiring stories of how donations create real change
                </p>
            </a>

            <a href="{{ route('about-us') }}" 
               class="group bg-gray-50 dark:bg-gray-800 rounded-xl p-8 hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition">
                    About Us
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Learn more about our mission, vision, and impact
                </p>
            </a>
        </div>
    </div>
</section>

@push('scripts')
<script>
// FAQ Toggle Function
function toggleFaq(button) {
    const faqItem = button.closest('.faq-item');
    const answer = faqItem.querySelector('.faq-answer');
    const icon = button.querySelector('.faq-icon');
    
    // Close all other FAQs
    document.querySelectorAll('.faq-item').forEach(item => {
        if (item !== faqItem) {
            item.querySelector('.faq-answer').classList.add('hidden');
            item.querySelector('.faq-icon').classList.remove('rotate-180');
        }
    });
    
    // Toggle current FAQ
    answer.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Search Functionality
document.getElementById('faq-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question span').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
            item.style.display = 'block';
            
            // Highlight search term if found
            if (searchTerm.length > 2) {
                item.classList.add('ring-2', 'ring-orange-500');
            } else {
                item.classList.remove('ring-2', 'ring-orange-500');
            }
        } else {
            item.style.display = 'none';
        }
    });
});

// Smooth scroll for category links
document.querySelectorAll('.smooth-scroll').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);
        
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});
</script>
@endpush
@endsection