@extends('layouts.user')

@section('title', 'My Donations')

@section('content')
<main class="flex-1 overflow-y-auto p-6 bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">My Donations</h1>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">View all your contributions to HopeNest</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                        <i class="fas fa-heart mr-2"></i>
                        {{ auth()->user()->donation()->where('status', 'successful')->count() }} Total Donations
                    </span>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">All Status</option>
                        <option value="successful" {{ request('status') == 'successful' ? 'selected' : '' }}>Successful</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Campaign</label>
                    <select name="campaign" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                        <option value="">All Campaigns</option>
                        @foreach($campaigns as $id => $title)
                            <option value="{{ $id }}" {{ request('campaign') == $id ? 'selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">From</label>
                    <input type="date" name="from" value="{{ request('from') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To</label>
                    <input type="date" name="to" value="{{ request('to') }}" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                </div>
            </div>
            <div class="mt-4 flex gap-3">
                <button type="submit" class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition">
                    <i class="fas fa-filter mr-2"></i> Apply Filters
                </button>
                <a href="{{ route('user.donations.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Clear
                </a>
            </div>
        </form>

        <!-- Donations Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Campaign</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($donations as $donation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $donation->paid_at?->format('d M Y') ?? '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded flex items-center justify-center">
                                            <i class="fas fa-heart text-primary"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $donation->campaign?->title ?? 'Deleted Campaign' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-green-600 dark:text-green-400">
                                    ₦{{ number_format($donation->amount_in_base_currency ?? $donation->total_amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        {{ $donation->status === 'successful' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' :
                                           ($donation->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' :
                                           'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('user.donations.receipt', $donation) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary/10 text-primary hover:bg-primary hover:text-white rounded-lg transition-all duration-200 text-xs font-medium group"
                                           title="View Receipt">
                                            <i class="fas fa-eye group-hover:scale-110 transition-transform"></i>
                                            <span class="hidden sm:inline">View</span>
                                        </a>
                                        <a href="{{ route('user.donations.pdf', $donation) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 rounded-lg transition-all duration-200 text-xs font-medium group"
                                           title="Download PDF">
                                            <i class="fas fa-download group-hover:scale-110 transition-transform"></i>
                                            <span class="hidden sm:inline">PDF</span>
                                        </a>
                                        <button onclick="alert('Tax certificate request coming soon!')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 hover:bg-purple-600 hover:text-white dark:hover:bg-purple-600 rounded-lg transition-all duration-200 text-xs font-medium group"
                                                title="Tax Certificate">
                                            <i class="fas fa-certificate group-hover:scale-110 transition-transform"></i>
                                            <span class="hidden sm:inline">Tax</span>
                                        </button>
                                        <button onclick="shareDonation('{{ $donation->id }}')"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 rounded-lg transition-all duration-200 text-xs font-medium group"
                                                title="Share Donation">
                                            <i class="fas fa-share-alt group-hover:scale-110 transition-transform"></i>
                                            <span class="hidden sm:inline">Share</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-heart-broken text-6xl mb-4 block opacity-50"></i>
                                    <p class="text-xl">No donations yet</p>
                                    <a href="{{ route('all.campaigns') }}">
                                        <button class=" bg-orange-500 border-none dark:text-gray-200  p-2 rounded-2xl hover:active:bg-orange-400">
                                            Click to Donate
                                        </button>
                                    </a>
                                    <p class="mt-2">Your generosity will appear here when you make a donation.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($donations->hasPages())
            <div class="p-4 border-t dark:border-gray-700">
                {{ $donations->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>
</main>

<script>
function shareDonation(id) {
    const text = "I just supported HopeNest! Join me in making a difference.";
    const url = "{{ url('/') }}";
    if (navigator.share) {
        navigator.share({ title: 'My Donation to HopeNest', text, url });
    } else {
        prompt('Copy this link to share:', url + '/donations/' + id);
    }
}
</script>
@endsection