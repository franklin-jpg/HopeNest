@extends('layouts.admin')

@section('title', 'Blog Tags')

@section('content')
<div class="min-h-screen  dark:from-slate-900 dark:via-slate-800 dark:to-indigo-950">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Blog Tags
                    </h1>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Manage your blog tags and their associated posts
                    </p>
                </div>
                <div class="mt-6 sm:mt-0">
                   <a href="{{ route('admin.blogs.tags.create') }}">
                     <button 
                        data-modal-target="tagModal" 
                        data-modal-toggle="tagModal"
                        type="button"
                        class="inline-flex items-center px-6 py-3 border border-transparent shadow-sm text-base font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-500 dark:to-indigo-500 dark:hover:from-blue-600 dark:hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add New Tag
                    </button>
                   </a>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 backdrop-blur-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-blue-100 dark:bg-blue-900/20">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Tags</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tags->total() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 backdrop-blur-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-emerald-100 dark:bg-emerald-900/20">
                        <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Active Tags</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tags->where('posts_count', '>', 0)->count() }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 backdrop-blur-sm">
                <div class="flex items-center">
                    <div class="p-3 rounded-xl bg-amber-100 dark:bg-amber-900/20">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-300">Posts with Tags</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $tags->sum('posts_count') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tags Table -->
        <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden backdrop-blur-sm">
            <div class="px-6 py-4 border-b border-gray-100 dark:border-slate-700/50">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Tags</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-slate-700/50">
                    <thead class="bg-gray-50 dark:bg-slate-700/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tag Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Posts</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-slate-800 divide-y divide-gray-100 dark:divide-slate-700/50">
                        @forelse($tags as $tag)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-slate-700/30 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <span class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-blue-600 dark:to-indigo-700 text-white font-semibold text-sm">
                                            {{ strtoupper(substr($tag->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $tag->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ $tag->slug }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium {{ $tag->posts_count > 0 ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300' : 'bg-gray-100 dark:bg-slate-700/50 text-gray-600 dark:text-gray-400' }}">
                                    {{ $tag->posts_count }} post{{ $tag->posts_count != 1 ? 's' : '' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $tag->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('admin.blogs.tags.edit', $tag) }}" 
                                   class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-slate-600 shadow-sm text-sm leading-4 font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700/80 hover:bg-gray-50 dark:hover:bg-slate-600/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-200"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.blogs.tags.destroy', $tag) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Are you sure you want to delete this tag?')"
                                            class="inline-flex items-center px-3 py-1.5 border border-red-300 dark:border-red-600/50 shadow-sm text-sm leading-4 font-medium rounded-xl text-red-700 dark:text-red-300 bg-white dark:bg-slate-700/80 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:focus:ring-red-400 transition-all duration-200"
                                    >
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-16 h-16 text-gray-300 dark:text-slate-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No tags</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating your first tag.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('admin.blogs.tags.create') }}">
                                              <button 
                                            data-modal-target="tagModal" 
                                            data-modal-toggle="tagModal"
                                            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 dark:from-blue-500 dark:to-indigo-500 dark:hover:from-blue-600 dark:hover:to-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                        >
                                            Create Tag
                                        </button>
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            @if($tags->hasPages())
            <div class="px-6 py-4 bg-gray-50 dark:bg-slate-700/50 border-t border-gray-100 dark:border-slate-700/50">
                {{ $tags->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('tagModal');
    const form = document.getElementById('tagForm');
    const title = document.getElementById('tagModalTitle');
    const submitText = document.getElementById('submitText');
    const loadingText = document.getElementById('loadingText');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Open create modal
    const openButtons = document.querySelectorAll('[data-modal-target="tagModal"]');
    openButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            form.action = '{{ route("admin.blogs.tags.store") }}';
            document.getElementById('tagId').value = '';
            document.getElementById('name').value = '';
            title.textContent = 'Create Tag';
            submitText.textContent = 'Create Tag';
            modal.classList.remove('hidden');
            document.getElementById('name').focus();
        });
    });

    // Form submission
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        loadingText.classList.remove('hidden');
        submitText.classList.add('hidden');
    });

    // Close modal
    const closeButtons = document.querySelectorAll('[data-modal-hide="tagModal"]');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            modal.classList.add('hidden');
            submitBtn.disabled = false;
            loadingText.classList.add('hidden');
            submitText.classList.remove('hidden');
        });
    });

    // Close on outside click
    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.classList.add('hidden');
            submitBtn.disabled = false;
            loadingText.classList.add('hidden');
            submitText.classList.remove('hidden');
        }
    });
});
</script>
@endsection