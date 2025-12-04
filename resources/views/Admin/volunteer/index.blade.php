@extends('layouts.admin')

@section('title', 'Volunteer Management')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Volunteer Management</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage applications, assignments, and volunteer activity</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.volunteers.export', request()->query()) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-green-600 hover:to-green-700 transition-all duration-300">
                <i class="fas fa-download"></i>
                Export CSV
            </a>
            <button type="button" 
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300"
                    data-bs-toggle="modal" data-bs-target="#bulkEmailModal">
                <i class="fas fa-envelope"></i>
                Send Bulk Email
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Pending -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-yellow-50 dark:border-yellow-900/30 hover:shadow-xl hover:border-yellow-200 dark:hover:border-yellow-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-yellow-700 dark:text-yellow-400 uppercase tracking-wide">Pending Applications</p>
                    <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">
                        {{ $volunteers->where('status', 'pending')->count() }}
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Approved -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-green-50 dark:border-green-900/30 hover:shadow-xl hover:border-green-200 dark:hover:border-green-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-700 dark:text-green-400 uppercase tracking-wide">Approved Volunteers</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                        {{ $volunteers->where('status', 'approved')->count() }}
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Rejected -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-red-50 dark:border-red-900/30 hover:shadow-xl hover:border-red-200 dark:hover:border-red-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-red-700 dark:text-red-400 uppercase tracking-wide">Rejected</p>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400 mt-1">
                        {{ $volunteers->where('status', 'rejected')->count() }}
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-red-500 to-red-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-times-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-blue-50 dark:border-blue-900/30 hover:shadow-xl hover:border-blue-200 dark:hover:border-blue-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wide">Total Volunteers</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                        {{ $volunteers->total() }}
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.volunteers.index') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" 
                           name="search" 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200" 
                           placeholder="Search by name or email..." 
                           value="{{ request('search') }}">
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.volunteers.index') }}" 
                       class="px-6 py-3 bg-gray-500/20 dark:bg-gray-500/30 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-500/30 dark:hover:bg-gray-500/50 transition-all duration-300">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Volunteers Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900/30 dark:to-gray-800/30">
            <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                <a href="{{ route('admin.volunteers.index') }}" 
                   class="{{ !request('status') ? 'border-orange-500 text-orange-600 dark:text-orange-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200">
                    All Volunteers
                </a>
                <a href="{{ route('admin.volunteers.pending') }}" 
                   class="{{ request('status') == 'pending' ? 'border-orange-500 text-orange-600 dark:text-orange-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} inline-flex items-center whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200">
                    Pending 
                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-500 to-orange-500 text-white shadow-sm">
                        {{ \App\Models\Volunteer::pending()->count() }}
                    </span>
                </a>
            </nav>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applied</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaigns</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($volunteers as $volunteer)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.volunteers.show', $volunteer) }}" class="group inline-flex items-center text-sm font-medium text-gray-900 dark:text-white hover:text-orange-600 dark:hover:text-orange-400">
                                <img class="h-10 w-10 rounded-full object-cover mr-4 group-hover:ring-2 group-hover:ring-orange-500/20" 
                                     src="{{ $volunteer->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($volunteer->user->name) . '&color=ff9500&background=f97316' }}" 
                                     alt="{{ $volunteer->user->name }}">
                                <span>{{ $volunteer->user->name }}</span>
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $volunteer->user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                {{ $volunteer->phone ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php $status = $volunteer->status @endphp
                            @if($status === 'pending')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-sm">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            @elseif($status === 'approved')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-green-400 to-green-600 text-white shadow-sm">
                                    <i class="fas fa-check mr-1"></i>Approved
                                </span>
                            @elseif($status === 'rejected')
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-red-400 to-red-600 text-white shadow-sm">
                                    <i class="fas fa-times mr-1"></i>Rejected
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-gray-400 to-gray-600 text-white shadow-sm">
                                    <i class="fas fa-ban mr-1"></i>Suspended
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $volunteer->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                            <span class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-sm">
                                {{ number_format($volunteer->hours()->where('status', 'approved')->sum('hours'), 1) }}h
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm font-medium">
                                {{ $volunteer->campaigns()->count() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.volunteers.show', $volunteer) }}" 
                                   class="inline-flex items-center justify-center w-10 h-10 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800/50 rounded-xl transition-all duration-200 hover:scale-105"
                                   title="View Details">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                @if($volunteer->status == 'pending')
                                    <button type="button" 
                                            class="inline-flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 rounded-xl transition-all duration-200 hover:scale-105"
                                            data-bs-toggle="modal" data-bs-target="#approveModal{{ $volunteer->id }}" title="Approve">
                                        <i class="fas fa-check text-sm"></i>
                                    </button>
                                    <button type="button" 
                                            class="inline-flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800/50 rounded-xl transition-all duration-200 hover:scale-105"
                                            data-bs-toggle="modal" data-bs-target="#rejectModal{{ $volunteer->id }}" title="Reject">
                                        <i class="fas fa-times text-sm"></i>
                                    </button>
                                @elseif($volunteer->status == 'approved')
                                    <button type="button" 
                                            class="inline-flex items-center justify-center w-10 h-10 bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400 hover:bg-orange-200 dark:hover:bg-orange-800/50 rounded-xl transition-all duration-200 hover:scale-105"
                                            data-bs-toggle="modal" data-bs-target="#assignModal{{ $volunteer->id }}" title="Assign Campaign">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                    <form action="{{ route('admin.volunteers.suspend', $volunteer) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-800/50 rounded-xl transition-all duration-200 hover:scale-105"
                                                title="Suspend"
                                                onclick="return confirm('Are you sure you want to suspend this volunteer?')">
                                            <i class="fas fa-ban text-sm"></i>
                                        </button>
                                    </form>
                                @elseif($volunteer->status == 'suspended')
                                    <form action="{{ route('admin.volunteers.reactivate', $volunteer) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 rounded-xl transition-all duration-200 hover:scale-105"
                                                title="Reactivate"
                                                onclick="return confirm('Are you sure you want to reactivate this volunteer?')">
                                            <i class="fas fa-check-circle text-sm"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-users text-3xl text-gray-400 dark:text-gray-500"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">No volunteers found</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Try adjusting your search or filter criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-6 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700">
            {{ $volunteers->links() }}
        </div>
    </div>
</div>

{{-- Modals Section --}}
<div class="mt-8 space-y-0">
    {{-- Approve Modal --}}
    @foreach($volunteers as $volunteer)
    @if($volunteer->status == 'pending')
    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal{{ $volunteer->id }}" tabindex="-1" aria-labelledby="approveModalLabel{{ $volunteer->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white dark:bg-gray-800 shadow-2xl rounded-2xl border-0">
                <form action="{{ route('admin.volunteers.approve', $volunteer) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-header px-8 pt-8 pb-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50/50 to-emerald-50/50 dark:from-green-950/10 dark:to-emerald-950/10">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl text-white shadow-lg">
                                <i class="fas fa-check-circle text-2xl"></i>
                            </div>
                            <div>
                                <h5 class="modal-title text-2xl font-bold text-gray-900 dark:text-white" id="approveModalLabel{{ $volunteer->id }}">
                                    Approve Volunteer
                                </h5>
                                <p class="text-sm text-green-700 dark:text-green-400 font-medium mt-1">
                                    {{ $volunteer->user->name }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="ml-auto text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body px-8 py-8">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-950/20 dark:to-emerald-950/20 rounded-2xl p-6 border-l-4 border-green-500">
                            <p class="text-gray-700 dark:text-gray-300 text-lg mb-6">
                                <i class="fas fa-info-circle text-green-500 mr-3"></i>
                                Are you sure you want to approve this volunteer? They will receive a confirmation email and gain access to all volunteer features.
                            </p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                                <div class="bg-white dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                                    <h6 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <i class="fas fa-user text-orange-500 mr-2"></i>Personal Info
                                    </h6>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $volunteer->user->email }}</p>
                                    @if($volunteer->phone)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $volunteer->phone }}</p>
                                    @endif
                                </div>
                                <div class="bg-white dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                                    <h6 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                        <i class="fas fa-calendar text-blue-500 mr-2"></i>Applied
                                    </h6>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $volunteer->created_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $volunteer->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="mb-8">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    Approval Notes (Optional)
                                </label>
                                <textarea name="notes" 
                                          class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-green-500/20 focus:border-green-500 transition-all duration-300 resize-none" 
                                          rows="4" 
                                          placeholder="Add any special notes or instructions for this volunteer..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer px-8 py-8 bg-gradient-to-r from-green-50/80 to-emerald-50/80 dark:from-green-950/10 dark:to-emerald-950/10 border-t border-gray-200/50 dark:border-gray-700/50 rounded-b-2xl">
                        <button type="button" 
                                class="px-8 py-3 mb-4 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800/80 hover:bg-gray-50 dark:hover:bg-gray-700/60 border-2 border-gray-300 dark:border-gray-600 font-semibold rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 mr-auto" 
                                data-bs-dismiss="modal">
                            <i class="fas fa-times mr-2 "></i>Cancel
                        </button>
                        <button type="submit" 
                                class="px-10 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:from-green-600 hover:to-emerald-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                            <i class="fas fa-check-circle mr-3"></i>
                            Approve Volunteer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal{{ $volunteer->id }}" tabindex="-1" aria-labelledby="rejectModalLabel{{ $volunteer->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white dark:bg-gray-800 shadow-2xl rounded-2xl border-0">
                <form action="{{ route('admin.volunteers.reject', $volunteer) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-header px-8 pt-8 pb-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-red-50/50 to-rose-50/50 dark:from-red-950/10 dark:to-rose-950/10">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl text-white shadow-lg">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </div>
                            <div>
                                <h5 class="modal-title text-2xl font-bold text-gray-900 dark:text-white" id="rejectModalLabel{{ $volunteer->id }}">
                                    Reject Volunteer Application
                                </h5>
                                <p class="text-sm text-red-700 dark:text-red-400 font-medium mt-1">
                                    {{ $volunteer->user->name }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="ml-auto text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body px-8 py-8">
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-950/20 dark:to-rose-950/20 rounded-2xl p-6 border-l-4 border-red-500 mb-8">
                            <div class="flex items-start gap-4 mb-6">
                                <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-full flex-shrink-0 mt-1">
                                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                                </div>
                                <div>
                                    <h6 class="font-semibold text-gray-900 dark:text-white text-lg">Rejection Notice</h6>
                                    <p class="text-gray-700 dark:text-gray-300 mt-2">
                                        This volunteer will receive an email with the rejection reason you provide below. 
                                        Please be specific and professional in your response.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-white dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                                <h6 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-user text-orange-500 mr-2"></i>Applicant
                                </h6>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $volunteer->user->email }}</p>
                                @if($volunteer->phone)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $volunteer->phone }}</p>
                                @endif
                            </div>
                            <div class="bg-white dark:bg-gray-900/50 rounded-xl p-6 border border-gray-200/50 dark:border-gray-700/50">
                                <h6 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <i class="fas fa-calendar text-blue-500 mr-2"></i>Applied
                                </h6>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $volunteer->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $volunteer->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <span class="text-red-500 mr-2">*</span>Rejection Reason
                            </label>
                            <textarea name="rejection_reason" 
                                      class="w-full px-5 py-4 border-2 border-red-200/50 dark:border-red-800/50 rounded-2xl bg-gradient-to-r from-red-50/30 to-rose-50/30 dark:from-red-950/10 dark:to-rose-950/10 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-red-500/20 focus:border-red-500 transition-all duration-300 resize-none" 
                                      rows="6" 
                                      placeholder="Please explain why this application is being rejected (e.g., 'Insufficient availability', 'Skills don't match current needs', etc.)..."
                                      required></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                                This reason will be sent directly to the applicant via email
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer px-8 py-8 bg-gradient-to-r from-red-50/80 to-rose-50/80 dark:from-red-950/10 dark:to-rose-950/10 border-t border-gray-200/50 dark:border-gray-700/50 rounded-b-2xl">
                        <button type="button" 
                                class="px-8 py-3 mb-4 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800/80 hover:bg-gray-50 dark:hover:bg-gray-700/60 border-2 border-gray-300 dark:border-gray-600 font-semibold rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 mr-auto" 
                                data-bs-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" 
                                class="px-10 py-3 bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:from-red-600 hover:to-rose-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center"
                                onclick="return confirm('Are you sure? This action cannot be undone.')">
                            <i class="fas fa-times-circle mr-3"></i>
                            Reject Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endforeach
</div>

{{-- 🚀 BULK EMAIL MODAL WITH SUCCESS/ERROR MESSAGES ✅ --}}
<div class="modal fade" id="bulkEmailModal" tabindex="-1" aria-labelledby="bulkEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content bg-white dark:bg-gray-800 shadow-2xl rounded-2xl border-0 max-w-6xl">
            <form action="{{ route('admin.volunteers.bulk-email') }}" method="POST">
                @csrf
                <div class="modal-header px-8 pt-8 pb-6 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-orange-50/50 to-amber-50/50 dark:from-orange-950/10 dark:to-amber-950/10">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gradient-to-br from-orange-500 to-amber-600 rounded-2xl text-white shadow-lg">
                            <i class="fas fa-envelope text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-2xl font-bold text-gray-900 dark:text-white" id="bulkEmailModalLabel">
                                Send Bulk Email to Volunteers
                            </h5>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Reach multiple volunteers with a single message</p>
                        </div>
                    </div>
                    <button type="button" class="ml-auto text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 transition-colors duration-200" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="modal-body px-8 py-8">
                    {{-- 🔥 SUCCESS/ERROR MESSAGES - PERFECTLY INTEGRATED ✅ --}}
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show rounded-2xl bg-red-50 dark:bg-red-950/20 border-l-4 border-red-500 p-6 mb-8 shadow-lg" role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mt-1"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h6 class="font-semibold text-red-800 dark:text-red-300 mb-3">⚠️ Please fix the following errors:</h6>
                                    <ul class="mb-0 space-y-2">
                                        @foreach ($errors->all() as $error)
                                            <li class="flex items-center text-sm text-red-700 dark:text-red-300 bg-red-100 dark:bg-red-900/30 px-4 py-2 rounded-xl">
                                                <i class="fas fa-times-circle text-red-500 mr-3"></i>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            <button type="button" class="btn-close ms-auto mt-1 flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show rounded-2xl bg-green-50 dark:bg-green-950/20 border-l-4 border-green-500 p-6 mb-8 shadow-lg" role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-500 text-2xl mt-1"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h6 class="font-semibold text-green-800 dark:text-green-300 mb-3">✅ Email Sent Successfully!</h6>
                                    <p class="mb-0 text-sm text-green-700 dark:text-green-300 font-medium">{{ session('success') }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close ms-auto mt-1 flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show rounded-2xl bg-red-50 dark:bg-red-950/20 border-l-4 border-red-500 p-6 mb-8 shadow-lg" role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-red-500 text-2xl mt-1"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h6 class="font-semibold text-red-800 dark:text-red-300 mb-3">❌ Error Sending Email</h6>
                                    <p class="mb-0 text-sm text-red-700 dark:text-red-300">{{ session('error') }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close ms-auto mt-1 flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    {{-- 🔥 END SUCCESS/ERROR MESSAGES ✅ --}}

                    <!-- Recipients -->
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-950/20 dark:to-amber-950/20 rounded-2xl p-8 mb-8 border-l-4 border-orange-500">
                        <h6 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <i class="fas fa-users mr-3 text-orange-500"></i>Choose Recipients
                        </h6>
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                    <span class="text-red-500 mr-2">*</span>Recipient Type
                                </label>
                                <select name="recipient_type" class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-300" id="recipientType" required>
                                    <option value="all">👥 All Volunteers</option>
                                    <option value="approved">✅ Approved Volunteers Only</option>
                                    <option value="pending">⏳ Pending Applications</option>
                                    <option value="campaign">🎯 Volunteers in Specific Campaign</option>
                                </select>
                            </div>
                            <div class="lg:col-span-3">
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Subject <span class="text-red-500">*</span></label>
                                <input type="text" 
                                       name="subject" 
                                       class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-300 @error('subject') border-red-500 bg-red-50 dark:bg-red-950/20 @enderror" 
                                       value="{{ old('subject') }}"
                                       placeholder="e.g., Important Update for Volunteers" required>
                                @error('subject')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Campaign Selector -->
                    <div id="campaignSelect" class="mb-8 hidden">
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/20 dark:to-indigo-950/20 rounded-2xl p-6 border-l-4 border-blue-500">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Select Campaign</label>
                            <select name="campaign_id" class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-300 @error('campaign_id') border-red-500 bg-red-50 dark:bg-red-950/20 @enderror">
                                <option value="">Choose a campaign...</option>
                                @foreach(\App\Models\Campaign::all() as $campaign)
                                    <option value="{{ $campaign->id }}" {{ old('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->title }}</option>
                                @endforeach
                            </select>
                            @error('campaign_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Message Editor -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <span class="text-red-500 mr-2">*</span>Message
                        </label>
                        <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900/20 dark:to-gray-800/20 rounded-2xl p-6 border-2 border-gray-200/50 dark:border-gray-700/50 @error('message') border-red-500/50 bg-red-50/20 dark:bg-red-950/10 @enderror">
                            <textarea name="message" 
                                      class="w-full px-6 py-5 border-0 bg-transparent text-gray-900 dark:text-gray-300 focus:ring-0 resize-none min-h-[200px] @error('message') text-red-800 @enderror" 
                                      placeholder="Write your message here...&#10;&#10;Available variables:&#10;{name} - Volunteer's name&#10;{email} - Volunteer's email&#10;{campaign_title} - Campaign name (if applicable)"
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}</p>
                            @enderror
                            <div class="mt-6 pt-6 border-t border-gray-200/50 dark:border-gray-700/50">
                                <p class="text-xs text-gray-500 dark:text-gray-400 flex flex-wrap items-center gap-4">
                                    <span class="inline-flex items-center px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-sm">
                                        <i class="fas fa-magic mr-2"></i>Variables
                                    </span>
                                    <code class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-xs">{name}</code>
                                    <code class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-xs">{email}</code>
                                    <code class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-xs">{campaign_title}</code>
                                </p>
                            </div>
                        </div>
                    </div>

                    @if (session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show rounded-2xl bg-yellow-50 dark:bg-yellow-950/20 border-l-4 border-yellow-500 p-6 mb-8 shadow-lg" role="alert">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mt-1"></i>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="mb-0 text-sm text-yellow-700 dark:text-yellow-300 font-medium">{{ session('warning') }}</p>
                                </div>
                            </div>
                            <button type="button" class="btn-close ms-auto mt-1 flex-shrink-0" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                </div>
                <div class="modal-footer px-8 py-8 bg-gradient-to-r from-orange-50/80 to-amber-50/80 dark:from-orange-950/10 dark:to-amber-950/10 border-t border-gray-200/50 dark:border-gray-700/50 rounded-b-2xl">
                    <button type="button" 
                            class="px-8 py-3 mb-4 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800/80 hover:bg-gray-50 dark:hover:bg-gray-700/60 border-2 border-gray-300 dark:border-gray-600 font-semibold rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 mr-auto" 
                            data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" 
                            class="px-12 py-4 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:from-orange-600 hover:to-amber-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center group">
                        <i class="fas fa-paper-plane mr-3 group-hover:translate-x-1 transition-transform duration-300"></i>
                        Send Email to Selected Volunteers
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Campaign selector toggle
        const recipientType = document.getElementById('recipientType');
        const campaignSelect = document.getElementById('campaignSelect');
        
        if (recipientType && campaignSelect) {
            recipientType.addEventListener('change', function() {
                if (this.value === 'campaign') {
                    campaignSelect.classList.remove('hidden');
                } else {
                    campaignSelect.classList.add('hidden');
                }
            });
            
            // Show campaign selector if already selected from previous submission
            if (recipientType.value === 'campaign') {
                campaignSelect.classList.remove('hidden');
            }
        }

        // Form submission loading state
        const form = document.querySelector('#bulkEmailModal form');
        if (form) {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sending Emails...';
                submitBtn.disabled = true;
            });
        }

        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush
@endsection