@extends('layouts.admin')

@section('title', 'Failed Donations')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                <i class="fas fa-exclamation-triangle text-red-600"></i>
                Failed Donations
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Track and manage failed donation attempts</p>
        </div>
        <a href="{{ route('admin.donations.index') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition">
            <i class="fas fa-arrow-left"></i> All Donations
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Failed</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['total_failed']) }}</p>
                </div>
                <div class="p-4 bg-red-100 dark:bg-red-900/30 rounded-xl">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Failed Amount</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">₦{{ number_format($stats['failed_amount'], 2) }}</p>
                </div>
                <div class="p-4 bg-orange-100 dark:bg-orange-900/30 rounded-xl">
                    <i class="fas fa-money-bill-wave text-2xl text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Last 24 Hours</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-1">{{ number_format($stats['last_24h']) }}</p>
                    @if($stats['last_24h'] > 0)
                        <span class="inline-flex items-center gap-1 text-sm text-red-600 dark:text-red-400 mt-2">
                            <i class="fas fa-arrow-up"></i> Requires attention
                        </span>
                    @endif
                </div>
                <div class="p-4 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                    <i class="fas fa-clock text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- High Failure Alert -->
    @if($stats['last_24h'] > 5)
        <div class="bg-orange-100 dark:bg-orange-900/30 border border-orange-300 dark:border-orange-700 rounded-xl p-5 mb-8 flex items-start gap-3">
            <i class="fas fa-exclamation-triangle text-2xl text-orange-600 dark:text-orange-400 mt-1"></i>
            <div>
                <strong class="text-orange-800 dark:text-orange-300">High failure rate detected!</strong>
                <p class="text-orange-700 dark:text-orange-400 mt-1">
                    There have been <strong>{{ $stats['last_24h'] }}</strong> failed donations in the last 24 hours.
                    Please review payment gateway settings and check for any issues.
                </p>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
            <i class="fas fa-filter"></i> Filters
        </h3>
        <form method="GET" action="{{ route('admin.donations.failed') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Campaign</label>
                <select name="campaign_id"
                        class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500">
                    <option value="">All Campaigns</option>
                    @foreach($campaigns as $campaign)
                        <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>
                            {{ $campaign->title }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit"
                        class="w-full px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i> Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Failed Donations Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Failed Donations List</h3>
        </div>

        @if($donations->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Receipt</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Donor</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Campaign</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Failed At</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($donations as $donation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition {{ $donation->created_at->isToday() ? 'bg-red-50 dark:bg-red-900/20' : '' }}">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.donations.show', $donation->id) }}"
                                           class="font-mono text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $donation->receipt_number }}
                                        </a>
                                        @if($donation->created_at->isToday())
                                            <span class="px-2 py-1 text-xs font-bold bg-red-600 text-white rounded-full">NEW</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($donation->is_anonymous)
                                        <span class="text-gray-500 dark:text-gray-400 italic">Anonymous Donor</span>
                                    @else
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $donation->donor_name }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $donation->donor_email }}</p>
                                            @if($donation->donor_phone)
                                                <p class="text-sm text-gray-500 dark:text-gray-500">{{ $donation->donor_phone }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.campaigns.show', $donation->campaign_id) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ Str::limit($donation->campaign->title, 35) }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-lg font-bold text-red-600 dark:text-red-400">{{ $donation->formatted_amount }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                        {{ $donation->payment_method ? ucfirst($donation->payment_method) : 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($donation->frequency === 'one-time')
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            One-time
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-800 dark:text-purple-300">
                                            {{ ucfirst($donation->frequency) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $donation->created_at->format('M d, Y') }}</p>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $donation->created_at->format('g:i A') }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">{{ $donation->created_at->diffForHumans() }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.donations.show', $donation->id) }}"
                                           class="text-blue-600 hover:text-blue-700 dark:text-blue-400 p-2" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$donation->is_anonymous)
                                            <form method="POST" action="{{ route('admin.donations.send-retry-link', $donation->id) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-orange-600 hover:text-orange-700 dark:text-orange-400 p-2" title="Send Retry Link">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                            </form>
                                            <a href="mailto:{{ $donation->donor_email }}" class="text-green-600 hover:text-green-700 dark:text-green-400 p-2" title="Email">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        @endif
                                        <button type="button" class="text-green-600 hover:text-green-700 dark:text-green-400 p-2"
                                                data-bs-toggle="modal" data-bs-target="#updateStatusModal{{ $donation->id }}" title="Mark as Successful">
                                            <i class="fas fa-check-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modal remains unchanged (Bootstrap) -->
                            <div class="modal fade" id="updateStatusModal{{ $donation->id }}" tabindex="-1">
                                <!-- ... your existing modal code ... -->
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $donations->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i class="fas fa-check-circle text-6xl text-green-500 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">No Failed Donations</h3>
                <p class="text-gray-600 dark:text-gray-400 mt-2">Great! All donations are processing successfully.</p>
            </div>
        @endif
    </div>

    <!-- Common Failure Reasons -->
    <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
            <i class="fas fa-info-circle text-blue-600"></i> Common Failure Reasons
        </h3>
        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Payment Issues</h4>
                <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                    <li class="flex items-center gap-2"><i class="fas fa-times-circle text-red-500"></i> Insufficient funds</li>
                    <li class="flex items-center gap-2"><i class="fas fa-times-circle text-red-500"></i> Card declined by bank</li>
                    <li class="flex items-center gap-2"><i class="fas fa-times-circle text-red-500"></i> Expired payment method</li>
                    <li class="flex items-center gap-2"><i class="fas fa-times-circle text-red-500"></i> Incorrect card details</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Technical Issues</h4>
                <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                    <li class="flex items-center gap-2"><i class="fas fa-wifi text-orange-500"></i> Network timeout</li>
                    <li class="flex items-center gap-2"><i class="fas fa-server text-orange-500"></i> Payment gateway error</li>
                    <li class="flex items-center gap-2"><i class="fas fa-window-close text-orange-500"></i> Browser/session issues</li>
                    <li class="flex items-center gap-2"><i class="fas fa-shield-alt text-orange-500"></i> 3D Secure failed</li>
                </ul>
            </div>
        </div>
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg">
            <p class="text-blue-800 dark:text-blue-300 flex items-center gap-2">
                <i class="fas fa-lightbulb"></i>
                <strong>Pro Tip:</strong> Send retry links to donors with failed payments — many will complete their donation on the second try!
            </p>
        </div>
    </div>
</div>
@endsection