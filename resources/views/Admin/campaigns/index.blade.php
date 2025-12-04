{{-- resources/views/admin/campaigns/index.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="p-6">

    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center gap-2">
                <i class="fas fa-bullhorn text-orange-500"></i>
                Campaign Management
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and monitor all your fundraising campaigns</p>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
            <!-- View Categories -->
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center justify-center gap-2 bg-gray-600 hover:bg-gray-700 
                      text-white px-4 py-2 rounded-lg shadow transition w-full sm:w-auto text-sm">
                <i class="fas fa-list"></i> View Categories
            </a>

            <!-- View Archived -->
            <a href="{{ route('admin.campaigns.archived') }}"
               class="flex items-center justify-center gap-2 bg-gray-500 hover:bg-gray-600 
                      text-white px-4 py-2 rounded-lg shadow transition w-full sm:w-auto text-sm">
                <i class="fas fa-archive"></i> View Archived ({{ $archivedCount }})
            </a>

            <!-- New Campaign -->
            <a href="{{ route('admin.campaigns.create') }}"
               class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 
                      text-white px-4 py-2 rounded-lg shadow transition w-full sm:w-auto text-sm">
                <i class="fas fa-plus"></i> New Campaign
            </a>
        </div>
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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Total Campaigns</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-white">{{ $stats['total'] }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                    <i class="fas fa-bullhorn text-blue-600 dark:text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Active</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['active'] }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-lg">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-300 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Drafts</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['draft'] }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-lg">
                    <i class="fas fa-file-alt text-yellow-600 dark:text-yellow-300 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 border dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Completed</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['completed'] }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-lg">
                    <i class="fas fa-flag-checkered text-blue-600 dark:text-blue-300 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 shadow rounded-xl p-6 mb-6 border dark:border-gray-700">
        <form action="{{ route('admin.campaigns.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by title..."
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                    <select name="category" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Start Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- End Date -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-search mr-2"></i>Apply Filters
                </button>
                <a href="{{ route('admin.campaigns.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i>Clear
                </a>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="w-full overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-xl border dark:border-gray-700">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-100 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Campaign</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Progress</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Donors</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Timeline</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase">Actions</th>
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
                                         class="h-16 w-16 object-cover rounded-lg shadow-sm">
                                @else
                                    <div class="h-16 w-16 bg-gray-200 border-2 border-dashed rounded-lg"></div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.campaigns.show', $campaign->id) }}" 
                                       class="font-medium text-gray-800 dark:text-gray-200 hover:text-blue-600">
                                        {{ Str::limit($campaign->title, 40) }}
                                    </a>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $campaign->location ?? '-' }}</p>
                                    <div class="flex gap-1 mt-1">
                                        @if($campaign->is_featured)
                                            <span class="text-xs bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 px-2 py-0.5 rounded">Featured</span>
                                        @endif
                                        @if($campaign->is_urgent)
                                            <span class="text-xs bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 px-2 py-0.5 rounded">Urgent</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>

                        <!-- Category -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                {{ $campaign->campaignCategory->name }}
                            </span>
                        </td>

                        <!-- Progress -->
                        <td class="px-6 py-4">
                            <div class="min-w-[150px]">
                                <div class="flex justify-between mb-1 text-xs">
                                    <span class="text-gray-600 dark:text-gray-400">{{ $campaign->progress_percentage }}%</span>
                                    <span class="text-gray-600 dark:text-gray-400">₦{{ number_format($campaign->raised_amount) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $campaign->progress_percentage }}%"></div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Goal: ₦{{ number_format($campaign->goal_amount) }}</p>
                            </div>
                        </td>

                        <!-- Donors -->
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                <i class="fas fa-users mr-1"></i>{{ $campaign->donorsCount() }}
                            </span>
                        </td>

                        <!-- Timeline -->
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-calendar-alt text-gray-400"></i>
                                {{ $campaign->getFormatedDate() }}
                            </div>
                            @if($campaign->end_date)
                                <div class="flex items-center gap-1 mt-1">
                                    <i class="fas fa-clock text-gray-400"></i>
                                    <span class="{{ $campaign->daysRemaining() <= 7 ? 'text-red-600 font-semibold' : '' }}">
                                        {{ $campaign->daysRemaining() ?? 0 }} days left
                                    </span>
                                </div>
                            @endif
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                @if($campaign->status === 'active') bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200
                                @elseif($campaign->status === 'completed') bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200
                                @else bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-200 @endif">
                                {{ ucfirst($campaign->status ?? 'Draft') }}
                            </span>
                        </td>

                        <!-- Actions Dropdown -->
                        <td class="px-6 py-4 text-right">
                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                <button @click="open = !open" type="button"
                                        class="inline-flex items-center gap-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-2 rounded-lg text-sm transition">
                                    Actions <i class="fas fa-chevron-down text-xs"></i>
                                </button>

                                <div x-show="open" @click.away="open = false"
                                     class="origin-top-right absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                                    <div class="py-1">
                                        <a href="{{ route('admin.campaigns.show', $campaign->id) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-eye w-4"></i> View Details
                                        </a>
                                        <a href="{{ route('admin.campaigns.edit', $campaign->id) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-edit w-4"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.donations.index') }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-hand-holding-usd w-4"></i> View Donations
                                        </a>
                                        <a href="{{ route('admin.campaigns.updates.index', $campaign->id) }}"
                                           class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <i class="fas fa-bullhorn w-4"></i> Campaign Updates
                                        </a>
                                        
                                        <div class="border-t dark:border-gray-700"></div>
                                        
                                        @if($campaign->status == 'active')
                                        <form action="{{ route('admin.campaigns.toggle-status', $campaign->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-yellow-700 dark:text-yellow-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <i class="fas fa-pause w-4"></i> Pause Campaign
                                            </button>
                                        </form>
                                        @elseif($campaign->status == 'draft')
                                        <form action="{{ route('admin.campaigns.toggle-status', $campaign->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-green-700 dark:text-green-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <i class="fas fa-play w-4"></i> Activate Campaign
                                            </button>
                                        </form>
                                        @endif
                                        
                                        @if($campaign->status != 'completed')
                                        <form action="{{ route('admin.campaigns.mark-completed', $campaign->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <i class="fas fa-check-circle w-4"></i> Mark Completed
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.campaigns.duplicate', $campaign->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <i class="fas fa-copy w-4"></i> Duplicate
                                            </button>
                                        </form>
                                        
                                        <div class="border-t dark:border-gray-700"></div>
                                        
                                        <form action="{{ route('admin.campaigns.destroy', $campaign->id) }}" method="POST" onsubmit="return confirm('Archive this campaign?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-700 dark:text-red-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <i class="fas fa-archive w-4"></i> Archive
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-600 dark:text-gray-300">
                            <i class="fas fa-inbox text-4xl mb-2 text-gray-400"></i>
                            <p>No campaigns found.</p>
                            <a href="{{ route('admin.campaigns.create') }}" class="text-blue-600 hover:underline mt-2 inline-block">
                                Create Your First Campaign
                            </a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $campaigns->links() }}
    </div>

</div>

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endpush
@endsection