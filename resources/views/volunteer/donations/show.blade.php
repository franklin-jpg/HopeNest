@extends('layouts.volunteer')

@section('title', 'Donation Details')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden transition-all duration-300">
        <!-- Header Gradient -->
        <div class="px-8 py-6  bg-orange-600">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">Donation Receipt</h1>
                    <p class="mt-2 text-indigo-100 text-lg font-medium">
                        #{{ $donation->receipt_number ?? $donation->payment_reference }}
                    </p>
                </div>
                <div class="mt-4 sm:mt-0">
                    @php
                        $statusColors = [
                            'successful' => 'bg-green-500',
                            'pending'    => 'bg-yellow-500',
                            'failed'     => 'bg-red-500',
                            'refunded'   => 'bg-gray-500',
                        ];
                        $statusBg = $statusColors[$donation->status] ?? 'bg-gray-500';
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold text-white {{ $statusBg }} shadow-lg">
                        {{ ucfirst(str_replace('_', ' ', $donation->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="p-8 space-y-8">
            <!-- Key Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-indigo-50 to-purple-50 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-xl p-6 border border-indigo-200 dark:border-indigo-700">
                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Campaign</p>
                    <p class="mt-2 text-xl font-bold text-gray-900 dark:text-white">
                        {{ $donation->campaign?->title ?? 'Deleted Campaign' }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/30 dark:to-emerald-900/30 rounded-xl p-6 border border-green-200 dark:border-green-700">
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Amount Donated</p>
                    <p class="mt-2 text-2xl font-extrabold text-green-700 dark:text-green-300">
                        {{ $donation->getFormattedAmountAttribute() }}
                    </p>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-xl p-6 border border-blue-200 dark:border-blue-700">
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Paid</p>
                    <p class="mt-2 text-2xl font-extrabold text-blue-700 dark:text-blue-300">
                        {{ $donation->getFormattedTotalAmountAttribute() }}
                    </p>
                    @if($donation->cover_fee)
                        <p class="mt-2 text-xs text-green-600 dark:text-green-400 font-medium">
                            You covered the processing fee
                        </p>
                    @endif
                </div>
            </div>

            <hr class="border-gray-200 dark:border-gray-700">

            <!-- Donation Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-5 border border-gray-200 dark:border-gray-600">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Date & Time</p>
                    <p class="mt-1 text-gray-900 dark:text-white font-semibold">
                        {{ $donation->paid_at?->format('d F Y') ?? '—' }} <br>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $donation->paid_at?->format('h:i A') ?? 'Pending' }}
                        </span>
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-5 border border-gray-200 dark:border-gray-600">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Frequency</p>
                    <p class="mt-1 text-gray-900 dark:text-white font-semibold capitalize">
                        {{ ucfirst(str_replace(['-', '_'], ' ', $donation->frequency)) }}
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-5 border border-gray-200 dark:border-gray-600">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Payment Method</p>
                    <p class="mt-1 text-gray-900 dark:text-white font-semibold">
                        {{ $donation->payment_method ? ucwords(str_replace('_', ' ', $donation->payment_method)) : '—' }}
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-5 border border-gray-200 dark:border-gray-600">
                    <p class="text-gray-500 dark:text-gray-400 font-medium">Anonymous?</p>
                    <p class="mt-1 text-gray-900 dark:text-white font-semibold">
                        {{ $donation->is_anonymous ? 'Yes' : 'No' }}
                    </p>
                </div>
            </div>

            <!-- Message from Donor -->
            @if($donation->message)
                <div class="bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-6">
                    <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2z"/>
                        </svg>
                        Message from You
                    </p>
                    <p class="mt-3 text-gray-800 dark:text-gray-200 italic leading-relaxed">
                        "{{ $donation->message }}"
                    </p>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-end pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('volunteer.volunteer-donations.index') }}"
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 dark:bg-gray-700 dark:hover:bg-gray-600 text-white font-medium rounded-lg shadow-md transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Donations
                </a>
            </div>
        </div>
    </div>
</div>
@endsection