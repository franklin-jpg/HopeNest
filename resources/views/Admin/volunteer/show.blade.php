@extends('layouts.admin')

@section('title', $volunteer->user->name . ' - Volunteer Profile')

@section('content')
<div class="p-6 space-y-8 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-8">
        <div class="flex flex-col sm:flex-row sm:items-center gap-6">
            <div class="relative">
                <img class="w-32 h-32 lg:w-20 lg:h-20 rounded-full object-cover ring-4 ring-white/20 dark:ring-gray-800/50 shadow-xl" 
                     src="{{ $volunteer->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($volunteer->user->name) . '&size=160&color=ff9500&background=f97316' }}" 
                     alt="{{ $volunteer->user->name }}">
                <div class="absolute -bottom-2 -right-2 bg-gradient-to-br from-orange-500 to-orange-600 p-3 rounded-full text-white shadow-lg">
                    <i class="fas fa-user-check text-1xl"></i>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-4">
                    <h1 class="text-4xl lg:text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $volunteer->user->name }}
                    </h1>
                    <span class="inline-flex items-center px-6 py-3 rounded-full text-sm font-semibold bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-lg">
                        <i class="fas fa-{{ $volunteer->status == 'approved' ? 'check-circle' : ($volunteer->status == 'pending' ? 'clock' : 'times-circle') }} mr-2"></i>
                        {{ ucfirst($volunteer->status) }}
                    </span>
                </div>
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    <i class="fas fa-envelope mr-2 text-orange-500"></i>
                    {{ $volunteer->user->email }}
                </p>
                @if($volunteer->phone)
                <p class="text-xl text-gray-600 dark:text-gray-400">
                    <i class="fas fa-phone mr-2 text-green-500"></i>
                    {{ $volunteer->phone }}
                </p>
                @endif
            </div>
        </div>
        
        <div class="flex flex-wrap gap-4 justify-end">
            @if($volunteer->status == 'pending')
                <button type="button" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-green-600 hover:to-green-700 transition-all duration-300" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="fas fa-check-circle"></i>
                    Approve
                </button>
                <button type="button" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-red-600 hover:to-red-700 transition-all duration-300" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times-circle"></i>
                    Reject
                </button>
            @elseif($volunteer->status == 'approved')
                <button type="button" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300" data-bs-toggle="modal" data-bs-target="#assignModal">
                    <i class="fas fa-plus-circle"></i>
                    Assign Campaign
                </button>
                <form action="{{ route('admin.volunteers.suspend', $volunteer) }}" method="POST" class="inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-yellow-600 hover:to-yellow-700 transition-all duration-300" onclick="return confirm('Suspend this volunteer?')">
                        <i class="fas fa-ban"></i>
                        Suspend
                    </button>
                </form>
            @elseif($volunteer->status == 'suspended')
                <form action="{{ route('admin.volunteers.reactivate', $volunteer) }}" method="POST" class="inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl hover:from-green-600 hover:to-green-700 transition-all duration-300" onclick="return confirm('Reactivate this volunteer?')">
                        <i class="fas fa-check-circle"></i>
                        Reactivate
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.volunteers.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-500/20 dark:bg-gray-500/30 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl hover:bg-gray-500/30 dark:hover:bg-gray-500/50 transition-all duration-300">
                <i class="fas fa-arrow-left"></i>
                Back to List
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-orange-50 dark:border-orange-900/30 hover:shadow-2xl hover:border-orange-200 dark:hover:border-orange-800/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-orange-700 dark:text-orange-400 uppercase tracking-wide">Total Hours</p>
                    <p class="text-4xl font-extrabold text-orange-600 dark:text-orange-400 mt-2">
                        {{ number_format($totalHours, 1) }}<span class="text-lg">h</span>
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-green-50 dark:border-green-900/30 hover:shadow-2xl hover:border-green-200 dark:hover:border-green-800/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-700 dark:text-green-400 uppercase tracking-wide">Campaigns</p>
                    <p class="text-4xl font-extrabold text-green-600 dark:text-green-400 mt-2">
                        {{ $volunteer->campaigns->count() }}
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-project-diagram text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-blue-50 dark:border-blue-900/30 hover:shadow-2xl hover:border-blue-200 dark:hover:border-blue-800/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-wide">Applied</p>
                    <p class="text-4xl font-extrabold text-blue-600 dark:text-blue-400 mt-2">
                        {{ $volunteer->created_at->format('M d, Y') }}
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-calendar-alt text-2xl"></i>
                </div>
            </div>
        </div>

        @if($volunteer->approved_at)
        <div class="group bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-xl border border-emerald-50 dark:border-emerald-900/30 hover:shadow-2xl hover:border-emerald-200 dark:hover:border-emerald-800/50 transition-all duration-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wide">Approved</p>
                    <p class="text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-2">
                        {{ $volunteer->approved_at->format('M d, Y') }}
                    </p>
                </div>
                <div class="p-4 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl text-white group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Application Details -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-file-alt mr-3"></i>
                        Application Details
                    </h2>
                </div>
                <div class="p-8 space-y-6">
                    @if($volunteer->notes)
                    <div class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-950/20 dark:to-amber-950/20 rounded-2xl p-6 border-l-4 border-orange-500">
                        <h6 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-sticky-note mr-2 text-orange-500"></i>
                            Approval Notes
                        </h6>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $volunteer->notes }}</p>
                    </div>
                    @endif

                    @if($volunteer->rejection_reason)
                    <div class="bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-950/20 dark:to-rose-950/20 rounded-2xl p-6 border-l-4 border-red-500">
                        <h6 class="font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2 text-red-500"></i>
                            Rejection Reason
                        </h6>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $volunteer->rejection_reason }}</p>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status History</label>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Applied</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $volunteer->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                @if($volunteer->approved_at)
                                <div class="flex justify-between items-center p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border-l-4 border-green-500">
                                    <span class="text-sm font-medium text-green-700 dark:text-green-400">Approved</span>
                                    <span class="text-xs text-green-600 dark:text-green-400">{{ $volunteer->approved_at->format('M d, Y H:i') }}</span>
                                </div>
                                @endif
                                @if($volunteer->updated_at->gt($volunteer->approved_at ?? $volunteer->created_at))
                                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Last Updated</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $volunteer->updated_at->format('M d, Y H:i') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hours Log -->
            @if($volunteer->hours->count() > 0)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-clock mr-3"></i>
                        Hours Log ({{ $volunteer->hours->where('status', 'approved')->sum('hours') }}h total)
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Campaign</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Hours</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($volunteer->hours->sortByDesc('created_at') as $hour)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center text-white mr-4">
                                            <i class="fas fa-project-diagram text-sm"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $hour->campaign->title ?? 'General' }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $hour->campaign->location ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $hour->date->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400">
                                        {{ $hour->hours }}h
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($hour->status == 'approved')
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-green-400 to-green-600 text-white shadow-sm">
                                            <i class="fas fa-check mr-1"></i>Approved
                                        </span>
                                    @elseif($hour->status == 'pending')
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gradient-to-r from-yellow-400 to-orange-500 text-white shadow-sm">
                                            <i class="fas fa-clock mr-1"></i>Pending
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center shadow-xl">
                <div class="mx-auto w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-clock text-3xl text-gray-400 dark:text-gray-500"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">No Hours Logged</h3>
                <p class="text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    This volunteer hasn't logged any hours yet. They can start contributing to campaigns once assigned.
                </p>
            </div>
            @endif
        </div>

        <!-- Sidebar - Campaigns -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden sticky top-6">
                <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-list mr-3"></i>
                        Assigned Campaigns
                        <span class="ml-auto inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-white/20">
                            {{ $volunteer->campaigns->count() }}
                        </span>
                    </h2>
                </div>
                <div class="p-6 max-h-96 overflow-y-auto">
                    @forelse($volunteer->campaigns->sortByDesc('pivot.created_at') as $campaign)
                    <div class="group bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900/30 dark:to-gray-800/30 rounded-xl p-6 mb-4 border border-gray-200/50 dark:border-gray-700/50 hover:border-emerald-300 dark:hover:border-emerald-800/50 transition-all duration-300">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-semibold text-gray-900 dark:text-white text-lg line-clamp-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                {{ $campaign->title }}
                            </h4>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-400">
                                {{ $campaign->pivot->status }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                            {{ $campaign->description }}
                        </p>
                        <div class="flex flex-wrap gap-2 text-xs text-gray-500 dark:text-gray-400">
                            <span class="inline-flex items-center px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ $campaign->start_date->format('M d') }} - {{ $campaign->end_date->format('M d') }}
                            </span>
                            @if($campaign->location)
                            <span class="inline-flex items-center px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded-full">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $campaign->location }}
                            </span>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200/50 dark:border-gray-700/50">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Assigned: {{ $campaign->pivot->created_at->format('M d, Y') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <div class="mx-auto w-20 h-20 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-project-diagram text-2xl text-gray-400 dark:text-gray-500"></i>
                        </div>
                        <p class="text-gray-600 dark:text-gray-400">No campaigns assigned yet</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Action Modals --}}
