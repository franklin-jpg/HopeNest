@extends('layouts.admin')

@section('title', 'Edit Blog Tag')

@section('content')
<div class="min-h-screen  dark:from-slate-900 dark:via-slate-800 dark:to-indigo-950">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                        <span class="inline-flex items-center">
                            <svg class="w-8 h-8 mr-3 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit Tag
                        </span>
                    </h1>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Update the tag name. <span class="font-medium text-amber-600 dark:text-amber-400">Posts will automatically update</span>
                    </p>
                </div>
                <div class="mt-6 sm:mt-0 flex gap-4">
                    <a href="{{ route('admin.blogs.tags.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700/80 hover:bg-gray-50 dark:hover:bg-slate-600/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Tags
                    </a>
                </div>
            </div>
        </div>

        <!-- Tag Info Card -->
        <div class="max-w-2xl mx-auto mb-8">
            <div class="bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-emerald-950/20 dark:to-blue-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <span class="inline-flex items-center justify-center h-12 w-12 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold text-sm">
                                {{ strtoupper(substr($tag->name, 0, 2)) }}
                            </span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $tag->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Created {{ $tag->created_at->format('M d, Y') }}
                                </span>
                                @if($tag->posts_count > 0)
                                <span class="ml-4 inline-flex items-center text-emerald-600 dark:text-emerald-400">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $tag->posts_count }} post{{ $tag->posts_count !== 1 ? 's' : '' }}
                                </span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="max-w-2xl mx-auto">
            <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden backdrop-blur-sm">
                <form action="{{ route('admin.blogs.tags.update', $tag) }}" method="POST" class="p-8">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Tag Name -->
                    <div class="space-y-6">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <span class="inline-flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Tag Name
                                </span>
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       name="name" 
                                       id="name"
                                       value="{{ old('name', $tag->name) }}"
                                       class="w-full px-5 py-4 border border-gray-300 dark:border-slate-600 rounded-xl shadow-sm bg-white dark:bg-slate-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('name') border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/10 @enderror"
                                       placeholder="Enter tag name..."
                                       required>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        <!-- Current Slug -->
                        <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-950/20 dark:to-indigo-950/20 rounded-xl border border-blue-100 dark:border-blue-900/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-sm font-medium text-blue-900 dark:text-blue-300 mb-1">Current Slug</label>
                                    <code class="text-sm font-mono bg-white dark:bg-slate-700/50 px-3 py-1.5 rounded-lg border border-blue-200 dark:border-blue-800/50 text-blue-800 dark:text-blue-300">
                                        /blog/tag/{{ $tag->slug }}
                                    </code>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        Will auto-update
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Slug Preview -->
                        <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-950/20 dark:to-pink-950/20 rounded-xl border border-purple-100 dark:border-purple-900/30">
                            <div class="flex items-center justify-between">
                                <div>
                                    <label class="block text-sm font-medium text-purple-900 dark:text-purple-300 mb-1">New Slug Preview</label>
                                    <code class="text-sm font-mono bg-white dark:bg-slate-700/50 px-3 py-1.5 rounded-lg border border-purple-200 dark:border-purple-800/50 text-purple-800 dark:text-purple-300">
                                        /blog/tag/<span id="slug-preview" class="font-semibold">{{ Str::slug(old('name', $tag->name)) ?: $tag->slug }}</span>
                                    </code>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-8 border-t border-gray-100 dark:border-slate-700/50 mt-8">
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            @if($tag->posts_count > 0)
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $tag->posts_count }} post{{ $tag->posts_count !== 1 ? 's' : '' }} will be updated automatically
                            </span>
                            @else
                            <span class="inline-flex items-center">
                                <svg class="w-4 h-4 mr-1 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Changes will be applied immediately
                            </span>
                            @endif
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('admin.blogs.tags.index') }}" 
                               class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700/80 hover:bg-gray-50 dark:hover:bg-slate-600/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-200 w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent shadow-sm text-sm font-medium rounded-xl text-white bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 dark:from-amber-500 dark:to-orange-500 dark:hover:from-amber-600 dark:hover:to-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200 transform hover:scale-105 w-full sm:w-auto">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span id="submit-text">Update Tag</span>
                                <svg id="loading-spinner" class="w-4 h-4 mr-2 hidden animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('name');
    const slugPreview = document.getElementById('slug-preview');
    const form = document.querySelector('form');
    const submitBtn = form.querySelector('button[type="submit"]');
    const submitText = document.getElementById('submit-text');
    const loadingSpinner = document.getElementById('loading-spinner');

    // Auto-generate slug preview
    nameInput.addEventListener('input', function() {
        const name = this.value.trim();
        if (name) {
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim('-');
            slugPreview.textContent = slug || 'example-tag';
        } else {
            slugPreview.textContent = 'example-tag';
        }
    });

    // Form submission with loading state
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        loadingSpinner.classList.remove('hidden');
        submitText.textContent = 'Updating...';
    });
});
</script>
@endsection