@extends('layouts.admin')

@php
    use App\Models\VolunteerHour;
    use App\Models\Volunteer;
@endphp

@section('title', 'Volunteer Hours Management')

@section('content')
<div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">Volunteer Hours Management</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage and approve volunteer hours efficiently</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.volunteers.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-amber-700 transition-all duration-300">
                <i class="fas fa-users"></i>
                View Volunteers
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Pending Hours -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-lg border border-orange-50 dark:border-orange-900/30 hover:shadow-xl hover:border-orange-200 dark:hover:border-orange-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-orange-700 dark:text-orange-400 uppercase tracking-wide">Pending Review</p>
                    <p class="text-2xl sm:text-3xl font-bold text-orange-600 dark:text-orange-400 mt-1">
                        {{ VolunteerHour::pending()->count() }}
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-lg sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Approved Hours -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-lg border border-green-50 dark:border-green-900/30 hover:shadow-xl hover:border-green-200 dark:hover:border-green-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-700 dark:text-green-400 uppercase tracking-wide">Total Approved</p>
                    <p class="text-2xl sm:text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                        {{ number_format(VolunteerHour::approved()->sum('hours'), 1) }}h
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-green-500 to-green-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-check-circle text-lg sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- This Month -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-lg border border-blue-50 dark:border-blue-900/30 hover:shadow-xl hover:border-blue-200 dark:hover:border-blue-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wide">This Month</p>
                    <p class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                        {{ number_format(VolunteerHour::whereMonth('date', now()->month)->approved()->sum('hours'), 1) }}h
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar text-lg sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Active Volunteers -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-4 sm:p-6 shadow-lg border border-purple-50 dark:border-purple-900/30 hover:shadow-xl hover:border-purple-200 dark:hover:border-purple-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-purple-700 dark:text-purple-400 uppercase tracking-wide">Active Volunteers</p>
                    <p class="text-2xl sm:text-3xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                        {{ Volunteer::approved()->count() }}
                    </p>
                </div>
                <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-friends text-lg sm:text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.volunteer-hours.index') }}" method="GET" class="grid grid-cols-1 lg:grid-cols-5 gap-4 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Volunteer</label>
                    <select name="volunteer_id" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200">
                        <option value="">All Volunteers</option>
                        @foreach($volunteers as $vol)
                            <option value="{{ $vol->id }}" {{ request('volunteer_id') == $vol->id ? 'selected' : '' }}>
                                {{ $vol->user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                    <input type="date" name="date_from" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200" value="{{ request('date_from') }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                    <input type="date" name="date_to" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-all duration-200" value="{{ request('date_to') }}">
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" 
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-amber-700 transition-all duration-300">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <a href="{{ route('admin.volunteer-hours.index') }}" 
                       class="px-6 py-3 bg-gray-500/20 dark:bg-gray-500/30 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-500/30 dark:hover:bg-gray-500/50 transition-all duration-300">
                        Clear
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Hours Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <!-- Tabs -->
        <div class="border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900/30 dark:to-gray-800/30">
            <nav class="flex -mb-px space-x-8" aria-label="Tabs">
                <a href="{{ route('admin.volunteer-hours.index') }}" 
                   class="{{ !request('status') ? 'border-orange-500 text-orange-600 dark:text-orange-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200">
                    All Hours
                </a>
                <a href="{{ route('admin.volunteer-hours.pending') }}" 
                   class="{{ request('status') == 'pending' ? 'border-orange-500 text-orange-600 dark:text-orange-400' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700' }} inline-flex items-center whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-all duration-200">
                    Pending 
                    <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-orange-500 to-amber-600 text-white shadow-sm">
                        {{ VolunteerHour::pending()->count() }}
                    </span>
                </a>
            </nav>
        </div>

        <div class="overflow-x-auto">
            @if($hours->count() > 0)
            <form action="{{ route('admin.volunteer-hours.bulk-approve') }}" method="POST" id="bulkApproveForm" class="p-6">
                @csrf
                <div class="flex items-center justify-between flex-wrap gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <button type="button" id="selectAllBtn" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-green-600 hover:to-green-700 transition-all duration-300">
                            <i class="fas fa-check-square"></i>
                            Select All
                        </button>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-check-double"></i>
                            Approve Selected
                        </button>
                    </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span id="selectedCount">0</span> of {{ $hours->count() }} hours selected
                    </div>
                </div>

                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 dark:border-gray-600 text-green-600 shadow-sm focus:ring-green-500 h-4 w-4">
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Volunteer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaign</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($hours as $hour)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($hour->status == 'pending')
                                    <input type="checkbox" name="hour_ids[]" value="{{ $hour->id }}" class="hour-checkbox rounded border-gray-300 dark:border-gray-600 text-green-600 shadow-sm focus:ring-green-500 h-4 w-4">
                                    @else
                                    <input type="checkbox" disabled class="hour-checkbox rounded border-gray-400/30 dark:border-gray-600/50 bg-gray-100/50 dark:bg-gray-700/30 h-4 w-4">
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.volunteers.show', $hour->volunteer) }}" class="group inline-flex items-center text-sm font-medium text-gray-900 dark:text-white hover:text-orange-600 dark:hover:text-orange-400">
                                        <div class="h-10 w-10 bg-gradient-to-br from-orange-500 to-amber-600 rounded-full flex items-center justify-center mr-4 group-hover:ring-2 group-hover:ring-orange-500/20">
                                            <i class="fas fa-user text-white text-sm"></i>
                                        </div>
                                        <span class="truncate max-w-xs">{{ $hour->volunteer->user->name }}</span>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($hour->campaign)
                                        <a href="{{ route('admin.campaigns.show', $hour->campaign) }}" 
                                           class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 border border-indigo-200/50 dark:border-indigo-800/50 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-medium hover:bg-indigo-500/20 transition-all duration-300">
                                            <i class="fas fa-bullhorn mr-2"></i>
                                            {{ Str::limit($hour->campaign->title, 25) }}
                                        </a>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-gray-500/10 to-gray-600/10 border border-gray-200/50 dark:border-gray-700/50 text-gray-600 dark:text-gray-400 rounded-full text-sm font-medium">
                                            <i class="fas fa-hands-helping mr-2"></i>
                                            General
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $hour->date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white">
                                    <span class="inline-flex items-center px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-sm">
                                        {{ number_format($hour->hours, 1) }}h
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <button type="button" 
                                                class="w-full text-left p-3 bg-gradient-to-r from-gray-50/80 to-gray-100/80 dark:from-gray-800/50 dark:to-gray-900/50 rounded-xl border border-gray-200/30 dark:border-gray-700/50 hover:shadow-sm transition-all duration-300 group truncate"
                                                data-bs-toggle="tooltip" 
                                                title="{{ $hour->description }}">
                                            <p class="font-medium text-gray-900 dark:text-white line-clamp-2 group-hover:text-orange-600 dark:group-hover:text-orange-400 transition-colors text-sm">
                                                {{ Str::limit($hour->description, 60) }}
                                            </p>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($hour->status == 'pending')
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-orange-400 to-amber-500 text-white shadow-sm">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @elseif($hour->status == 'approved')
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-green-400 to-green-600 text-white shadow-sm">
                                            <i class="fas fa-check mr-1"></i>Approved
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-red-400 to-red-600 text-white shadow-sm">
                                            <i class="fas fa-times mr-1"></i>Rejected
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if($hour->status == 'pending')
                                    <div class="flex items-center gap-2">
                                        <form action="{{ route('admin.volunteer-hours.approve', $hour) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-10 h-10 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-800/50 rounded-xl transition-all duration-200 hover:scale-105"
                                                    title="Approve Hours">
                                                <i class="fas fa-check text-sm"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.volunteer-hours.reject', $hour) }}" method="POST" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit" 
                                                    class="inline-flex items-center justify-center w-10 h-10 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-800/50 rounded-xl transition-all duration-200 hover:scale-105"
                                                    title="Reject Hours"
                                                    onclick="return confirm('Are you sure you want to reject these hours?')">
                                                <i class="fas fa-times text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @elseif($hour->status == 'approved')
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-200/50 dark:border-green-800/50 text-green-700 dark:text-green-300 rounded-full text-xs font-medium">
                                            <i class="fas fa-user-check mr-1"></i>
                                            {{ Str::limit($hour->approvedBy->name ?? 'System', 12) }}
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $hour->approved_at?->format('M d, Y') }}
                                        </span>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Pagination -->
            <div class="px-6 py-6 bg-gray-50 dark:bg-gray-900/30 border-t border-gray-200 dark:border-gray-700">
                {{ $hours->links() }}
            </div>
            @else
            <div class="px-6 py-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-hands-helping text-3xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">No Volunteer Hours Yet</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 max-w-2xl mx-auto">Volunteers haven't logged any hours yet. Once they start contributing, you'll see their hours here for review and approval.</p>
                    <div class="mt-6 flex flex-wrap justify-center gap-4">
                        <a href="{{ route('admin.volunteers.index') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-amber-700 transition-all duration-300">
                            <i class="fas fa-users"></i>
                            Manage Volunteers
                        </a>
                        <a href="{{ route('admin.volunteer-hours.pending') }}" 
                           class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transition-all duration-300">
                            <i class="fas fa-clock"></i>
                            Check Pending
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select all checkboxes functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const hourCheckboxes = document.querySelectorAll('.hour-checkbox');
    const bulkApproveBtn = document.querySelector('#bulkApproveForm button[type="submit"]');
    const selectedCountEl = document.getElementById('selectedCount');

    let selectedCount = 0;

    function updateSelection() {
        selectedCount = document.querySelectorAll('.hour-checkbox:checked').length;
        selectedCountEl.textContent = selectedCount;
        bulkApproveBtn.disabled = selectedCount === 0;
        
        if (selectAllBtn) {
            if (selectedCount === 0) {
                selectAllBtn.innerHTML = '<i class="fas fa-square mr-2"></i>Select All';
            } else if (selectedCount === hourCheckboxes.length) {
                selectAllBtn.innerHTML = '<i class="fas fa-check-square mr-2"></i>Deselect All';
            } else {
                selectAllBtn.innerHTML = '<i class="fas fa-minus-square mr-2"></i>Partial (' + selectedCount + ')';
            }
        }
    }

    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            hourCheckboxes.forEach(checkbox => {
                if (!checkbox.disabled) {
                    checkbox.checked = isChecked;
                }
            });
            updateSelection();
        });
    }

    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const allChecked = document.querySelectorAll('.hour-checkbox:checked').length === 
                              document.querySelectorAll('.hour-checkbox:not([disabled])').length;
            
            if (allChecked) {
                hourCheckboxes.forEach(checkbox => {
                    if (!checkbox.disabled) checkbox.checked = false;
                });
            } else {
                hourCheckboxes.forEach(checkbox => {
                    if (!checkbox.disabled) checkbox.checked = true;
                });
            }
            updateSelection();
        });
    }

    hourCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    // Prevent form submission if no hours selected
    document.getElementById('bulkApproveForm')?.addEventListener('submit', function(e) {
        if (selectedCount === 0) {
            e.preventDefault();
            alert('Please select at least one hour entry to approve.');
            return false;
        }
    });

    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush

@endsection