<div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content bg-white dark:bg-gray-800 shadow-2xl rounded-2xl">
            <form action="{{ route('admin.volunteers.assign-campaign', $volunteer) }}" method="POST">
                @csrf
                <div class="modal-header px-8 pt-8 pb-6 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-950/10">
                    <h5 class="modal-title text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-plus-circle text-orange-500 mr-3"></i>
                        Assign to Campaign
                    </h5>
                    <button type="button" class="text-gray-400 hover:text-gray-600 dark:text-gray-400 dark:hover:text-gray-300" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body px-8 py-8">
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Select Campaign *</label>
                            <select name="campaign_id" class="w-full px-5 py-4 border-2 border-gray-200 dark:border-gray-600 rounded-2xl bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-300 focus:ring-3 focus:ring-orange-500/20 focus:border-orange-500 transition-all duration-300" required>
                                <option value="">Choose a campaign...</option>
                                @foreach(\App\Models\Campaign::where('status', 'active')->get() as $campaign)
                                    @if(!$volunteer->campaigns->contains($campaign->id))
                                    <option value="{{ $campaign->id }}">{{ $campaign->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer px-8 py-8 bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-950/10 border-t">
                    <button type="button" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-2xl hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 m-5" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transition-all duration-300">Assign Campaign</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-hide success messages
    @if(session('success'))
        setTimeout(() => {
            const alert = document.querySelector('.alert-success');
            if (alert) alert.remove();
        }, 5000);
    @endif
</script>
@endpush
@endsection