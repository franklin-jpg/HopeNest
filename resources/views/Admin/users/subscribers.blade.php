@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900">
    <div class="container-fluid px-4 py-8 max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <div class="flex items-center">
                    <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-lg -mr-4">
                        <i class="fas fa-envelope-open text-3xl text-white"></i>
                    </div>
                    <div class="ml-4">
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                            Newsletter Subscribers
                        </h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Manage your email subscribers and communication list
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Users
                </a>
                <button onclick="window.print()" 
                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-print mr-2"></i>
                    Print List
                </button>
            </div>
        </div>

        <!-- Subscriber Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Total Subscribers -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total Subscribers</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['total_subscribers']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-envelope text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Active Subscribers -->
            <div class="group relative bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl shadow-sm hover:shadow-lg border border-emerald-200/50 dark:border-emerald-800/50 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">Active</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['active_subscribers']) }}
                        </p>
                        <p class="text-sm text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ number_format(($stats['active_subscribers'] / max($stats['total_subscribers'], 1) * 100), 1) }}%
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-check-circle text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Unsubscribed -->
            <div class="group relative bg-gradient-to-br from-red-50 to-rose-100 dark:from-red-900/20 dark:to-rose-900/20 rounded-2xl shadow-sm hover:shadow-lg border border-red-200/50 dark:border-red-800/50 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-red-700 dark:text-red-400 uppercase tracking-wide">Unsubscribed</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['unsubscribed']) }}
                        </p>
                        <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                            {{ number_format(($stats['unsubscribed'] / max($stats['total_subscribers'], 1) * 100), 1) }}%
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-times-circle text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- New This Month -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">New This Month</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['this_month']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar-plus text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('admin.users.index') }}" 
                   class="group inline-flex items-center px-6 py-4 text-sm font-medium border-b-2 transition-all duration-300 {{ request()->routeIs('admin.users.index') ? 'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-users mr-2"></i>
                    All Users
                </a>
                <a href="{{ route('admin.users.donors') }}" 
                   class="group inline-flex items-center px-6 py-4 text-sm font-medium border-b-2 transition-all duration-300 {{ request()->routeIs('admin.users.donors') ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-hand-holding-heart mr-2"></i>
                    Donors
                </a>
                <a href="{{ route('admin.users.subscribers') }}" 
                   class="group inline-flex items-center px-6 py-4 text-sm font-medium border-b-2 transition-all duration-300 {{ request()->routeIs('admin.users.subscribers') ? 'border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }}">
                    <i class="fas fa-envelope mr-2"></i>
                    Subscribers
                </a>
            </nav>
        </div>

        <!-- Subscribers Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-list mr-3 text-indigo-600 dark:text-indigo-400"></i>
                    Subscribers List
                </h3>
            </div>
            
            <div class="p-6">
                <!-- Filters -->
                <form method="GET" class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Status</label>
                            <select name="status" 
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 text-gray-900 dark:text-gray-100">
                                <option value="">All Subscribers</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="unsubscribed" {{ request('status') == 'unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                            </select>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Search Subscribers</label>
                            <input type="text" name="search" 
                                   class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" 
                                   placeholder="Search by name or email..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-300">
                                <i class="fas fa-search mr-2"></i>Filter Subscribers
                            </button>
                        </div>
                    </div>
                    
                    <!-- Clear Filters -->
                    @if(request()->filled(['status', 'search']))
                    <div class="mt-6">
                        <a href="{{ route('admin.users.subscribers') }}" 
                           class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>
                            Clear All Filters
                        </a>
                    </div>
                    @endif
                </form>

                <!-- Subscribers Table -->
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                    <span class="flex items-center">
                                        <i class="fas fa-hashtag mr-2 text-indigo-500"></i># 
                                    </span>
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Subscriber</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Subscribed</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($subscribers as $index => $subscriber)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                        {{ ($subscribers->currentPage() - 1) * $subscribers->perPage() + $loop->iteration }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                            {{ $subscriber->name ? strtoupper(substr($subscriber->name, 0, 2)) : 'NS' }}
                                        </div>
                                        <div class="ml-4 flex-1 min-w-0">
                                            <div class="text-sm font-bold text-gray-900 dark:text-white truncate max-w-md">
                                                {{ $subscriber->name ?? 'No Name' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                <i class="fas fa-globe mr-1"></i>
                                                {{ $subscriber->ip_address ?? 'Unknown' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100 font-mono bg-gray-50 dark:bg-gray-800 px-4 py-2 rounded-xl truncate max-w-sm">
                                        {{ $subscriber->email }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($subscriber->unsubscribed_at)
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-100 to-rose-100 text-red-800 text-sm font-medium rounded-xl dark:bg-red-900/30 dark:text-red-400">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Unsubscribed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-800 text-sm font-medium rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            Active
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    {{ \Carbon\Carbon::parse($subscriber->subscribed_at)->format('M d, Y') }}<br>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($subscriber->subscribed_at)->diffForHumans() }}
                                    </span>
                                    @if($subscriber->unsubscribed_at)
                                        <br>
                                        <span class="text-xs text-red-500 dark:text-red-400">
                                            <i class="fas fa-clock mr-1"></i>
                                            Unsub: {{ \Carbon\Carbon::parse($subscriber->unsubscribed_at)->format('M d, Y') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <button onclick="copyEmail('{{ $subscriber->email }}')" 
                                            class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-200 text-xs"
                                            title="Copy Email">
                                        <i class="fas fa-copy mr-1"></i>Copy
                                    </button>
                                    @if($subscriber->unsubscribed_at)
                                    <a href="#" 
                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-xl shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-0.5 transition-all duration-200 text-xs"
                                       title="Resubscribe">
                                        <i class="fas fa-redo mr-1"></i>Resubscribe
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="p-8 bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-2xl shadow-lg">
                                            <i class="fas fa-envelope-open-text text-8xl text-indigo-500 mb-6"></i>
                                        </div>
                                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-4">No subscribers found</p>
                                        <p class="text-gray-500 dark:text-gray-400 mt-2 max-w-2xl mx-auto">
                                            @if(request('status') || request('search'))
                                                Try adjusting your filters to see subscribers
                                            @else
                                                No one has subscribed to your newsletter yet
                                            @endif
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($subscribers->hasPages())
                <div class="px-6 py-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-center">
                        {{ $subscribers->appends(request()->query())->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyEmail(email) {
    navigator.clipboard.writeText(email).then(function() {
        // Show success toast
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-lg z-50 flex items-center animate-bounce';
        toast.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i>
            <span>Email copied to clipboard!</span>
        `;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    });
}
</script>

<style>
@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}
</style>
@endsection