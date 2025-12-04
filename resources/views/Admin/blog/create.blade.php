@extends('layouts.admin')

@section('title', 'Create Blog Post')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-950">
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white tracking-tight">
                        <span class="inline-flex items-center">
                            <svg class="w-8 h-8 mr-3 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10a2 2 0 01-2 2H6a2 2 0 01-2-2V4a2 2 0 012-2h8a2 2 0 012 2v8"></path>
                            </svg>
                            Create New Post
                        </span>
                    </h1>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        Write a compelling blog post to engage your audience
                    </p>
                </div>
                <div class="mt-6 sm:mt-0 flex gap-4">
                    <a href="{{ route('admin.blogs.blog.index') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700/80 hover:bg-gray-50 dark:hover:bg-slate-600/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Posts
                    </a>
                </div>
            </div>
        </div>

        <!-- FORM STARTS HERE - Wraps entire grid -->
        <form action="{{ route('admin.blogs.blog.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form Content -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden backdrop-blur-sm">
                        <div class="p-8">
                            <!-- Basic Info -->
                            <div class="space-y-8">
                                <!-- Title -->
                                <div>
                                    <label for="title" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                        <span class="inline-flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            Post Title
                                        </span>
                                    </label>
                                    <input type="text" 
                                           name="title" 
                                           id="title"
                                           value="{{ old('title') }}"
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-slate-600 rounded-xl shadow-sm bg-white dark:bg-slate-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('title') border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/10 @enderror"
                                           placeholder="Enter a compelling post title..."
                                           required>
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <!-- Featured Image -->
                                <div>
                                    <label for="featured_image" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                        <span class="inline-flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Featured Image
                                        </span>
                                    </label>
                                    <input type="file" 
                                           name="featured_image" 
                                           id="featured_image"
                                           accept="image/*"
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-slate-600 rounded-xl shadow-sm bg-white dark:bg-slate-700/50 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-slate-700/50 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-slate-600/50 transition-all duration-200 @error('featured_image') border-red-300 dark:border-red-500 @enderror">
                                    @error('featured_image')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1 inline text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        Recommended size: 1200x630px (16:9 aspect ratio)
                                    </p>
                                </div>
     
                                <!-- Content (EasyMDE) -->
                                <div>
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                        Content <span class="text-red-500">*</span>
                                    </label>
                                    <textarea id="markdown-editor" name="content" rows="8"
                                        class="w-full border rounded-lg px-4 py-3 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-primary focus:border-transparent @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                                    @error('content')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status & Publish -->
                    <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 backdrop-blur-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Publish
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <select name="status" id="status" class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-200">
                                    <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                </select>
                            </div>
                            <div id="publish-date-group" class="space-y-2" style="display: none;">
                                <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Publish Date</label>
                                <input type="datetime-local" 
                                       name="published_at" 
                                       id="published_at"
                                       value="{{ old('published_at') }}"
                                       class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transform hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span id="publish-text">Publish</span>
                            </button>
                        </div>
                    </div>

                    <!-- Categories & Tags -->
                    <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 backdrop-blur-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Categories & Tags</h3>
                        
                        <!-- Categories -->
                        <div class="space-y-4 mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categories</label>
                            @foreach($categories as $category)
                            <label class="flex items-center p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-200 dark:border-slate-600 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-slate-600/50 cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded border-gray-300 dark:border-slate-600 text-blue-600 shadow-sm focus:ring-blue-500" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-white mr-2"
                                          style="background-color: {{ $category->color }};">
                                        {{ $category->name }}
                                    </span>
                                </span>
                            </label>
                            @endforeach
                        </div>

                        <!-- Tags -->
                        <div class="space-y-3">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Tags</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach($tags as $tag)
                                <label class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-slate-700/50 rounded-full border border-gray-200 dark:border-slate-600 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-600/50 transition-all duration-200 cursor-pointer">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="rounded border-gray-300 dark:border-slate-600 text-emerald-600 shadow-sm focus:ring-emerald-500 mr-2" {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
                                    {{ $tag->name }}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 backdrop-blur-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            SEO Settings
                        </h3>
                        <div class="space-y-4">
                            <div>
                                <label for="seo_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SEO Title (60 chars max)</label>
                                <input type="text" 
                                       name="seo_title" 
                                       id="seo_title"
                                       value="{{ old('seo_title') }}"
                                       class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                       placeholder="Leave empty to auto-generate"
                                       maxlength="60">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    <span id="seo-title-count">0</span>/60 characters
                                </p>
                            </div>
                            <div>
                                <label for="seo_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SEO Description (160 chars max)</label>
                                <textarea name="seo_description" 
                                          id="seo_description"
                                          rows="3"
                                          class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                          placeholder="Leave empty to auto-generate from excerpt"
                                          maxlength="160">{{ old('seo_description') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    <span id="seo-desc-count">0</span>/160 characters
                                </p>
                            </div>
                            <div>
                                <label for="seo_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keywords (comma separated)</label>
                                <input type="text" 
                                       name="seo_keywords" 
                                       id="seo_keywords"
                                       value="{{ old('seo_keywords') }}"
                                       class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                       placeholder="blog, technology, tutorial, laravel">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- FORM ENDS HERE -->
    </div>
</div>

{{-- EasyMDE CSS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.css">
<script src="https://cdn.jsdelivr.net/npm/easymde@2.18.0/dist/easymde.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize EasyMDE
    const editor = new EasyMDE({
        element: document.getElementById('markdown-editor'),
        spellChecker: false,
        placeholder: "Tell your story in detail... (Markdown supported)",
        toolbar: ["bold", "italic", "heading", "|", "quote", "unordered-list", "ordered-list", "|", "link", "image", "|", "preview", "side-by-side", "fullscreen", "|", "guide"]
    });

    // Status change handler
    const statusSelect = document.getElementById('status');
    const publishDateGroup = document.getElementById('publish-date-group');
    const publishText = document.getElementById('publish-text');
    
    function togglePublishDate() {
        const status = statusSelect.value;
        if (status === 'scheduled') {
            publishDateGroup.style.display = 'block';
            publishText.textContent = 'Schedule';
        } else if (status === 'published') {
            publishDateGroup.style.display = 'none';
            publishText.textContent = 'Publish Now';
        } else {
            publishDateGroup.style.display = 'none';
            publishText.textContent = 'Save as Draft';
        }
    }
    
    statusSelect.addEventListener('change', togglePublishDate);
    togglePublishDate(); // Initial check

    // SEO character counters
    const seoTitleInput = document.getElementById('seo_title');
    const seoTitleCount = document.getElementById('seo-title-count');
    const seoDescInput = document.getElementById('seo_description');
    const seoDescCount = document.getElementById('seo-desc-count');

    function updateSeoTitleCount() {
        seoTitleCount.textContent = seoTitleInput.value.length;
        if (seoTitleInput.value.length > 50) {
            seoTitleCount.classList.add('text-red-500');
        } else {
            seoTitleCount.classList.remove('text-red-500');
        }
    }

    function updateSeoDescCount() {
        seoDescCount.textContent = seoDescInput.value.length;
        if (seoDescInput.value.length > 150) {
            seoDescCount.classList.add('text-red-500');
        } else {
            seoDescCount.classList.remove('text-red-500');
        }
    }

    seoTitleInput.addEventListener('input', updateSeoTitleCount);
    seoDescInput.addEventListener('input', updateSeoDescCount);
    updateSeoTitleCount();
    updateSeoDescCount();
});
</script>
@endsection