@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-950 p-6">
    <!-- Header -->
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                    <i class="fas fa-newspaper text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Blog Posts
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Manage your Hopenest news and stories
                    </p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.blogs.blog.create') }}" 
                   class="group flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-plus"></i>
                    <span>New Post</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Posts</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $posts->total() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900/20 rounded-xl">
                        <i class="fas fa-newspaper text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Published</p>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mt-1">
                            {{ \App\Models\BlogPost::where('status', 'published')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-emerald-100 dark:bg-emerald-900/20 rounded-xl">
                        <i class="fas fa-check-circle text-emerald-600 dark:text-emerald-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Drafts</p>
                        <p class="text-2xl font-bold text-amber-600 dark:text-amber-400 mt-1">
                            {{ \App\Models\BlogPost::where('status', 'draft')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-amber-100 dark:bg-amber-900/20 rounded-xl">
                        <i class="fas fa-edit text-amber-600 dark:text-amber-400 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Categories</p>
                        <p class="text-2xl font-bold text-purple-600 dark:text-purple-400 mt-1">
                            {{ \App\Models\BlogCategory::count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900/20 rounded-xl">
                        <i class="fas fa-folder text-purple-600 dark:text-purple-400 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search & Filter -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-6 mb-8">
            <form method="GET" class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <i class="fas fa-search mr-2"></i>Search Posts
                    </label>
                    <div class="relative">
                        <input type="text" 
                               name="search" 
                               class="w-full pl-12 pr-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300" 
                               placeholder="Search by title or content..."
                               value="{{ request('search') }}">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300">
                        <option value="">All Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                    </select>
                </div>

                <div class="lg:col-span-2 flex flex-col sm:flex-row gap-3">
                    <button type="submit" 
                            class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-search"></i>
                        <span>Filter Results</span>
                    </button>
                    <a href="{{ route('admin.blogs.blog.index') }}" 
                       class="flex-1 flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-times"></i>
                        <span>Clear Filters</span>
                    </a>
                </div>
            </form>
        </div>

        <!-- Posts Table -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">#</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Categories</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Published</th>
                            <th class="px-6 py-5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700/50">
                        @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200">
                            <td class="px-6 py-5 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ ($posts->currentPage() - 1) * $posts->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    @if($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}" 
                                             alt="{{ $post->title }}" 
                                             class="w-12 h-12 rounded-lg object-cover shadow-sm">
                                    @else
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-white text-sm"></i>
                                        </div>
                                    @endif
                                    <div class="flex flex-col">
                                        <strong class="text-sm font-semibold text-gray-900 dark:text-white leading-tight">
                                            {{ Str::limit($post->title, 60) }}
                                        </strong>
                                        @if($post->is_featured)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-amber-400 to-yellow-500 text-white mt-1">
                                                <i class="fas fa-star"></i>
                                                Featured
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap">
                                @php $statusClass = match($post->status) {
                                    'published' => 'bg-gradient-to-r from-emerald-500 to-green-600',
                                    'scheduled' => 'bg-gradient-to-r from-blue-500 to-indigo-600',
                                    'draft' => 'bg-gradient-to-r from-gray-400 to-gray-500',
                                    default => 'bg-gradient-to-r from-gray-400 to-gray-500'
                                }; @endphp
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium text-white shadow-sm {{ $statusClass }}">
                                    @if($post->status === 'published')
                                        <i class="fas fa-check-circle mr-1"></i> Published
                                    @elseif($post->status === 'scheduled')
                                        <i class="fas fa-clock mr-1"></i> Scheduled
                                    @else
                                        <i class="fas fa-edit mr-1"></i> Draft
                                    @endif
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-2">
                                    @forelse($post->categories->take(3) as $category)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-white shadow-sm"
                                              style="background-color: {{ $category->color }};">
                                            {{ $category->name }}
                                        </span>
                                    @empty
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500">
                                            <i class="fas fa-folder-open mr-1"></i> No categories
                                        </span>
                                    @endforelse
                                    @if($post->categories->count() > 3)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500">
                                            +{{ $post->categories->count() - 3 }} more
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                @if($post->published_at)
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-calendar-alt text-xs"></i>
                                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                                        @if($post->published_at->isToday())
                                            <span class="ml-2 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-200 text-xs rounded-full font-medium">
                                                Today
                                            </span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">Not published</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('admin.blogs.blog.edit', $post) }}" 
                                       class="group relative inline-flex items-center justify-center p-2 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 hover:scale-110"
                                       title="Edit Post">
                                        <i class="fas fa-edit text-lg"></i>
                                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-blue-500 group-hover:w-4 transition-all duration-300"></div>
                                    </a>
                                    
                                    <!-- Toggle Status Button (Form with POST) -->
                                    @if($post->status !== 'published')
                                        <form method="POST" action="{{ route('admin.blogs.blog.toggle-status', $post) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="group relative inline-flex items-center justify-center p-2 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-all duration-200 hover:scale-110"
                                                    title="Publish Post">
                                                <i class="fas fa-check-circle text-lg"></i>
                                                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-emerald-500 group-hover:w-4 transition-all duration-300"></div>
                                            </button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.blogs.blog.toggle-status', $post) }}" class="inline">
                                            @csrf
                                            <button type="submit"
                                                    class="group relative inline-flex items-center justify-center p-2 text-gray-400 hover:text-amber-600 dark:hover:text-amber-400 transition-all duration-200 hover:scale-110"
                                                    title="Unpublish Post">
                                                <i class="fas fa-pause-circle text-lg"></i>
                                                <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-amber-500 group-hover:w-4 transition-all duration-300"></div>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('admin.blogs.blog.destroy', $post) }}" class="inline">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="group relative inline-flex items-center justify-center p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 hover:scale-110"
                                                onclick="return confirm('Are you sure you want to delete this post?')"
                                                title="Delete">
                                            <i class="fas fa-trash text-lg"></i>
                                            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-red-500 group-hover:w-4 transition-all duration-300"></div>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-newspaper text-3xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">No blog posts found</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first post</p>
                                    <a href="{{ route('admin.blogs.blog.create') }}" 
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transform hover:-translate-y-1 transition-all duration-300">
                                        <i class="fas fa-plus"></i>
                                        Create First Post
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($posts->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 px-6 py-6 border-t border-gray-200 dark:border-gray-700/50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        <span class="font-medium">
                            Showing {{ ($posts->currentPage() - 1) * $posts->perPage() + 1 }} to 
                            {{ min($posts->total(), $posts->currentPage() * $posts->perPage()) }} 
                            of {{ $posts->total() }} results
                        </span>
                    </div>
                    <div class="flex items-center justify-center sm:justify-end space-x-2">
                        {{ $posts->onEachSide(2)->links('vendor.pagination.tailwind-custom') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection