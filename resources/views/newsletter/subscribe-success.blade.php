{{-- resources/views/newsletter/subscribe-success.blade.php --}}
@extends('layouts.app')

@section('title', 'Thank You for Subscribing - HopeNest')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4">
    <div class="max-w-md mx-auto">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header Gradient -->
            <div class="bg-orange-500 px-8 py-12 text-center">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">You're In!</h1>
                <p class="text-white text-lg">Welcome to the HopeNest family</p>
            </div>

            <!-- Body -->
            <div class="px-8 py-10 text-center">
                <p class="text-gray-700 leading-relaxed mb-6">
                    Thank you for subscribing! You'll now receive updates about 
                    <strong>new campaigns</strong>, 
                    <strong>success stories</strong>, and 
                    <strong>urgent needs</strong> from HopeNest.
                </p>

                <div class="bg-orange-50 border border-orange-200 rounded-xl p-5 mb-8">
                    <p class="text-orange-800 font-medium">
                        <i class="fas fa-envelope mr-2"></i>
                        Check your inbox! We just sent you a welcome email.
                    </p>
                </div>

                <a href="{{ url('/') }}" 
                   class="inline-flex items-center bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-8 rounded-full transition shadow-lg">
                    <i class="fas fa-home mr-2"></i>
                    ← Back to Home
                </a>
            </div>

            <!-- Footer -->
            <div class="bg-gray-100 px-8 py-6 text-center text-sm text-gray-600">
                <p>You subscribed with: <strong>{{ session('subscribed_email', 'your email') }}</strong></p>
                <p class="mt-2">
                    Changed your mind? 
                    <a href="{{ route('newsletter.unsubscribe.form') }}" class="text-orange-500 hover:underline font-medium">
                        Unsubscribe here
                    </a>
                </p>
            </div>
        </div>

        <p class="text-center text-gray-500 text-sm mt-8">
            © {{ date('Y') }} HopeNest • Helping families, one heart at a time
        </p>
    </div>
</div>
@endsection