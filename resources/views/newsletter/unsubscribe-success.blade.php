{{-- resources/views/newsletter/unsubscribed.blade.php --}}
@extends('layouts.app')

@section('title', 'Successfully Unsubscribed')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full text-center">
        <div class="bg-white rounded-2xl shadow-xl p-12">
            <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M20 12H4"></path>
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-gray-800 mb-4">You've Been Unsubscribed</h2>
@if(session('status'))
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
        <p class="text-green-800 font-medium">{{ session('status') }}</p>
    </div>
@endif
            <p class="text-gray-600 mb-8">
                We're sad to see you go, but we respect your choice.<br>
                You will no longer receive newsletter updates from HopeNest.
            </p>

            <p class="text-sm text-gray-500 mb-8">
                Changed your mind? You can 
                <a href="{{ url('/') }}" class="text-emerald-600 font-medium hover:underline">
                    subscribe again anytime
                </a>.
            </p>

            <a href="{{ url('/') }}" 
               class="inline-block bg-gray-800 hover:bg-gray-900 text-white font-medium py-3 px-8 rounded-full transition">
                ← Back to HopeNest
            </a>
        </div>

        <p class="text-gray-500 text-sm mt-10">
            © {{ date('Y') }} HopeNest • Thank you for your past support
        </p>
    </div>
</div>
@endsection