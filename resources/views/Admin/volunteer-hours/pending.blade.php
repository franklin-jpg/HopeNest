@extends('layouts.admin')

@php
    use App\Models\VolunteerHour;
    use App\Models\Volunteer;
@endphp

@section('title', 'Pending Volunteer Hours')

@section('content')
<div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
    @if ($errors->any())
    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were some errors</h3>
                <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-300">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                <i class="fas fa-clock mr-3"></i>Pending Hours Review
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Review and approve volunteer hours waiting for your confirmation</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.volunteer-hours.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-300">
                <i class="fas fa-list"></i>
                All Hours
            </a>
            <a href="{{ route('admin.volunteers.index') }}" 
               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-amber-700 transition-all duration-300">
                <i class="fas fa-users"></i>
                Manage Volunteers
            </a>
        </div>
    </div>

    <!-- Statistics Cards - Pending Focused -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Total Pending Hours -->
        <div class="group bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-950/20 dark:to-orange-950/20 rounded-2xl p-6 shadow-lg border-2 border-amber-200/50 dark:border-amber-800/50 hover:shadow-xl hover:border-amber-300/70 dark:hover:border-amber-700/60 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wide">Pending Hours</p>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400 mt-1">
                        {{ VolunteerHour::pending()->count() }}
                    </p>
                    <p class="text-xs text-amber-600 dark:text-amber-400 mt-1 font-medium">
                        {{ number_format(VolunteerHour::pending()->sum('hours'), 1) }}h total
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300 shadow-lg">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Today's Pending -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-blue-50 dark:border-blue-900/30 hover:shadow-xl hover:border-blue-200 dark:hover:border-blue-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wide">Today's Pending</p>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">
                        {{ VolunteerHour::pending()->whereDate('date', today())->count() }}
                    </p>
                    <p class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-medium">
                        {{ number_format(VolunteerHour::pending()->whereDate('date', today())->sum('hours'), 1) }}h
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar-day text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- This Week -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-green-50 dark:border-green-900/30 hover:shadow-xl hover:border-green-200 dark:hover:border-green-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-700 dark:text-green-400 uppercase tracking-wide">This Week</p>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-1">
                        {{ VolunteerHour::pending()->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count() }}
                    </p>
                    <p class="text-xs text-green-600 dark:text-green-400 mt-1 font-medium">
                        {{ number_format(VolunteerHour::pending()->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->sum('hours'), 1) }}h
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar-week text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Volunteers with Pending -->
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-purple-50 dark:border-purple-900/30 hover:shadow-xl hover:border-purple-200 dark:hover:border-purple-800/50 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-purple-700 dark:text-purple-400 uppercase tracking-wide">Volunteers Waiting</p>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                        {{ VolunteerHour::pending()->distinct('volunteer_id')->count('volunteer_id') }}
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-purple-500 to-violet-600 rounded-xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-user-clock text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Filters -->
    <div class=" rounded-2xl shadow-lg  ">
        <div class="p-6 border-b border-gray-400 dark:border-gray-500 bg-gradient-to-r from-amber-500/5 to-orange-500/5">
            <h2 class="text-lg font-bold text-amber-800 dark:text-amber-200 flex items-center gap-2">
                <i class="fas fa-filter text-amber-600"></i>
                Quick Filters
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.volunteer-hours.pending') }}?date_from={{ now()->format('Y-m-d') }}"
                   class="group flex flex-col items-center p-4 rounded-xl bg-white dark:bg-gray-800 border-2 border-amber-200/50 dark:border-amber-800/50 hover:border-amber-400 dark:hover:border-amber-600 hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-calendar-day text-2xl text-amber-600 group-hover:text-amber-700 mb-2"></i>
                    <span class="text-sm font-semibold text-amber-800 dark:text-amber-300">Today</span>
                    <span class="text-xs text-amber-600 dark:text-amber-400 mt-1">
                        {{ VolunteerHour::pending()->whereDate('date', today())->count() }} entries
                    </span>
                </a>

                <a href="{{ route('admin.volunteer-hours.pending') }}?date_from={{ now()->startOfWeek()->format('Y-m-d') }}&date_to={{ now()->endOfWeek()->format('Y-m-d') }}"
                   class="group flex flex-col items-center p-4 rounded-xl bg-white dark:bg-gray-800 border-2 border-blue-200/50 dark:border-blue-800/50 hover:border-blue-400 dark:hover:border-blue-600 hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-calendar-week text-2xl text-blue-600 group-hover:text-blue-700 mb-2"></i>
                    <span class="text-sm font-semibold text-blue-800 dark:text-blue-300">This Week</span>
                    <span class="text-xs text-blue-600 dark:text-blue-400 mt-1">
                        {{ VolunteerHour::pending()->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])->count() }} entries
                    </span>
                </a>

                <a href="{{ route('admin.volunteer-hours.pending') }}?date_from={{ now()->startOfMonth()->format('Y-m-d') }}&date_to={{ now()->endOfMonth()->format('Y-m-d') }}"
                   class="group flex flex-col items-center p-4 rounded-xl bg-white dark:bg-gray-800 border-2 border-green-200/50 dark:border-green-800/50 hover:border-green-400 dark:hover:border-green-600 hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-calendar-alt text-2xl text-green-600 group-hover:text-green-700 mb-2"></i>
                    <span class="text-sm font-semibold text-green-800 dark:text-green-300">This Month</span>
                    <span class="text-xs text-green-600 dark:text-green-400 mt-1">
                        {{ VolunteerHour::pending()->whereMonth('date', now()->month)->count() }} entries
                    </span>
                </a>

                <a href="{{ route('admin.volunteer-hours.pending') }}"
                   class="group flex flex-col items-center p-4 rounded-xl bg-white dark:bg-gray-800 border-2 border-gray-200/50 dark:border-gray-700/50 hover:border-gray-400 dark:hover:border-gray-600 hover:shadow-lg transition-all duration-300">
                    <i class="fas fa-list text-2xl text-gray-600 group-hover:text-gray-700 mb-2"></i>
                    <span class="text-sm font-semibold text-gray-800 dark:text-gray-300">All Pending</span>
                    <span class="text-xs text-gray-600 dark:text-gray-400 mt-1 font-bold">
                        {{ VolunteerHour::pending()->count() }} total
                    </span>
                </a>
            </div>
        </div>
    </div>

    <!-- Pending Hours Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden">
        <!-- Action Bar -->
        <div class=" bg-amber-800 p-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-3 bg-white/20 backdrop-blur-sm rounded-xl p-3 border border-white/20">
                        <div class="p-2 bg-white/10 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">Action Required</h3>
                            <p class="text-white/90 text-sm">{{ $hours->total() }} pending hours need your review</p>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-3 justify-end">
                    <button type="button" id="selectAllBtn" class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-xl border-2 border-white/30 hover:bg-white/30 hover:border-white/50 transition-all duration-300">
                        <i class="fas fa-check-square"></i>
                        Select All
                    </button>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.volunteer-hours.bulk-approve') }}" method="POST" id="bulkApproveForm" class="p-6">
            @csrf
            <div class="flex items-center justify-between flex-wrap gap-4 mb-6 bg-gradient-to-r from-amber-50/50 to-orange-50/50 dark:from-amber-950/10 dark:to-orange-950/10 rounded-xl p-4 border border-amber-200/30 dark:border-amber-800/30">
                <div class="flex items-center gap-4">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-check-double"></i>
                        Approve Selected
                    </button>
                </div>
                <div class="text-sm text-amber-800 dark:text-amber-200 font-medium">
                    <span id="selectedCount">0</span> of {{ $hours->count() }} hours selected
                    @if($hours->total() > $hours->count())
                        <span class="ml-4 text-sm text-gray-600 dark:text-gray-400">+{{ $hours->total() - $hours->count() }} more on other pages</span>
                    @endif
                </div>
            </div>

            @if($hours->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-950/20 dark:to-orange-950/20">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-amber-300 dark:border-amber-600 text-emerald-600 shadow-sm focus:ring-emerald-500 h-4 w-4">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Volunteer</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Campaign</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Hours</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-right text-xs font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($hours as $hour)
                        <tr class="hover:bg-gradient-to-r hover:from-amber-50/50 hover:to-orange-50/50 dark:hover:from-amber-950/10 dark:hover:to-orange-950/10 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <input type="checkbox" name="hour_ids[]" value="{{ $hour->id }}" class="hour-checkbox rounded border-amber-300 dark:border-amber-600 text-emerald-600 shadow-sm focus:ring-emerald-500 h-4 w-4">
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.volunteers.show', $hour->volunteer) }}" class="group inline-flex items-center text-sm font-semibold text-gray-900 dark:text-white hover:text-amber-600 dark:hover:text-amber-400">
                                    <div class="h-12 w-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-full flex items-center justify-center mr-4 group-hover:ring-2 group-hover:ring-amber-500/30 shadow-lg">
                                        <i class="fas fa-user text-white text-sm"></i>
                                    </div>
                                    <div>
                                        <span class="truncate max-w-xs block">{{ $hour->volunteer->user->name }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $hour->volunteer->user->email }}</span>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($hour->campaign)
                                    <a href="{{ route('admin.campaigns.show', $hour->campaign) }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 border-2 border-indigo-200/50 dark:border-indigo-800/50 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-semibold hover:bg-indigo-500/20 hover:border-indigo-300/70 transition-all duration-300">
                                        <i class="fas fa-bullhorn"></i>
                                        {{ Str::limit($hour->campaign->title, 25) }}
                                    </a>
                                @else
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-gray-500/10 to-gray-600/10 border-2 border-gray-200/50 dark:border-gray-700/50 text-gray-700 dark:text-gray-300 rounded-full text-sm font-semibold">
                                        <i class="fas fa-hands-helping"></i>
                                        General Volunteering
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-mono text-amber-800 dark:text-amber-200 bg-amber-100/80 dark:bg-amber-900/30 rounded-full px-3 py-1">
                                    {{ $hour->date->format('M d, Y') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 text-amber-800 dark:text-amber-200 rounded-full text-sm font-bold border-2 border-amber-200/50 dark:border-amber-800/50">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ number_format($hour->hours, 1) }}h
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-md">
                                    <div class="p-4 bg-gradient-to-r from-amber-50/50 to-orange-50/50 dark:from-amber-950/10 dark:to-orange-950/10 rounded-xl border-2 border-amber-200/30 dark:border-amber-800/40 hover:border-amber-300/60 dark:hover:border-amber-700/50 transition-all duration-300 group">
                                        <p class="font-medium text-gray-900 dark:text-white text-sm leading-relaxed line-clamp-3 group-hover:text-amber-800 dark:group-hover:text-amber-200">
                                            {{ $hour->description ?: 'No description provided' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end gap-3">
                                    <form action="{{ route('admin.volunteer-hours.approve', $hour) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transition-all duration-300 hover:scale-105"
                                                title="Approve Hours">
                                            <i class="fas fa-check text-lg"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.volunteer-hours.reject', $hour) }}" method="POST" class="inline">
                                        @csrf @method('PATCH')
                                        <button type="submit" 
                                                class="inline-flex items-center justify-center w-12 h-12 bg-gradient-to-r from-red-500 to-rose-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:from-red-600 hover:to-rose-700 transition-all duration-300 hover:scale-105"
                                                title="Reject Hours"
                                                onclick="return confirm('Are you sure you want to reject {{ number_format($hour->hours, 1) }} hours for {{ $hour->volunteer->user->name }}?')">
                                            <i class="fas fa-times text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-6 bg-gradient-to-r from-amber-50/50 to-orange-50/50 dark:from-amber-950/10 dark:to-orange-950/10 border-t-2 border-amber-200/30 dark:border-amber-800/30 rounded-b-2xl">
                {{ $hours->appends(request()->query())->links() }}
            </div>
            @else
            <div class="text-center py-16">
                <div class="flex flex-col items-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-emerald-500 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-xl">
                        <i class="fas fa-check-circle text-4xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">🎉 Great job!</h3>
                    <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto text-center mb-6">
                        All pending volunteer hours have been reviewed and approved. 
                        You're all caught up! 🚀
                    </p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('admin.volunteer-hours.index') }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transition-all duration-300">
                            <i class="fas fa-list"></i>
                            View All Hours
                        </a>
                        <a href="{{ route('admin.volunteers.index') }}" 
                           class="inline-flex items-center gap-3 px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-amber-700 transition-all duration-300">
                            <i class="fas fa-users"></i>
                            Manage Volunteers
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Enhanced bulk selection functionality
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
                selectAllBtn.classList.remove('bg-green-500', 'bg-amber-500');
                selectAllBtn.classList.add('bg-white/20');
            } else if (selectedCount === hourCheckboxes.length) {
                selectAllBtn.innerHTML = '<i class="fas fa-check-square mr-2"></i>Deselect All';
                selectAllBtn.classList.remove('bg-white/20');
                selectAllBtn.classList.add('bg-green-500/30');
            } else {
                selectAllBtn.innerHTML = `<i class="fas fa-minus-square mr-2"></i>Partial (${selectedCount})`;
                selectAllBtn.classList.remove('bg-white/20', 'bg-green-500/30');
                selectAllBtn.classList.add('bg-amber-500/30');
            }
        }
    }

    // Select All checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            hourCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateSelection();
        });
    }

    // Select All button (toggle)
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const allChecked = document.querySelectorAll('.hour-checkbox:checked').length === hourCheckboxes.length;
            
            hourCheckboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
            updateSelection();
        });
    }

    // Individual checkboxes
    hourCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });

    // Form validation
    document.getElementById('bulkApproveForm')?.addEventListener('submit', function(e) {
        if (selectedCount === 0) {
            e.preventDefault();
            alert('⚠️ Please select at least one hour entry to approve.');
            return false;
        }
        
        if (!confirm(`✅ Approve ${selectedCount} hour entrie(s)?`)) {
            e.preventDefault();
            return false;
        }
    });

    // Smooth animations
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading state to approve buttons
        const approveButtons = document.querySelectorAll('form[action*="approve"] button[type="submit"]');
        approveButtons.forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const originalIcon = icon.innerHTML;
                icon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                this.disabled = true;
                
                setTimeout(() => {
                    icon.innerHTML = originalIcon;
                    this.disabled = false;
                }, 2000);
            });
        });
    });
</script>
@endpush

@endsection