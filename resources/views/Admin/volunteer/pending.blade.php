@extends('layouts.admin')

@section('title', 'Pending Volunteer Applications')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
        <div class="space-y-2">
            <div class="flex items-center gap-4">
                <div class="p-4 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl text-white shadow-lg">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Pending Applications</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Review and approve new volunteer applications
                    </p>
                </div>
            </div>
            <div class="inline-flex items-center px-6 py-2 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold text-sm shadow-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                {{ $volunteers->total() }} application{{ $volunteers->total() !== 1 ? 's' : '' }} pending review
            </div>
        </div>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.volunteers.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gray-500/20 dark:bg-gray-500/30 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl hover:bg-gray-500/30 dark:hover:bg-gray-500/50 transition-all duration-300">
                <i class="fas fa-arrow-left"></i>
                Back to All
            </a>
            <a href="{{ route('admin.volunteers.export', ['status' => 'pending']) }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-yellow-600 hover:to-orange-700 transition-all duration-300">
                <i class="fas fa-download"></i>
                Export Pending
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="group bg-gradient-to-br from-yellow-50/80 to-amber-50/80 dark:from-yellow-950/20 dark:to-amber-950/20 rounded-2xl p-8 shadow-xl border-2 border-yellow-100/50 dark:border-yellow-900/40 hover:shadow-2xl hover:border-yellow-200/60 dark:hover:border-yellow-800/60 transition-all duration-500 overflow-hidden relative">
            <div class="absolute top-0 left-0 w-32 h-32 bg-gradient-to-br from-yellow-400/20 to-amber-500/20 rounded-full -ml-16 -mt-16 blur-xl animate-pulse"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-2xl text-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-yellow-800 dark:text-yellow-300 uppercase tracking-wide">Pending Applications</p>
                            <p class="text-4xl lg:text-5xl font-extrabold text-yellow-700 dark:text-yellow-400 bg-gradient-to-r from-yellow-600 to-amber-700 bg-clip-text text-transparent leading-tight">
                                {{ $volunteers->total() }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-yellow-700 dark:text-yellow-400 font-medium">
                        <i class="fas fa-hourglass-half text-yellow-500"></i>
                        <span class="bg-yellow-100 dark:bg-yellow-900/40 px-3 py-1 rounded-full text-xs font-semibold">Needs Review</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-gray-50 dark:border-gray-700/50 hover:shadow-2xl hover:border-gray-200 dark:hover:border-gray-600/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Applied Today</p>
                    <p class="text-4xl font-extrabold text-gray-900 dark:text-white mt-2">
                        {{ \App\Models\Volunteer::pending()->whereDate('created_at', today())->count() }}
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-gray-50 dark:border-gray-700/50 hover:shadow-2xl hover:border-gray-200 dark:hover:border-gray-600/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Avg. Wait Time</p>
                    <p class="text-1x1  text-gray-900 dark:text-white mt-2 tracking-tight leading-none">
                        {{ \App\Models\Volunteer::pending()->avg('created_at') ? now()->diffInDays(\App\Models\Volunteer::pending()->first()->created_at) : 0 }}<span class="text-lg">d</span>
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-stopwatch text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="p-8 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-yellow-50/50 to-amber-50/50 dark:from-yellow-950/10 dark:to-amber-950/10">
            <form action="{{ route('admin.volunteers.pending') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-end">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Applied Date</label>
                    <input type="date" 
                           name="date" 
                           class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-yellow-500/20 focus:border-yellow-500 transition-all duration-300" 
                           value="{{ request('date') }}">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Sort By</label>
                    <select name="sort" class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-yellow-500/20 focus:border-yellow-500 transition-all duration-300">
                        <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name A-Z</option>
                    </select>
                </div>
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                            class="flex-1 px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-yellow-600 hover:to-orange-700 transition-all duration-300">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.volunteers.pending') }}" 
                       class="px-8 py-4 bg-gray-500/20 dark:bg-gray-500/30 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl hover:bg-gray-500/30 dark:hover:bg-gray-500/50 transition-all duration-300">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
        <div class="border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-950/20 dark:to-amber-950/20">
            <div class="flex items-center justify-between px-8 py-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                    <i class="fas fa-list mr-3 text-yellow-600"></i>
                    Application List
                </h2>
                <div class="inline-flex items-center px-6 py-3 rounded-full bg-gradient-to-r from-yellow-400 to-orange-500 text-white font-semibold text-sm shadow-lg">
                    <i class="fas fa-users mr-2"></i>
                    {{ $volunteers->total() }} Total
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800/50">
                    <tr>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applicant</th>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Applied</th>
                        <th class="px-8 py-6 text-left text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($volunteers as $volunteer)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 border-b-2 border-gray-100 dark:border-gray-700/50">
                        <td class="px-8 py-8">
                            <div class="flex items-center gap-6">
                                <a href="{{ route('admin.volunteers.show', $volunteer) }}" class="group relative">
                                    <img class="h-16 w-16 rounded-full object-cover ring-4 ring-yellow-100/50 dark:ring-yellow-900/30 group-hover:ring-yellow-200/70 dark:group-hover:ring-yellow-800/50 transition-all duration-300" 
                                         src="{{ $volunteer->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($volunteer->user->name) . '&size=128&color=ff9500&background=f97316' }}" 
                                         alt="{{ $volunteer->user->name }}">
                                    <div class="absolute -top-2 -right-2 bg-gradient-to-br from-yellow-500 to-orange-600 p-2 rounded-full text-white shadow-lg animate-pulse">
                                        <i class="fas fa-clock text-sm"></i>
                                    </div>
                                </a>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ route('admin.volunteers.show', $volunteer) }}" class="block text-lg font-semibold text-gray-900 dark:text-white hover:text-orange-600 dark:hover:text-orange-400 transition-colors duration-200">
                                        {{ $volunteer->user->name }}
                                    </a>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">
                                        {{ $volunteer->user->email }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-8 whitespace-nowrap">
                            <div class="space-y-2">
                                @if($volunteer->phone)
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-blue-500/10 to-indigo-500/10 text-blue-700 dark:text-blue-400 border border-blue-200/50 dark:border-blue-800/50">
                                    <i class="fas fa-phone mr-2"></i>
                                    {{ $volunteer->phone }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-gray-500/10 to-gray-600/10 text-gray-700 dark:text-gray-400 border border-gray-200/50 dark:border-gray-700/50">
                                    <i class="fas fa-phone-slash mr-2"></i>
                                    No Phone
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-8 py-8 whitespace-nowrap">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $volunteer->created_at->format('M d, Y') }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 text-yellow-800 dark:text-yellow-300">
                                        {{ $volunteer->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-yellow-500 to-orange-600 h-2 rounded-full" style="width: {{ min(100, ($volunteer->created_at->diffInDays(now()) / 7) * 20) }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $volunteer->created_at->diffInDays() }} day{{ $volunteer->created_at->diffInDays() !== 1 ? 's' : '' }} ago
                                </p>
                            </div>
                        </td>
                        <td class="px-8 py-8 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <a href="{{ route('admin.volunteers.show', $volunteer) }}" 
                                   class="inline-flex items-center justify-center w-12 h-12 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-800/50 rounded-2xl transition-all duration-200 hover:scale-110"
                                   title="View Details">
                                    <i class="fas fa-eye text-lg"></i>
                                </a>
                                <button type="button" 
                                        class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg hover:shadow-xl rounded-2xl transition-all duration-200 hover:scale-110 transform hover:-translate-y-1"
                                        data-bs-toggle="modal" data-bs-target="#approveModal{{ $volunteer->id }}" title="Approve">
                                    <i class="fas fa-check text-lg"></i>
                                </button>
                                <button type="button" 
                                        class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg hover:shadow-xl rounded-2xl transition-all duration-200 hover:scale-110 transform hover:-translate-y-1"
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $volunteer->id }}" title="Reject">
                                    <i class="fas fa-times text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-24 h-24 bg-gradient-to-br from-yellow-100 to-amber-100 dark:from-yellow-900/20 dark:to-amber-900/20 rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="fas fa-check-circle text-4xl text-yellow-500"></i>
                                </div>
                                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">No pending applications</h3>
                                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                                    All volunteer applications have been reviewed. Great job keeping up with the queue!
                                </p>
                                <a href="{{ route('admin.volunteers.index') }}" 
                                   class="mt-6 inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300">
                                    <i class="fas fa-users"></i>
                                    View All Volunteers
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($volunteers->hasPages())
        <div class="px-8 py-8 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-950/10 dark:to-amber-950/10 border-t border-gray-200 dark:border-gray-700">
            {{ $volunteers->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Approve Modals --}}
@foreach($volunteers as $volunteer)
<div class="modal fade" id="approveModal{{ $volunteer->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white dark:bg-gray-800 shadow-2xl rounded-2xl border-0">
            <form action="{{ route('admin.volunteers.approve', $volunteer) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-header px-8 pt-8 pb-6 bg-gradient-to-r from-green-50/80 to-emerald-50/80 dark:from-green-950/10 dark:to-emerald-950/10 border-b border-gray-200/50 dark:border-gray-700/50">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl text-white shadow-lg">
                            <i class="fas fa-check-circle text-2xl"></i>
                        </div>
                        <div>
                            <h5 class="modal-title text-2xl font-bold text-gray-900 dark:text-white">
                                Approve {{ $volunteer->user->name }}
                            </h5>
                            <p class="text-sm text-green-700 dark:text-green-400 font-medium mt-1">
                                Application #{{ $volunteer->id }}
                            </p>
                        </div>
                    </div>
                    <button type="button" class="ml-auto text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 transition-colors" data-bs-dismiss="modal">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div class="modal-body px-8 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-950/20 dark:to-emerald-950/20 rounded-2xl p-6 border-l-4 border-green-500">
                            <h6 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-user-circle mr-2 text-green-500"></i>Applicant Details
                            </h6>
                            <div class="space-y-3 text-sm">
                                <p><span class="font-medium text-gray-700 dark:text-gray-300">Email:</span> {{ $volunteer->user->email }}</p>
                                @if($volunteer->phone)
                                <p><span class="font-medium text-gray-700 dark:text-gray-300">Phone:</span> {{ $volunteer->phone }}</p>
                                @endif
                                <p><span class="font-medium text-gray-700 dark:text-gray-300">Applied:</span> {{ $volunteer->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-900/50 rounded-2xl p-6 border-2 border-gray-200/50 dark:border-gray-700/50">
                            <h6 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <i class="fas fa-info-circle mr-2 text-blue-500"></i>Approval Info
                            </h6>
                            <div class="space-y-3 text-sm">
                                <p class="text-green-700 dark:text-green-400"><i class="fas fa-check-circle mr-2"></i>Immediate email notification</p>
                                <p class="text-green-700 dark:text-green-400"><i class="fas fa-check-circle mr-2"></i>Access to all campaigns</p>
                                <p class="text-green-700 dark:text-green-400"><i class="fas fa-check-circle mr-2"></i>Can log volunteer hours</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Internal Notes (Optional)
                        </label>
                        <textarea name="notes" 
                                  class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-green-500/20 focus:border-green-500 transition-all duration-300 resize-none" 
                                  rows="4" 
                                  placeholder="Add any notes about this approval..."></textarea>
                    </div>
                </div>
                <div class="modal-footer px-8 py-8 bg-gradient-to-r from-green-50/80 to-emerald-50/80 dark:from-green-950/10 dark:to-emerald-950/10 border-t border-gray-200/50 dark:border-gray-700/50 rounded-b-2xl">
                    <button type="button" class="px-8 py-3 mb-4 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800/80 hover:bg-gray-50 dark:hover:bg-gray-700/60 border-2 border-gray-300 dark:border-gray-600 font-semibold rounded-2xl shadow-sm hover:shadow-md transition-all duration-300" data-bs-dismiss="modal">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="px-12 py-4 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:from-green-600 hover:to-emerald-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center">
                        <i class="fas fa-check-circle mr-3"></i>
                        Approve Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal{{ $volunteer->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-white dark:bg-gray-800 shadow-2xl rounded-2xl border-0">
                <form action="{{ route('admin.volunteers.reject', $volunteer) }}" method="POST">
                    @csrf @method('PATCH')
                    <div class="modal-header px-8 pt-8 pb-6 bg-gradient-to-r from-red-50/80 to-rose-50/80 dark:from-red-950/10 dark:to-rose-950/10 border-b border-gray-200/50 dark:border-gray-700/50">
                        <div class="flex items-center gap-4">
                            <div class="p-3 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl text-white shadow-lg">
                                <i class="fas fa-times-circle text-2xl"></i>
                            </div>
                            <div>
                                <h5 class="modal-title text-2xl font-bold text-gray-900 dark:text-white">
                                    Reject {{ $volunteer->user->name }}
                                </h5>
                                <p class="text-sm text-red-700 dark:text-red-400 font-medium mt-1">
                                    Application #{{ $volunteer->id }}
                                </p>
                            </div>
                        </div>
                        <button type="button" class="ml-auto text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300 transition-colors" data-bs-dismiss="modal">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="modal-body px-8 py-8">
                        <div class="bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-950/20 dark:to-rose-950/20 rounded-2xl p-6 border-l-4 border-red-500 mb-8">
                            <div class="flex items-start gap-4">
                                <div class="p-3 bg-red-100 dark:bg-red-900/20 rounded-full flex-shrink-0 mt-1">
                                    <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                                </div>
                                <div>
                                    <h6 class="font-semibold text-gray-900 dark:text-white text-lg">Rejection Notice</h6>
                                    <p class="text-gray-700 dark:text-gray-300 mt-2 text-sm">
                                        The applicant will receive this reason via email. Please be specific and professional.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="bg-white dark:bg-gray-900/50 rounded-2xl p-6 border-2 border-gray-200/50 dark:border-gray-700/50">
                                <h6 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-user mr-2 text-orange-500"></i>Applicant
                                </h6>
                                <div class="space-y-2 text-sm">
                                    <p><span class="font-medium">Email:</span> {{ $volunteer->user->email }}</p>
                                    @if($volunteer->phone)
                                    <p><span class="font-medium">Phone:</span> {{ $volunteer->phone }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-900/50 rounded-2xl p-6 border-2 border-gray-200/50 dark:border-gray-700/50">
                                <h6 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>Timeline
                                </h6>
                                <div class="space-y-2 text-sm">
                                    <p><span class="font-medium">Applied:</span> {{ $volunteer->created_at->format('M d, Y') }}</p>
                                    <p><span class="font-medium">Waiting:</span> {{ $volunteer->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center">
                                <span class="text-red-500 mr-3">*</span>
                                Rejection Reason
                            </label>
                            <textarea name="rejection_reason" 
                                      class="w-full px-5 py-4 border-2 border-red-200/50 dark:border-red-800/50 rounded-2xl bg-gradient-to-r from-red-50/20 to-rose-50/20 dark:from-red-950/10 dark:to-rose-950/10 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-red-500/20 focus:border-red-500 transition-all duration-300 resize-none" 
                                      rows="6" 
                                      placeholder="Explain why this application is being rejected (e.g., 'Limited availability does not meet minimum requirements', 'Skills do not match current campaign needs', etc.)..."
                                      required></textarea>
                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                This message will be sent directly to {{ $volunteer->user->name }}
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer px-8 py-8 bg-gradient-to-r from-red-50/80 to-rose-50/80 dark:from-red-950/10 dark:to-rose-950/10 border-t border-gray-200/50 dark:border-gray-700/50 rounded-b-2xl">
                        <button type="button" class="px-8 py-3 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800/80 hover:bg-gray-50 dark:hover:bg-gray-700/60 border-2 border-gray-300 dark:border-gray-600 font-semibold rounded-2xl shadow-sm hover:shadow-md transition-all duration-300" data-bs-dismiss="modal">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </button>
                        <button type="submit" class="px-12 py-4 bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:from-red-600 hover:to-rose-700 transform hover:-translate-y-1 transition-all duration-300 flex items-center"
                                onclick="return confirm('Are you sure? This action cannot be undone.')">
                            <i class="fas fa-times-circle mr-3"></i>
                            Reject Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

@push('scripts')
<script>
    // Smooth animations for action buttons
    document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            const modalId = this.getAttribute('data-bs-target');
            setTimeout(() => {
                const modal = document.querySelector(modalId);
                if (modal) {
                    modal.classList.add('show');
                    modal.style.display = 'block';
                    document.body.classList.add('modal-open');
                    document.body.style.overflow = 'hidden';
                    document.body.style.paddingRight = '0';
                }
            }, 100);
        });
    });
</script>
@endpush
@endsection