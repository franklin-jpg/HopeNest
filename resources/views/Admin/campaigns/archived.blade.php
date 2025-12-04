{{-- resources/views/admin/campaigns/archived.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="p-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-2">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-archive text-gray-600"></i>
                Archived Campaigns
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage campaigns that have been archived</p>
        </div>

        <a href="{{ route('admin.campaigns.index') }}"
           class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition">
            <i class="fas fa-arrow-left"></i> Active Campaigns ({{ $activeCount }})
        </a>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Search & Sort -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6 border dark:border-gray-700">
        <form action="{{ route('admin.campaigns.archived') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Search by Title -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by title..."
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-filter"></i> Apply
                    </button>
                    <a href="{{ route('admin.campaigns.archived') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Archived Campaigns Table -->
    <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-xl border dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Campaign</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Goal Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Archived On</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Actions</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($campaigns as $campaign)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">

                        <!-- Campaign Info -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($campaign->featured_image)
                                    <img src="{{ Storage::url($campaign->featured_image) }}"
                                         alt="{{ $campaign->title }}"
                                         class="w-16 h-16 object-cover rounded-lg shadow">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-600 rounded-lg flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-800 dark:text-gray-200">
                                        {{ Str::limit($campaign->title, 40) }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $campaign->location ?? '-' }}</p>
                                    <div class="flex gap-1 mt-1">
                                        @if($campaign->is_featured)
                                            <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded">Featured</span>
                                        @endif
                                        @if($campaign->is_urgent)
                                            <span class="text-xs bg-red-100 text-red-800 px-2 py-0.5 rounded">Urgent</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $campaign->campaignCategory->name }}
                            </span>
                        </td>

                        <!-- Goal Amount -->
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            ₦{{ number_format($campaign->goal_amount, 2) }}
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($campaign->status === 'active') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200
                                @elseif($campaign->status === 'completed') bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200
                                @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 @endif">
                                {{ ucfirst($campaign->status ?? 'draft') }}
                            </span>
                        </td>

                        <!-- Archived On -->
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $campaign->deleted_at->format('M d, Y') }}
                            <br>
                            <small class="text-xs text-gray-500">
                                {{ $campaign->deleted_at->diffForHumans() }}
                            </small>
                        </td>

                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <!-- Restore -->
                                <form action="{{ route('admin.campaigns.restore', $campaign->id) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                            class="flex items-center gap-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 rounded text-xs transition"
                                            onclick="return confirm('Restore this campaign?')">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>

                                <!-- Force Delete -->
                                <form action="{{ route('admin.campaigns.forceDelete', $campaign->id) }}"
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="flex items-center gap-1 bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded text-xs transition"
                                            onclick="return confirm('Permanently delete? This action cannot be undone!')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-archive text-4xl mb-2 text-gray-400"></i>
                            <p>No archived campaigns found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $campaigns->appends(request()->query())->links() }}
    </div>

</div>
@endsection