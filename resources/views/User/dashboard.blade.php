@extends('layouts.user')

@section('content')
  <main class="flex-1 overflow-y-auto p-6 transition-colors duration-500 bg-gray-50 dark:bg-gray-900">

    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-primary to-secondary rounded-lg shadow-lg p-6 mb-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                <p class="text-white/90">Thank you for being part of our mission to make a difference</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-hands-helping text-6xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">

        <!-- Total Donated (in NGN - base currency) -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/40 p-6 transition-colors duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Total Donated</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        ₦{{ $stats['total_donated'] }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">
                        Across {{ $stats['causes_supported'] }} campaign{{ $stats['causes_supported'] != 1 ? 's' : '' }}
                    </p>
                </div>
                <div class="w-14 h-14 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-2xl text-primary"></i>
                </div>
            </div>
        </div>

        <!-- Donation Streak -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/40 p-6 transition-colors duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Donation Streak</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ $stats['donation_streak'] }} month{{ $stats['donation_streak'] != 1 ? 's' : '' }}
                    </h3>
                    @if($stats['donation_streak'] > 0)
                        <p class="text-orange-600 dark:text-orange-400 text-sm mt-2 flex items-center gap-1">
                            <i class="fas fa-fire"></i> On fire!
                        </p>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Start your streak today!</p>
                    @endif
                </div>
                <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/40 rounded-full flex items-center justify-center">
                    <i class="fas fa-fire text-2xl text-orange-600 dark:text-orange-400"></i>
                </div>
            </div>
        </div>

        <!-- Lives Impacted -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/40 p-6 transition-colors duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Lives Impacted</p>
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ number_format($stats['lives_impacted']) }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">Through your generosity</p>
                </div>
                <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/40 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Donations -->
    <div class="grid lg:grid-cols-3 gap-6 mb-6">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow dark:shadow-gray-900/40 transition-colors duration-500">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">My Recent Donations</h3>
<a href="{{ route('user.donations.index') }}" class="text-primary hover:underline text-sm font-medium">
    View All
</a>            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Campaign</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
    @forelse($stats['recent_donations'] as $donation)
        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors cursor-pointer"
            onclick="window.location='{{ route('user.donations.show', $donation) }}'">
            
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary/10 dark:bg-primary/20 rounded flex items-center justify-center mr-3">
                        <i class="fas fa-heart text-primary"></i>
                    </div>
                    <span class="text-sm text-gray-800 dark:text-gray-200 font-medium">
                        {{ Str::limit($donation->campaign?->title ?? 'Deleted Campaign', 30) }}
                    </span>
                </div>
            </td>
            
            <td class="px-6 py-4 text-sm font-medium text-green-600 dark:text-green-400">
                ₦{{ number_format($donation->amount_in_base_currency, 2) }}
            </td>
            
            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                {{ $donation->paid_at?->format('M d, Y') ?? 'Pending' }}
            </td>
            
            <td class="px-6 py-4">
                <span class="px-2 py-1 text-xs font-medium rounded-full
                    {{ $donation->status === 'successful'
                        ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400'
                        : 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400' }}">
                    {{ ucfirst($donation->status) }}
                </span>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4" class="px-6 py-16 text-center text-gray-500 dark:text-gray-400">
                <i class="fas fa-heart-broken text-5xl mb-4 block opacity-50"></i>
                <p class="text-lg font-medium">No donations yet</p>
                <a href="{{ route('all.campaigns') }}">
                                        <button class=" bg-orange-500 border-none dark:text-gray-200  p-2 rounded-2xl hover:active:bg-orange-400">
                                            Click to Donate
                                        </button>
                                    </a>
                <p class="text-sm mt-2">Your generosity will appear here when you make a donation</p>
            </td>
        </tr>
    @endforelse
</tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions & Achievements (Keep your existing ones below) -->
        <div class="space-y-6">
            <!-- Your existing Quick Actions and Achievements cards go here -->
        </div>
    </div>

</main>
@endsection