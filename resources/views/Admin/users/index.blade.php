@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900">
    <div class="container-fluid px-4 py-8 max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                    User Management
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Manage all users, donors, and subscribers
                </p>
            </div>
            <a href="{{ route('admin.users.export') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border border-transparent hover:border-emerald-600/30">
                <i class="fas fa-download mr-2"></i>
                Export Users
            </a>
        </div>

        <!-- Statistics Cards - Grid Layout -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total Users</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['total_users']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Total Donors -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total Donors</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['total_donors']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-hand-holding-heart text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- New Donors This Month -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">New Donors This Month</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['new_donors_this_month']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-plus text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Active Users</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['active_users']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-cyan-500 to-blue-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-check text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue & Other Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
            <!-- Inactive Users -->
            <div class="group relative bg-gradient-to-br from-red-50 to-rose-100 dark:from-red-900/20 dark:to-rose-900/20 rounded-2xl shadow-sm hover:shadow-lg border border-red-200/50 dark:border-red-800/50 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-red-700 dark:text-red-400 uppercase tracking-wide">Inactive Users</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['inactive_users']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-user-times text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Newsletter Subscribers -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Newsletter Subscribers</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['newsletter_subscribers']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-envelope text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Total Revenue -->
            <div class="group relative bg-gradient-to-br from-emerald-50 to-green-100 dark:from-emerald-900/20 dark:to-green-900/20 rounded-2xl shadow-sm hover:shadow-lg border border-emerald-200/50 dark:border-emerald-800/50 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">Total Revenue</p>
                        <p class="mt-2 text-3xl   text-gray-900 dark:text-white  tracking-tight leading-none">
                            ₦{{ number_format($stats['total_revenue'], 2) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-dollar-sign text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Total Donations -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Total Donations</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($stats['total_donations']) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-gift text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="{{ route('admin.users.index') }}" 
                   class="group inline-flex items-center dark:text-gray-400 px-6 py-4 text-sm font-medium border-b-2 transition-all duration-300 {{ request()->routeIs('admin.users.index') ? 'border-primary-500 text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20' : 'border-transparent text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600' }}">
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

        <!-- Users Table Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900/50">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-user-cog mr-3 text-blue-600 dark:text-blue-400"></i>
                    All Users
                </h3>
            </div>
            
            <div class="p-6">
                <!-- Filters -->
                <form method="GET" class="mb-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <input type="text" name="search" 
                                   class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400" 
                                   placeholder="Search by name or email..." 
                                   value="{{ request('search') }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                            <select name="role" 
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-300 text-gray-900 dark:text-gray-100">
                                <option value="">All Roles</option>
                                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="volunteer" {{ request('role') == 'volunteer' ? 'selected' : '' }}>Volunteer</option>
                                <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status" 
                                    class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl shadow-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 text-gray-900 dark:text-gray-100">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-700 hover:to-indigo-700 transform hover:-translate-y-0.5 transition-all duration-300">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Users Table -->
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Donations</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Registered</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($users as $user)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white font-semibold">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $roleColor = $user->role == 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : ($user->role == 'volunteer' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300') @endphp
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $roleColor }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php $statusColor = $user->active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' @endphp
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                        {{ $user->active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center">
                                        <i class="fas fa-donate text-emerald-500 mr-2"></i>
                                       {{ $user->donation_count ?? 0 }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 rounded-xl hover:bg-blue-200 dark:hover:bg-blue-800/50 transition-all duration-200" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-2 {{ $user->active ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 hover:bg-amber-200 dark:hover:bg-amber-800/50' : 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400 hover:bg-emerald-200 dark:hover:bg-emerald-800/50' }} rounded-xl transition-all duration-200" 
                                                title="{{ $user->active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $user->active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-users text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">No users found</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Try adjusting your search or filter criteria</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($users->hasPages())
                <div class="px-6 py-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-center">
                        {{ $users->links() }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Latest Donations Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-donate mr-3 text-emerald-600 dark:text-emerald-400"></i>
                    Latest Donations
                </h3>
            </div>
            
            <div class="p-6">
                <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Donor</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Campaign</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($latestDonations as $donation)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-emerald-400 rounded-full mr-3"></div>
                                        {{ $donation->created_at->format('M d, Y') }}
                                        <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">at {{ $donation->created_at->format('H:i') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($donation->user)
                                        <a href="{{ route('admin.users.show', $donation->user) }}" 
                                           class="font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300 transition-colors duration-200">
                                            {{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }}
                                        </a>
                                    @else
                                        <span class="text-gray-900 dark:text-gray-100 font-medium">
                                            {{ $donation->is_anonymous ? 'Anonymous' : $donation->donor_name }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($donation->campaign)
                                        <a href="{{ route('admin.campaigns.show', $donation->campaign) }}" 
                                           class="font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors duration-200 truncate max-w-xs block">
                                            {{ $donation->campaign->title }}
                                        </a>
                                    @else
                                        <span class="text-gray-500 dark:text-gray-400 font-medium">Campaign Deleted</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-semibold">
                                    <div class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl border border-emerald-200/50 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300">
                                        <i class="fas fa-naira-sign mr-1"></i>
                                        {{ number_format($donation->total_amount, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $donation->status == 'successful' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                        {{ ucfirst($donation->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="fas fa-donate text-6xl text-gray-300 dark:text-gray-600 mb-4"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">No donations found</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Recent donations will appear here</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection