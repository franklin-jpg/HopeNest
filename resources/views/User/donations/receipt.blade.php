{{-- resources/views/user/donations/receipt.blade.php --}}
@extends('layouts.user')

@section('title', 'Receipt - ' . $donation->receipt_number)

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8 px-4">
    <div class="max-w-4xl mx-auto">

        <!-- Receipt Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header Gradient -->
            <div class="bg-gradient-to-r from-primary to-secondary p-10 text-white text-center">
                <div class="flex justify-center mb-6">
                    <div class="w-24 h-24 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
                        <i class="fas fa-hands-helping text-5xl"></i>
                    </div>
                </div>
                <h1 class="text-4xl font-bold mb-3">Thank You!</h1>
                <p class="text-xl opacity-90">Your donation has been received and is already making a difference</p>
            </div>

            <div class="p-8 lg:p-12">

                <!-- Receipt Header -->
                <div class="text-center mb-10">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                        Official Receipt
                    </h2>
                    <p class="text-2xl text-primary font-semibold mt-2">
                        #{{ $donation->receipt_number }}
                    </p>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">
                        {{ $donation->paid_at?->format('l, F d, Y \a\t g:i A') ?? 'Payment Processing' }}
                    </p>
                </div>

                <!-- Donation Summary -->
                <div class="grid md:grid-cols-2 gap-10 mb-12">
                    <div class="space-y-8">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Donor</p>
                            <div class="mt-3">
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ $donation->is_anonymous ? 'Anonymous Hero' : $donation->donor_name }}
                                </p>
                                @if(!$donation->is_anonymous)
                                    <p class="text-gray-600 dark:text-gray-400 flex items-center gap-2 mt-2">
                                        <i class="fas fa-envelope"></i> {{ $donation->donor_email }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Supported Campaign</p>
                            <div class="mt-3 flex items-start gap-4">
                                <div class="w-16 h-16 bg-primary/10 dark:bg-primary/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-heart text-2xl text-primary"></i>
                                </div>
                                <div>
                                    <p class="text-xl font-bold text-gray-900 dark:text-white">
                                        {{ $donation->campaign?->title ?? 'General Support Fund' }}
                                    </p>
                                    @if($donation->campaign?->campaignCategory)
                                        <p class="text-gray-600 dark:text-gray-400">
                                            {{ $donation->campaign->campaignCategory->name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount Donated</p>
                            <div class="mt-4 p-6 bg-green-50 dark:bg-green-900/20 rounded-2xl border-2 border-green-200 dark:border-green-800">
                                <p class="text-5xl font-bold text-green-600 dark:text-green-400 text-center">
                                    ₦{{ number_format($donation->amount_in_base_currency ?? $donation->total_amount, 2) }}
                                </p>
                                @if($donation->cover_fee && $donation->processing_fee > 0)
                                    <p class="text-center text-sm text-gray-600 dark:text-gray-400 mt-3">
                                        Includes ₦{{ number_format($donation->processing_fee, 2) }} processing fee (covered by donor)
                                    </p>
                                @endif
                            </div>
                        </div>

                        <div class="text-center">
                            <span class="inline-flex items-center px-8 py-4 rounded-full text-2xl font-bold
                                {{ $donation->status === 'successful' 
                                    ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                    : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                <i class="fas {{ $donation->status === 'successful' ? 'fa-check-circle' : 'fa-clock' }} mr-3 text-3xl"></i>
                                {{ ucfirst($donation->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Personal Message -->
                @if($donation->message)
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-2 border-blue-200 dark:border-blue-700 rounded-2xl p-8 mb-10">
                        <p class="text-lg font-semibold text-blue-900 dark:text-blue-300 mb-4 flex items-center gap-3">
                            <i class="fas fa-quote-left text-3xl opacity-50"></i>
                            Your Message to the Cause
                        </p>
                        <p class="text-xl italic text-gray-800 dark:text-gray-200 leading-relaxed">
                            "{{ $donation->message }}"
                        </p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-center gap-4 pt-8 border-t dark:border-gray-700">
                    <a href="{{ route('user.donations.pdf', $donation) }}"
                       class="inline-flex items-center gap-3 px-8 py-5 bg-primary text-white text-lg font-semibold rounded-xl hover:bg-primary/90 transform hover:scale-105 transition shadow-xl">
                        <i class="fas fa-file-pdf text-2xl"></i>
                        Download PDF Receipt
                    </a>

                    <button onclick="shareDonation()"
                            class="inline-flex items-center gap-3 px-8 py-5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-lg font-semibold rounded-xl hover:from-indigo-700 hover:to-purple-700 transform hover:scale-105 transition shadow-xl">
                        <i class="fas fa-share-alt text-2xl"></i>
                        Share Your Impact
                    </button>

                    <button onclick="alert('Tax certificate request is coming soon!')"
                            class="inline-flex items-center gap-3 px-8 py-5 bg-gradient-to-r from-amber-500 to-orange-600 text-white text-lg font-semibold rounded-xl hover:from-amber-600 hover:to-orange-700 transform hover:scale-105 transition shadow-xl">
                        <i class="fas fa-certificate text-2xl"></i>
                        Request Tax Certificate
                    </button>
                </div>

                <!-- Back Button -->
                <div class="text-center mt-10">
                    <a href="{{ route('user.donations.index') }}"
                       class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-primary transition">
                        <i class="fas fa-arrow-left"></i>
                        Back to My Donations
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="text-center mt-12 text-gray-600 dark:text-gray-400">
            <p class="text-lg font-medium">HopeNest Foundation</p>
            <p>A registered non-profit organization • All donations are tax-deductible</p>
            <p class="mt-4 text-sm">
                <i class="fas fa-heart text-red-500"></i>
                Thank you for being a hero
                <i class="fas fa-heart text-red-500"></i>
            </p>
        </div>
    </div>
</div>

<script>
function shareDonation() {
    const amount = "₦{{ number_format($donation->amount_in_base_currency ?? $donation->total_amount, 0) }}";
    const campaign = "{{ addslashes($donation->campaign?->title ?? 'HopeNest') }}";
    const text = `I just donated ${amount} to "${campaign}" via HopeNest! Join me in changing lives.`;
    const url = "{{ url('/') }}";

    if (navigator.share) {
        navigator.share({ title: 'I supported HopeNest!', text, url })
            .catch(() => fallbackShare(text, url));
    } else {
        fallbackShare(text, url);
    }
}

function fallbackShare(text, url) {
    const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text + ' ' + url)}`;
    window.open(shareUrl, '_blank');
}
</script>
@endsection