{{-- @extends('layouts.user')

@section('title', 'Donation Receipt - ' . $donation->receipt_number)

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden mb-8">
            <div class="bg-gradient-to-r from-primary to-secondary p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold">Thank You for Your Donation!</h1>
                        <p class="text-white/90 mt-2 text-lg">Your generosity changes lives</p>
                    </div>
                    <i class="fas fa-hands-helping text-6xl md:text-8xl opacity-30"></i>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                            Receipt #{{ $donation->receipt_number }}
                        </h2>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">
                            {{ $donation->paid_at?->format('F d, Y \a\t g:i A') ?? 'Payment Pending' }}
                        </p>
                    </div>
                    <div>
                        <span class="inline-flex items-center px-6 py-3 rounded-full text-base md:text-lg font-bold
                            {{ $donation->status === 'successful' 
                                ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' 
                                : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                            <i class="fas {{ $donation->status === 'successful' ? 'fa-check-circle' : 'fa-clock' }} mr-2"></i>
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                </div>

                <!-- Donation Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Donor</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                {{ $donation->is_anonymous ? 'Anonymous Donor' : $donation->donor_name }}
                            </p>
                            @if(!$donation->is_anonymous)
                                <p class="text-gray-600 dark:text-gray-400">{{ $donation->donor_email }}</p>
                            @endif
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Campaign</p>
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-primary/10 dark:bg-primary/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-heart text-primary text-xl"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">
                                        {{ $donation->campaign?->title ?? 'Deleted Campaign' }}
                                    </p>
                                    @if($donation->campaign?->campaignCategory)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $donation->campaign->campaignCategory->name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Amount Donated</p>
                            <p class="text-3xl md:text-4xl font-bold text-green-600 dark:text-green-400">
                                ₦{{ number_format($donation->amount_in_base_currency ?? $donation->total_amount, 2) }}
                            </p>
                            @if($donation->cover_fee && $donation->processing_fee > 0)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Includes ₦{{ number_format($donation->processing_fee, 2) }} processing fee (covered by you)
                                </p>
                            @endif
                        </div>

                        @if($donation->frequency !== 'one-time')
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                <p class="text-sm font-medium text-blue-800 dark:text-blue-300 flex items-center gap-2">
                                    <i class="fas fa-sync-alt"></i>
                                    Recurring Donation ({{ ucfirst($donation->frequency) }})
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Message -->
                @if($donation->message)
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-6 border border-gray-200 dark:border-gray-600 mb-8">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <i class="fas fa-comment-dots text-primary"></i>
                            Your Message
                        </p>
                        <p class="text-gray-800 dark:text-gray-200 italic">"{{ $donation->message }}"</p>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="pt-8 border-t dark:border-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('user.donations.pdf', $donation) }}"
                           class="inline-flex items-center justify-center gap-2 px-4 py-3 bg-primary text-white font-medium rounded-lg hover:bg-primary/90 transition-all shadow-lg hover:shadow-xl group">
                            <i class="fas fa-download group-hover:scale-110 transition-transform"></i>
                            <span>Download PDF</span>
                        </a>

                        <button onclick="shareDonation()"
                                class="inline-flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-all shadow-lg hover:shadow-xl group">
                            <i class="fas fa-share-alt group-hover:scale-110 transition-transform"></i>
                            <span>Share Impact</span>
                        </button>

                        <button onclick="alert('Tax certificate request feature coming soon!')"
                                class="inline-flex items-center justify-center gap-2 px-4 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-all shadow-lg hover:shadow-xl group">
                            <i class="fas fa-certificate group-hover:scale-110 transition-transform"></i>
                            <span>Tax Certificate</span>
                        </button>

                        <a href="{{ route('user.donations.index') }}"
                           class="inline-flex items-center justify-center gap-2 px-4 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all group">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            <span>Back to List</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Impact Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-heart text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Your Impact</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">Life-Changing</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Community</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">Growing Together</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-full flex items-center justify-center">
                        <i class="fas fa-hands-helping text-2xl text-orange-600 dark:text-orange-400"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gratitude</p>
                        <p class="text-xl font-bold text-gray-900 dark:text-white">Thank You!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 text-center">
            <div class="max-w-2xl mx-auto">
                <i class="fas fa-shield-alt text-3xl text-primary mb-3"></i>
                <p class="text-gray-700 dark:text-gray-300 font-medium mb-2">
                    HopeNest is a registered non-profit organization
                </p>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    All donations are tax-deductible where applicable. This receipt serves as proof of your charitable contribution.
                </p>
            </div>
        </div>
    </div>
</main>

<script>
function shareDonation() {
    const text = "I just donated ₦{{ number_format($donation->amount_in_base_currency ?? $donation->total_amount, 0) }} to {{ $donation->campaign?->title ?? 'HopeNest' }} via HopeNest! Join me in making a difference";
    const url = "{{ url('/') }}";

    if (navigator.share) {
        navigator.share({
            title: 'My Donation to HopeNest',
            text: text,
            url: url
        }).catch(console.error);
    } else {
        const shareText = encodeURIComponent(text + " " + url);
        window.open(`https://twitter.com/intent/tweet?text=${shareText}`, '_blank');
    }
}
</script>
@endsection --}}