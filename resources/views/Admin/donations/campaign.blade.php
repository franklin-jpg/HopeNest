@extends('layouts.admin')

@section('title', 'Donations for ' . $campaign->title)

@section('content')
<div class="container-fluid py-4">
    <!-- Header with Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.campaigns.show', $campaign) }}"
               class="btn btn-outline-secondary dark:btn-outline-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 btn-sm mb-2 transition">
                <i class="fas fa-arrow-left me-1"></i> Back to Campaign
            </a>
            <h2 class="mb-0 text-gray-900 dark:text-white">Donations for {{ $campaign->title }}</h2>
            <p class="text-muted dark:text-gray-400 mb-0">Campaign ID: #{{ $campaign->id }}</p>
        </div>
        <div>
            <a href="{{ route('admin.donations.campaign', ['campaign' => $campaign->id, 'export' => 'csv']) }}"
               class="btn btn-success hover:bg-green-600 transition">
                <i class="fas fa-download me-1"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
      @php
    $statsCards = [
        ['label' => 'Total Donations', 'value' => $stats['total'], 'icon' => 'fa-donate', 'color' => 'blue'],
        ['label' => 'Successful', 'value' => $stats['successful'], 'icon' => 'fa-check-circle', 'color' => 'green'],
        ['label' => 'Pending', 'value' => $stats['pending'], 'icon' => 'fa-clock', 'color' => 'yellow'],
        ['label' => 'Failed', 'value' => $stats['failed'], 'icon' => 'fa-times-circle', 'color' => 'red'],
        ['label' => 'Total Raised', 'value' => '₦' . number_format($stats['total_amount'], 2), 'icon' => 'fa-money-bill-wave', 'color' => 'indigo'],
        ['label' => 'Donors', 'value' => $stats['donors_count'], 'icon' => 'fa-users', 'color' => 'purple'],
    ];
@endphp

        @foreach($statsCards as $card)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 transition-all">
                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                {{ $card['label'] }}
                            </p>
                            <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                                {{ $card['value'] }}
                            </p>
                        </div>
                        <div class="p-3 rounded-full bg-{{ $card['color'] }}-100 dark:bg-{{ $card['color'] }}-900/30 text-{{ $card['color'] }}-600 dark:text-{{ $card['color'] }}-400">
                            <i class="fas {{ $card['icon'] }} text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filters Card -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6 transition-all">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.donations.campaign', $campaign) }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                    <input type="text"
                           name="search"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                           placeholder="Name, email, receipt..."
                           value="{{ request('search') }}">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 transition">
                        <option value="">All Statuses</option>
                        <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <!-- Amount Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Min Amount</label>
                    <input type="number" name="min_amount" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                           placeholder="0.00"
                           value="{{ request('min_amount') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Amount</label>
                    <input type="number" name="max_amount" step="0.01"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                           placeholder="0.00"
                           value="{{ request('max_amount') }}">
                </div>

                <!-- Date Range -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                    <input type="date" name="date_from"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                           value="{{ request('date_from') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                    <input type="date" name="date_to"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white"
                           value="{{ request('date_to') }}">
                </div>

                <!-- Buttons -->
                <div class="lg:col-span-2 flex items-end gap-3">
                    <button type="submit"
                            class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition">
                        <i class="fas fa-filter me-2"></i> Apply Filters
                    </button>
                    <a href="{{ route('admin.donations.campaign', $campaign) }}"
                       class="px-5 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-medium rounded-lg transition">
                        <i class="fas fa-redo me-2"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Donations Table -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden transition-all">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">
                Donation Records ({{ $donations->total() }})
            </h5>
        </div>

        <div class="overflow-x-auto">
            @if($donations->count() > 0)
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/70">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Receipt #</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Donor</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($donations as $donation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('admin.donations.show', $donation) }}"
                                       class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                        {{ $donation->receipt_number ?? 'N/A' }}
                                    </a>
                                </td>
                                <td class="px-6 py-4">
                                    @if($donation->is_anonymous)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                            Anonymous
                                        </span>
                                    @else
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $donation->donor_name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $donation->donor_email }}</div>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-lg font-bold text-blue-600 dark:text-blue-400">
                                        {{ $donation->formatted_amount }}
                                    </div>
                                    @if($donation->processing_fee > 0)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Fee: {{ $donation->getCurrencySymbol() }}{{ number_format($donation->processing_fee, 2) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                        {{ ucfirst($donation->payment_method ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($donation->status)
                                        @case('successful')
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">Successful</span>
                                            @break
                                        @case('pending')
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300">Pending</span>
                                            @break
                                        @case('failed')
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">Failed</span>
                                            @break
                                        @case('refunded')
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Refunded</span>
                                            @break
                                        @default
                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                                {{ ucfirst($donation->status) }}
                                            </span>
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    <div>{{ $donation->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-500">{{ $donation->created_at->format('g:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex gap-2">
                                        <a href="{{ route('admin.donations.show', $donation) }}"
                                           class="p-2 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @if($donation->status === 'successful' && !$donation->is_anonymous)
                                            <button type="button"
                                                    onclick="resendReceipt({{ $donation->id }})"
                                                    class="p-2 text-green-600 hover:bg-green-100 dark:hover:bg-green-900/30 rounded-lg transition"
                                                    title="Resend Receipt">
                                                <i class="fas fa-receipt"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-16">
                    <i class="fas fa-donate fa-4x text-gray-300 dark:text-gray-600 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400 text-lg">No donations found for this campaign.</p>
                </div>
            @endif
        </div>

        @if($donations->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                {{ $donations->onEachSide(2)->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function resendReceipt(donationId) {
    if (!confirm('Are you sure you want to resend the receipt for this donation?')) {
        return;
    }

    fetch(`/admin/donations/${donationId}/resend-receipt`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.success ? data.message : 'Error: ' + data.message);
    })
    .catch(() => {
        alert('Failed to resend receipt. Please try again.');
    });
}
</script>
@endpush
@endsection