{{-- resources/views/newsletter/unsubscribe.blade.php --}}
@extends('layouts.app')

@section('title', 'Unsubscribe from HopeNest Updates')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-red-50 to-gray-100 py-12 px-4">
    <div class="max-w-lg mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-rose-500 to-pink-600 px-8 py-12 text-center">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white">We'll Miss You</h1>
            </div>

            <!-- Form -->
            <div class="p-8">
                @if(request()->has('email') && filter_var(request()->email, FILTER_VALIDATE_EMAIL))
                    <div class="text-center mb-8">
                        <p class="text-gray-700 text-lg">
                            <strong>{{ request()->email }}</strong> 
                            will be removed from our newsletter.
                        </p>
                    </div>

                    <form action="{{ route('newsletter.unsubscribe') }}" method="POST">
                        @csrf
<form action="{{ route('newsletter.unsubscribe') }}" method="POST">
    @csrf
    <input type="hidden" name="email" value="{{ request()->email ?? old('email') }}">
    <!-- OR for manual entry -->
    @if(!request()->has('email'))
        <input type="email" name="email" value="{{ old('email') }}" 
               placeholder="Enter your email" class="w-full px-4 py-3 border rounded-lg mb-4" required>
    @endif
                        <input type="hidden" name="email" value="{{ request()->email }}">

                        <button type="submit" 
                                class="w-full bg-rose-600 hover:bg-rose-700 text-white font-bold py-4 px-6 rounded-xl transition transform hover:scale-105 shadow-lg">
                            Yes, Unsubscribe Me
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <a href="{{ url('/') }}" 
                           class="text-gray-600 hover:text-gray-800 underline">
                            No, keep me subscribed
                        </a>
                    </div>
                @else
                    <p class="text-center text-gray-600">No email provided. Please use the link from your email.</p>
                @endif
            </div>

            <!-- Footer Note -->
            <div class="bg-gray-50 px-8 py-6 text-center text-sm text-gray-600 border-t">
                <p>You can always re-subscribe anytime from our website.</p>
            </div>
        </div>
    </div>
</div>
@endsection