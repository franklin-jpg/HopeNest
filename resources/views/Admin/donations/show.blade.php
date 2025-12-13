{{-- resources/views/admin/donations/show.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
        <div>
            <nav class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                <a href="{{ route('admin.donations.index') }}" class="hover:text-blue-600">All Donations</a>
                <span class="mx-2">/</span>
            @if($donation->campaign)
                    <a href="{{ route('admin.campaigns.show', $donation->campaign->id) }}" class="hover:text-blue-600">
                        {{ Str::limit($donation->campaign->title, 30) }}
                    </a>
                @else
                    <span class="text-gray-500">[Deleted Campaign]</span>
                @endif
                <span class="mx-2">/</span>
                <span>{{ $donation->receipt_number ?? 'Details' }}</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-receipt text-blue-600"></i>
                Donation Details
            </h1>
        </div>

        <div class="flex items-center gap-3">
            @if($donation->status === 'successful' && $donation->receipt_number)
            <a href="{{ route('donations.receipt', $donation->id) }}" 
               class="flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-download"></i> Download Receipt
            </a>
            @endif
            <a href="{{ route('admin.donations.index') }}"
               class="flex items-center gap-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white px-4 py-2 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="mb-6 rounded-lg p-4 border-l-4
        @if($donation->status === 'successful') bg-green-50 dark:bg-green-900/20 border-green-500
        @elseif($donation->status === 'pending') bg-yellow-50 dark:bg-yellow-900/20 border-yellow-500
        @elseif($donation->status === 'failed') bg-red-50 dark:bg-red-900/20 border-red-500
        @elseif($donation->status === 'refunded') bg-gray-50 dark:bg-gray-900/20 border-gray-500
        @else bg-blue-50 dark:bg-blue-900/20 border-blue-500 @endif">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($donation->status === 'successful')
                    <i class="fas fa-check-circle text-green-600 text-3xl"></i>
                    <div>
                        <p class="font-bold text-green-800 dark:text-green-200 text-lg">Payment Successful</p>
                        <p class="text-sm text-green-700 dark:text-green-300">
                            Received {{ $donation->paid_at ? $donation->paid_at->diffForHumans() : 'recently' }}
                        </p>
                    </div>
                @elseif($donation->status === 'pending')
                    <i class="fas fa-clock text-yellow-600 text-3xl"></i>
                    <div>
                        <p class="font-bold text-yellow-800 dark:text-yellow-200 text-lg">Payment Pending</p>
                        <p class="text-sm text-yellow-700 dark:text-yellow-300">Awaiting payment confirmation</p>
                    </div>
                @elseif($donation->status === 'failed')
                    <i class="fas fa-times-circle text-red-600 text-3xl"></i>
                    <div>
                        <p class="font-bold text-red-800 dark:text-red-200 text-lg">Payment Failed</p>
                        <p class="text-sm text-red-700 dark:text-red-300">Transaction could not be completed</p>
                    </div>
                @elseif($donation->status === 'refunded')
                    <i class="fas fa-undo text-gray-600 text-3xl"></i>
                    <div>
                        <p class="font-bold text-gray-800 dark:text-gray-200 text-lg">Refunded</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            This donation has been refunded
                            @if($donation->refunded_at)
                                on {{ $donation->refunded_at->format('M d, Y') }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
            
            @if($donation->receipt_number)
            <div class="text-right">
                <p class="text-sm text-gray-600 dark:text-gray-400">Receipt Number</p>
                <p class="font-mono font-bold text-lg text-gray-800 dark:text-gray-200">{{ $donation->receipt_number }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (Left) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payment Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
                <div class="border-b dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-credit-card text-blue-600"></i>
                        Payment Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Amount -->
                        <div class="col-span-2">
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-lg p-6 text-center border border-blue-200 dark:border-blue-800">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Donation Amount</p>
                                <p class="text-5xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ $donation->getCurrencySymbol() }}{{ number_format($donation->amount, 2) }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ $donation->currency }}</p>
                                @if($donation->currency !== 'NGN')
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-2">
                                        ≈ ₦{{ number_format($donation->amount_in_base_currency, 2) }} (Base Currency)
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Processing Fee -->
                        @if($donation->processing_fee > 0)
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-percent text-gray-400"></i>
                                Processing Fee
                            </label>
                            <p class="text-xl font-semibold text-gray-800 dark:text-gray-200 mt-2">
                                {{ $donation->getCurrencySymbol() }}{{ number_format($donation->processing_fee, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                @if($donation->cover_fee)
                                    <i class="fas fa-check-circle text-green-600 mr-1"></i>Covered by donor
                                @else
                                    <i class="fas fa-times-circle text-red-600 mr-1"></i>Absorbed by campaign
                                @endif
                            </p>
                        </div>
                        @endif

                        <!-- Total Amount -->
                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-calculator text-gray-400"></i>
                                Total Charged
                            </label>
                            <p class="text-xl font-bold text-green-600 dark:text-green-400 mt-2">
                                {{ $donation->getCurrencySymbol() }}{{ number_format($donation->total_amount, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Amount + Processing Fee
                            </p>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-credit-card text-gray-400"></i>
                                Payment Method
                            </label>
                            <p class="text-lg font-semibold text-gray-800 dark:text-gray-200 mt-2 capitalize">
                                {{ $donation->payment_method ? str_replace('_', ' ', $donation->payment_method) : 'Not specified' }}
                            </p>
                        </div>

                      

                        <!-- Transaction ID -->
                        @if($donation->transaction_id)
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2 mb-2">
                                <i class="fas fa-hashtag text-gray-400"></i>
                                Transaction ID
                            </label>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between">
                                <code class="text-sm font-mono text-gray-800 dark:text-gray-200">
                                    {{ $donation->transaction_id }}
                                </code>
                                <button onclick="copyToClipboard('{{ $donation->transaction_id }}')" 
                                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 p-2"
                                        title="Copy">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        @endif

                        <!-- Payment Reference -->
                        @if($donation->payment_reference)
                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2 mb-2">
                                <i class="fas fa-key text-gray-400"></i>
                                Payment Reference
                            </label>
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 flex items-center justify-between">
                                <code class="text-sm font-mono text-gray-800 dark:text-gray-200">
                                    {{ $donation->payment_reference }}
                                </code>
                                <button onclick="copyToClipboard('{{ $donation->payment_reference }}')" 
                                        class="text-blue-600 hover:text-blue-700 dark:text-blue-400 p-2"
                                        title="Copy">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Donor Information -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
                <div class="border-b dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        @if($donation->is_anonymous)
                            <i class="fas fa-user-secret text-purple-600"></i>
                        @else
                            <i class="fas fa-user text-blue-600"></i>
                        @endif
                        Donor Information
                    </h2>
                </div>
                <div class="p-6">
                    @if($donation->is_anonymous)
                        <div class="text-center py-8">
                            <i class="fas fa-user-secret text-6xl text-purple-400 mb-4"></i>
                            <p class="text-xl font-bold text-gray-800 dark:text-gray-200">Anonymous Donation</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                This donor chose to remain anonymous. Personal information is hidden for privacy.
                            </p>
                        </div>
                    @else
                        <div class="space-y-6">
                            <!-- Donor Name -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2 mb-2">
                                    <i class="fas fa-user text-gray-400"></i>
                                    Full Name
                                </label>
                                <p class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center gap-2">
                                    {{ $donation->donor_name }}
                                    @if($donation->user)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            <i class="fas fa-user-check mr-1"></i>Registered User
                                        </span>
                                    @endif
                                </p>
                            </div>

                            <!-- Email -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2 mb-2">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                    Email Address
                                </label>
                                <div class="flex items-center justify-between">
                                    <a href="mailto:{{ $donation->donor_email }}" 
                                       class="text-lg text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        {{ $donation->donor_email }}
                                    </a>
                                    <button onclick="copyToClipboard('{{ $donation->donor_email }}')" 
                                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 p-2"
                                            title="Copy Email">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Phone -->
                            @if($donation->donor_phone)
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <label class="text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2 mb-2">
                                    <i class="fas fa-phone text-gray-400"></i>
                                    Phone Number
                                </label>
                                <div class="flex items-center justify-between">
                                    <a href="tel:{{ $donation->donor_phone }}" 
                                       class="text-lg text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        {{ $donation->donor_phone }}
                                    </a>
                                    <button onclick="copyToClipboard('{{ $donation->donor_phone }}')" 
                                            class="text-blue-600 hover:text-blue-700 dark:text-blue-400 p-2"
                                            title="Copy Phone">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                            @endif

                            <!-- User Account Link -->
                            @if($donation->user)
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4 border border-blue-200 dark:border-blue-800">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-semibold text-blue-800 dark:text-blue-200 flex items-center gap-2">
                                            <i class="fas fa-user-circle"></i>
                                            Registered User Account
                                        </p>
                                        <p class="text-xs text-blue-700 dark:text-blue-300 mt-1">
                                            {{ $donation->user->name }} ({{ $donation->user->email }})
                                        </p>
                                    </div>
                                    <a href="#" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Donor Message -->
            @if($donation->message)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
                <div class="border-b dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-comment-alt text-purple-600"></i>
                        Donor Message
                    </h2>
                </div>
                <div class="p-6">
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-lg p-6 border border-purple-200 dark:border-purple-800">
                        <i class="fas fa-quote-left text-purple-400 text-2xl mb-3"></i>
                        <p class="text-gray-800 dark:text-gray-200 text-lg leading-relaxed italic">
                            {{ $donation->message }}
                        </p>
                        <i class="fas fa-quote-right text-purple-400 text-2xl mt-3 float-right"></i>
                    </div>
                </div>
            </div>
            @endif

            <!-- Refund Information -->
            @if($donation->status === 'refunded' && $donation->refund_reason)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
                <div class="border-b dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-undo text-red-600"></i>
                        Refund Information
                    </h2>
                </div>
                <div class="p-6">
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200 mb-2">Refund Reason:</p>
                        <p class="text-gray-700 dark:text-gray-300">{{ $donation->refund_reason }}</p>
                        @if($donation->refunded_at)
                            <p class="text-xs text-red-600 dark:text-red-400 mt-3">
                                Refunded on {{ $donation->refunded_at->format('M d, Y \a\t g:i A') }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar (Right) -->
        <div class="space-y-6">
            <!-- Campaign Info -->
         <!-- Campaign Info -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
    <div class="border-b dark:border-gray-700 p-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
            <i class="fas fa-bullhorn text-blue-600"></i>
            Campaign
        </h3>
    </div>
    <div class="p-4">
        @if($donation->campaign)
            <img src="{{ asset('storage/' . $donation->campaign->featured_image) }}" 
                 alt="{{ $donation->campaign->title }}" 
                 class="w-full h-40 object-cover rounded-lg mb-4">

            <h4 class="font-bold text-gray-800 dark:text-gray-200 mb-3">
                {{ $donation->campaign->title }}
            </h4>

            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between pb-2 border-b dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Category</span>
                    <span class="text-gray-800 dark:text-gray-200 font-medium">
                        {{ $donation->campaign->campaignCategory?->name ?? '—' }}
                    </span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Status</span>
                    <span class="px-2 py-1 rounded text-xs font-semibold
                        @if($donation->campaign->status === 'active') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200
                        @elseif($donation->campaign->status === 'completed') bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200
                        @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                        {{ ucfirst($donation->campaign->status) }}
                    </span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Goal</span>
                    <span class="text-gray-800 dark:text-gray-200 font-medium">
                        ₦{{ number_format($donation->campaign->goal_amount, 2) }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Raised</span>
                    <span class="text-green-600 dark:text-green-400 font-bold">
                        ₦{{ number_format($donation->campaign->raised_amount, 2) }}
                    </span>
                </div>
            </div>

            <a href="{{ route('admin.campaigns.show', $donation->campaign->id) }}" 
               class="block mt-4 text-center bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition text-sm">
                <i class="fas fa-external-link-alt mr-2"></i>View Campaign
            </a>
        @else
            <!-- Campaign Deleted / Missing -->
            <div class="text-center py-8">
                <i class="fas fa-trash-alt text-5xl text-gray-400 mb-4"></i>
                <p class="text-lg font-semibold text-gray-600 dark:text-gray-400">Campaign Deleted</p>
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">
                    This donation was made to a campaign that no longer exists.
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-600 mt-4">
                    Campaign ID: #{{ $donation->campaign_id }}
                </p>
            </div>
        @endif
    </div>
</div>

            <!-- Timeline -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
                <div class="border-b dark:border-gray-700 p-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-clock text-purple-600"></i>
                        Timeline
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    <!-- Created -->
                    <div class="flex items-start gap-3">
                        <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                            <i class="fas fa-plus text-blue-600 dark:text-blue-300"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Donation Created</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $donation->created_at->format('M d, Y \a\t g:i A') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $donation->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>

                    <!-- Paid -->
                    @if($donation->paid_at)
                    <div class="flex items-start gap-3">
                        <div class="bg-green-100 dark:bg-green-900 p-2 rounded-lg">
                            <i class="fas fa-check text-green-600 dark:text-green-300"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Payment Confirmed</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $donation->paid_at->format('M d, Y \a\t g:i A') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $donation->paid_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Refunded -->
                    @if($donation->refunded_at)
                    <div class="flex items-start gap-3">
                        <div class="bg-red-100 dark:bg-red-900 p-2 rounded-lg">
                            <i class="fas fa-undo text-red-600 dark:text-red-300"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Refund Processed</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $donation->refunded_at->format('M d, Y \a\t g:i A') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $donation->refunded_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <!-- Updated -->
                    @if($donation->updated_at->ne($donation->created_at))
                    <div class="flex items-start gap-3">
                        <div class="bg-gray-100 dark:bg-gray-700 p-2 rounded-lg">
                            <i class="fas fa-edit text-gray-600 dark:text-gray-300"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">Last Updated</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $donation->updated_at->format('M d, Y \a\t g:i A') }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $donation->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Additional Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
                <div class="border-b dark:border-gray-700 p-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-info-circle text-blue-600"></i>
                        Additional Info
                    </h3>
                </div>
                <div class="p-4 space-y-3 text-sm">
                    <div class="flex items-center justify-between pb-2 border-b dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Donation ID</span>
                        <span class="font-mono text-gray-800 dark:text-gray-200">#{{ $donation->id }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between pb-2 border-b dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Terms Agreed</span>
                        <span>
                            @if($donation->agreed_to_terms)
                                <i class="fas fa-check-circle text-green-600"></i>
                            @else
                                <i class="fas fa-times-circle text-red-600"></i>
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between pb-2 border-b dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Anonymous</span>
                        <span>
                            @if($donation->is_anonymous)
                                <i class="fas fa-check-circle text-purple-600"></i>
                            @else
                                <i class="fas fa-times-circle text-gray-400"></i>
                            @endif
                        </span>
                    </div>

                    <div class="flex items-center justify-between pb-2 border-b dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">Tax Certificate</span>
                        <span>
                            @if($donation->tax_certificate_sent)
                                <i class="fas fa-check-circle text-green-600"></i>
                                <span class="text-xs text-gray-500 ml-1">Sent</span>
                            @else
                                <i class="fas fa-times-circle text-gray-400"></i>
                            @endif
                        </span>
                    </div>

                    @if($donation->exchange_rate && $donation->exchange_rate != 1)
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 dark:text-gray-400">Exchange Rate</span>
                        <span class="font-mono text-gray-800 dark:text-gray-200">{{ number_format($donation->exchange_rate, 4) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
                <div class="border-b dark:border-gray-700 p-4">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                        <i class="fas fa-bolt text-yellow-600"></i>
                        Quick Actions
                    </h3>
                </div>
                <div class="p-4 space-y-2">
                    @if($donation->status === 'successful')
                        <!-- Download Receipt -->
                        <a href="{{ route('donations.receipt', $donation->id) }}" 
                           class="flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                            <i class="fas fa-download"></i> Download Receipt
                        </a>
                        
                        @if(!$donation->is_anonymous)
                            <!-- Resend Receipt -->
                            <form action="{{ route('admin.donations.resend-receipt', $donation->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                                    <i class="fas fa-paper-plane"></i> Resend Receipt
                                </button>
                            </form>

                            <!-- Send Thank You -->
                            <form action="{{ route('admin.donations.send-thank-you', $donation->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="flex items-center justify-center gap-2 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                                    <i class="fas fa-heart"></i> Send Thank You
                                </button>
                            </form>

                            <!-- Email Donor -->
                            <a href="mailto:{{ $donation->donor_email }}" 
                               class="flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                                <i class="fas fa-envelope"></i> Email Donor
                            </a>
                        @endif

                        @if(!$donation->tax_certificate_sent)
                            <!-- Mark Tax Certificate Sent -->
                            <form action="{{ route('admin.donations.mark-tax-cert', $donation->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                                    <i class="fas fa-file-invoice"></i> Mark Tax Cert Sent
                                </button>
                            </form>
                        @endif

                        <!-- Refund -->
                        <button type="button" onclick="openRefundModal()" 
                                class="flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                            <i class="fas fa-undo"></i> Process Refund
                        </button>
                    @endif

                    @if($donation->status === 'failed' && !$donation->is_anonymous)
                        <!-- Send Retry Link -->
                        <form action="{{ route('admin.donations.send-retry-link', $donation->id) }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                                <i class="fas fa-redo"></i> Send Retry Link
                            </button>
                        </form>
                    @endif

                    @if($donation->frequency !== 'one-time')
                        @if($donation->recurring_status === 'active')
                            <!-- Pause Recurring -->
                            <form action="{{ route('admin.donations.pause-recurring', $donation->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="flex items-center justify-center gap-2 bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                                    <i class="fas fa-pause"></i> Pause Recurring
                                </button>
                            </form>
                        @endif

                        @if($donation->recurring_status !== 'cancelled')
                            <!-- Cancel Recurring -->
                            <form action="{{ route('admin.donations.cancel-recurring', $donation->id) }}" method="POST" 
                                  onsubmit="return confirm('Are you sure you want to cancel this recurring donation?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit" 
                                        class="flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                                    <i class="fas fa-ban"></i> Cancel Recurring
                                </button>
                            </form>
                        @endif
                    @endif

                    <!-- Update Status -->
                    <button type="button" onclick="openStatusModal()" 
                            class="flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2.5 rounded-lg transition text-sm w-full">
                        <i class="fas fa-edit"></i> Update Status
                    </button>
                </div>
            </div>

            <!-- Campaign Progress -->
           <!-- Campaign Progress -->
<div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border dark:border-gray-700">
    <div class="border-b dark:border-gray-700 p-4">
        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
            Chart: Campaign Progress
        </h3>
    </div>
    <div class="p-4">
        @if($donation->campaign)
            <div class="mb-4">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-gray-600 dark:text-gray-400">Progress</span>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ number_format($donation->campaign->progress_percentage ?? 0, 1) }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 h-full rounded-full transition-all duration-700"
                         style="width: {{ min($donation->campaign->progress_percentage ?? 0, 100) }}%"></div>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Total Donors</span>
                    <span class="font-semibold text-gray-800 dark:text-gray-200">
                        {{ $donation->campaign->donorsCount() }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Goal</span>
                    <span class="font-medium dark:text-green-400">₦{{ number_format($donation->campaign->goal_amount, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Raised</span>
                    <span class="font-bold text-green-600 dark:text-green-400">
                        ₦{{ number_format($donation->campaign->raised_amount, 2) }}
                    </span>
                </div>
                <div class="flex justify-between pt-2 border-t dark:border-gray-700">
                    <span class="text-gray-600 dark:text-gray-400">Remaining</span>
                    <span class="font-semibold text-gray-800 dark:text-green-400">
                        ₦{{ number_format(max(0, $donation->campaign->goal_amount - $donation->campaign->raised_amount), 2) }}
                    </span>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <i class="fas fa-ban text-4xl mb-3 opacity-50"></i>
                <p class="font-medium">Campaign no longer exists</p>
                <p class="text-xs mt-1">Progress data unavailable</p>
            </div>
        @endif
    </div>
</div>
        </div>
    </div>
</div>

<!-- Refund Modal -->
<div id="refundModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Process Refund</h3>
            <button onclick="closeRefundModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.donations.refund', $donation->id) }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                    <p class="text-sm text-red-800 dark:text-red-200">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Warning:</strong> This action cannot be undone. The donation amount will be refunded and the campaign's raised amount will be updated.
                    </p>
                </div>

                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Refund Amount</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                        {{ $donation->getCurrencySymbol() }}{{ number_format($donation->total_amount, 2) }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Refund Reason <span class="text-red-600">*</span>
                    </label>
                    <textarea name="refund_reason" rows="4" required
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-red-500"
                              placeholder="Please provide a detailed reason for this refund..."></textarea>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        This reason will be logged for record-keeping purposes.
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 p-6 border-t dark:border-gray-700">
                <button type="button" onclick="closeRefundModal()" 
                        class="px-6 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                    <i class="fas fa-undo mr-2"></i>Process Refund
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Status Modal -->
<div id="statusModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full">
        <div class="flex items-center justify-between p-6 border-b dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Update Donation Status</h3>
            <button onclick="closeStatusModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form action="{{ route('admin.donations.update-status', $donation->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Current Status
                    </label>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                            @if($donation->status === 'successful') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200
                            @elseif($donation->status === 'pending') bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($donation->status === 'failed') bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-200
                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200 @endif">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        New Status <span class="text-red-600">*</span>
                    </label>
                    <select name="status" required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Status</option>
                        <option value="pending" {{ $donation->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="successful" {{ $donation->status === 'successful' ? 'selected' : '' }}>Successful</option>
                        <option value="failed" {{ $donation->status === 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ $donation->status === 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Note (Optional)
                    </label>
                    <textarea name="note" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500"
                              placeholder="Add any notes about this status change..."></textarea>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                    <p class="text-xs text-blue-800 dark:text-blue-200">
                        <i class="fas fa-info-circle mr-1"></i>
                        Status changes are logged for audit purposes.
                    </p>
                </div>
            </div>
            <div class="flex items-center justify-end gap-3 p-6 border-t dark:border-gray-700">
                <button type="button" onclick="closeStatusModal()" 
                        class="px-6 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Update Status
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
}

function closeRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
}

function openStatusModal() {
    document.getElementById('statusModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusModal').classList.add('hidden');
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.innerHTML = '<i class="fas fa-check mr-2"></i>Copied to clipboard!';
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }).catch(function(err) {
        alert('Failed to copy to clipboard');
        console.error('Copy failed:', err);
    });
}

// Close modals on outside click
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('refundModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeRefundModal();
        }
    });
    
    document.getElementById('statusModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeStatusModal();
        }
    });
});
</script>
@endpush
@endsection