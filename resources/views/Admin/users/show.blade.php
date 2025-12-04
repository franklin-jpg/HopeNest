@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-slate-900">
    <div class="container-fluid px-4 py-8 max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-6">
            <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                <div class="flex-shrink-0">
                    <div class="relative">
                        <div class="h-24 w-24 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl shadow-2xl">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                        @if($user->active)
                            <div class="absolute -top-2 -right-2 bg-emerald-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                                <i class="fas fa-check"></i>
                            </div>
                        @else
                            <div class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center shadow-lg">
                                <i class="fas fa-times"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ $user->name }}
                    </h1>
                    <p class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center">
                        <i class="fas fa-envelope mr-2 text-blue-600 dark:text-blue-400"></i>
                        {{ $user->email }}
                    </p>
                    <div class="mt-3 flex flex-wrap gap-4">
                        @php 
                            $roleColor = $user->role == 'admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 
                                        ($user->role == 'volunteer' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 
                                        'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400');
                        @endphp
                        <span class="inline-flex px-4 py-2 rounded-xl font-semibold {{ $roleColor }} border-2 border-current">
                            <i class="fas fa-user-{{ $user->role == 'admin' ? 'shield-alt' : ($user->role == 'volunteer' ? 'hands-helping' : 'user') }} mr-2"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="inline-flex px-4 py-2 rounded-xl font-semibold {{ $user->active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }} border-2 border-current">
                            <i class="fas fa-{{ $user->active ? 'check-circle' : 'times-circle' }} mr-2"></i>
                            {{ $user->active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-slate-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Users
                </a>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Donations -->
            <div class="group relative bg-gradient-to-br from-emerald-50 to-teal-100 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-2xl shadow-sm hover:shadow-lg border border-emerald-200/50 dark:border-emerald-800/50 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">Total Donations</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            ₦{{ number_format($user->donation()->where('status', 'successful')->sum('total_amount'), 0) }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-hand-holding-heart text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Donation Count -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Donations Made</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $user->donation()->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-donate text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Account Age -->
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Member Since</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ $user->created_at->format('M Y') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $user->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <div class="p-3 bg-gradient-to-br from-purple-500 to-pink-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-calendar text-2xl text-white"></i>
                    </div>
                </div>
            </div>

            <!-- Last Login -->
            <div class="group relative bg-gradient-to-br from-indigo-50 to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-2xl shadow-sm hover:shadow-lg border border-indigo-200/50 dark:border-indigo-800/50 p-6 transition-all duration-300 hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-indigo-700 dark:text-indigo-400 uppercase tracking-wide">Last Login</p>
                        @if($user->last_login_at)
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $user->last_login_at->format('M d') }}
                            </p>
                            <p class="text-sm text-indigo-600 dark:text-indigo-400 mt-1">
                                {{ $user->last_login_at->diffForHumans() }}
                            </p>
                        @else
                            <p class="text-3xl font-bold text-gray-500 dark:text-gray-400">Never</p>
                        @endif
                    </div>
                    <div class="p-3 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-sign-in-alt text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Profile Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900/50">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-user-circle mr-3 text-blue-600 dark:text-blue-400"></i>
                            Basic Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600">
                                    {{ $user->name }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600">
                                    {{ $user->email }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600">
                                    {{ $user->phone ?? 'Not provided' }}
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Verified</label>
                                <div class="flex items-center justify-center h-12 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600">
                                    @if($user->email_verified_at)
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-emerald-100 to-teal-100 text-emerald-800 text-sm font-medium rounded-xl dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <i class="fas fa-check-circle mr-2"></i>
                                            {{ $user->email_verified_at->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-100 to-rose-100 text-red-800 text-sm font-medium rounded-xl dark:bg-red-900/30 dark:text-red-400">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Not Verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Volunteer Information (Only for Volunteers) -->
                @if($user->role === 'volunteer')
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-amber-200/50 dark:border-amber-800/50 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-hands-helping mr-3 text-amber-600 dark:text-amber-400"></i>
                            Volunteer Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <div class="flex items-center justify-center h-12">
                                    @php
                                        $volunteerStatus = $user->volunteer->status ?? 'pending';
                                        $statusColor = $volunteerStatus === 'approved' ? 'from-emerald-100 to-teal-100' : 
                                                     ($volunteerStatus === 'rejected' ? 'from-red-100 to-rose-100' : 
                                                     ($volunteerStatus === 'suspended' ? 'from-amber-100 to-orange-100' : 'from-gray-100 to-gray-200'));
                                        $statusTextColor = $volunteerStatus === 'approved' ? 'text-emerald-800' : 
                                                         ($volunteerStatus === 'rejected' ? 'text-red-800' : 
                                                         ($volunteerStatus === 'suspended' ? 'text-amber-800' : 'text-gray-700'));
                                    @endphp
                                    <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r {{ $statusColor }} {{ $statusTextColor }} text-sm font-semibold rounded-xl dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                        <i class="fas fa-circle mr-2"></i>
                                        {{ ucfirst($volunteerStatus) }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                <div class="text-lg font-semibold text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600">
                                    {{ $user->volunteer->phone ?? 'Not provided' }}
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Skills</label>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $user->volunteer->skills ?? 'Not specified' }}</p>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Availability</label>
                                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-600">
                                    <p class="text-sm text-gray-900 dark:text-white">{{ $user->volunteer->availability ?? 'Not specified' }}</p>
                                </div>
                            </div>
                        </div>
                        @if($user->volunteer->motivation)
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Motivation</label>
                            <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 p-6 rounded-xl border border-amber-200/50 dark:border-amber-800/50">
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    {{ $user->volunteer->motivation }}
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Recent Donations -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-donate mr-3 text-emerald-600 dark:text-emerald-400"></i>
                            Recent Donations
                            <span class="ml-auto text-sm font-medium text-gray-600 dark:text-gray-400">
                                ({{ $recentDonations->count() }} of {{ $user->donation()->count() }})
                            </span>
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($recentDonations->count() > 0)
                            <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
                                        <tr>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Campaign</th>
                                            <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($recentDonations as $donation)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                {{ $donation->created_at->format('M d, Y') }}
                                                <br>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $donation->created_at->format('H:i') }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($donation->campaign)
                                                    <div class="text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 transition-colors duration-200 truncate max-w-xs">
                                                        {{ Str::limit($donation->campaign->title, 40) }}
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-500 dark:text-gray-400">Campaign Deleted</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <div class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl border border-emerald-200/50 dark:border-emerald-800/50 text-emerald-700 dark:text-emerald-300 font-semibold">
                                                    <i class="fas fa-naira-sign mr-1"></i>
                                                    {{ number_format($donation->total_amount, 2) }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold {{ $donation->status == 'successful' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : ($donation->status == 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400') }}">
                                                    {{ ucfirst($donation->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                          
                        @else
                            <div class="text-center py-12">
                                <div class="mx-auto h-24 w-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-hand-holding-heart text-4xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <p class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No donations yet</p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This user hasn't made any donations</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column - Activity -->
            <div class="space-y-6">
                <!-- Account Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-chart-line mr-3 text-purple-600 dark:text-purple-400"></i>
                            Account Activity
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Joined</span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                            @if($user->email_verified_at)
                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 rounded-xl">
                                <span class="text-sm font-medium text-emerald-700 dark:text-emerald-400 flex items-center">
                                    <i class="fas fa-check-circle mr-2"></i>Email Verified
                                </span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->email_verified_at->format('M d, Y') }}</span>
                            </div>
                            @endif
                            @if($user->role === 'volunteer' && $user->volunteer)
                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl">
                                <span class="text-sm font-medium text-amber-700 dark:text-amber-400 flex items-center">
                                    <i class="fas fa-hands-helping mr-2"></i>Volunteer Application
                                </span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $user->volunteer->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            @endif
                            @if($user->last_login_at)
                            <div class="flex justify-between items-center py-3 px-4 bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-xl">
                                <span class="text-sm font-medium text-indigo-700 dark:text-indigo-400 flex items-center">
                                    <i class="fas fa-sign-in-alt mr-2"></i>Last Login
                                </span>
                                <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->last_login_at->format('M d, Y H:i') }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center py-3 px-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Status</span>
                                <span class="{{ $user->active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }} inline-flex px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ $user->active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection