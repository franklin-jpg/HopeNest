@extends('layouts.admin')

@section('title', "Edit: {{ $blog->title }}")

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-slate-900 dark:via-slate-800 dark:to-indigo-950">
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
                            Edit Post
                        </span>
                    </h1>
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Last updated {{ $blog->updated_at->diffForHumans() }}
                        </span>
                        @if($blog->published_at)
                        • 
                        <span class="inline-flex items-center text-emerald-600 dark:text-emerald-400">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Published {{ $blog->published_at->format('M d, Y') }}
                        </span>
                        @endif
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
                    <a href="{{ route('admin.blogs.blog.show', $blog) }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-slate-600 shadow-sm text-sm font-medium rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-slate-700/80 hover:bg-gray-50 dark:hover:bg-slate-600/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Post
                    </a>
                </div>
            </div>
        </div>

        <!-- Post Preview Card -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="bg-gradient-to-r from-emerald-50 to-blue-50 dark:from-emerald-950/20 dark:to-blue-950/20 rounded-2xl border border-emerald-100 dark:border-emerald-900/30 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                    <div class="flex items-center flex-1">
                        @if($blog->featured_image)
                        <img src="{{ Storage::url($blog->featured_image) }}" 
                             alt="{{ $blog->title }}" 
                             class="w-20 h-20 rounded-xl object-cover shadow-lg flex-shrink-0">
                        @else
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center flex-shrink-0">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                        <div class="ml-4">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $blog->title }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                {{ Str::limit($blog->excerpt, 80) }}
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 justify-end lg:justify-start">
                        @foreach($blog->categories->take(3) as $category)
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium text-white shadow-sm"
                              style="background-color: {{ $category->color }};">
                            {{ $category->name }}
                        </span>
                        @endforeach
                        @if($blog->is_featured)
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-medium bg-gradient-to-r from-amber-400 to-yellow-500 text-white shadow-sm">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                            </svg>
                            Featured
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM STARTS HERE - Wraps entire grid -->
        <form action="{{ route('admin.blogs.blog.update', $blog) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 overflow-hidden backdrop-blur-sm">
                        <div class="p-8">
                            <!-- Title -->
                            <div class="space-y-8">
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
                                           value="{{ old('title', $blog->title) }}"
                                           class="w-full px-5 py-4 border border-gray-300 dark:border-slate-600 rounded-xl shadow-sm bg-white dark:bg-slate-700/50 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 @error('title') border-red-300 dark:border-red-500 bg-red-50 dark:bg-red-900/10 @enderror"
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
                                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">Featured Image</label>
                                    <div class="space-y-4">
                                        @if($blog->featured_image)
                                        <div class="relative group">
                                            <img src="{{ Storage::url($blog->featured_image) }}" 
                                                 alt="{{ $blog->title }}" 
                                                 class="w-full max-w-sm h-48 object-cover rounded-xl shadow-sm">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 rounded-xl transition-all duration-300 flex items-center justify-center opacity-0 group-hover:opacity-100">
                                                <span class="text-white text-sm font-medium bg-black bg-opacity-50 px-3 py-1 rounded-full">Current image</span>
                                            </div>
                                        </div>
                                        @endif
                                        <input type="file" 
                                               name="featured_image" 
                                               id="featured_image"
                                               accept="image/*"
                                               class="w-full px-5 py-4 border border-gray-300 dark:border-slate-600 rounded-xl shadow-sm bg-white dark:bg-slate-700/50 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-slate-700/50 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-slate-600/50 transition-all duration-200">
                                    </div>
                                </div>

                                <!-- Content -->
                                <div>
                                    <label for="content" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                        <span class="inline-flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Content
                                        </span>
                                    </label>
                                    <textarea name="content" id="content" class="w-full @error('content') border-red-300 dark:border-red-500 @enderror">{{ old('content', $blog->content) }}</textarea>
                                    @error('content')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar (same as create but with pre-filled values) -->
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
                                    <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="scheduled" {{ old('status', $blog->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                </select>
                            </div>
                            <div id="publish-date-group" class="space-y-2" style="display: {{ old('status', $blog->status) == 'scheduled' ? 'block' : 'none' }};">
                                <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Publish Date</label>
                                <input type="datetime-local" 
                                       name="published_at" 
                                       id="published_at"
                                       value="{{ old('published_at', $blog->published_at ? $blog->published_at->format('Y-m-d\TH:i') : '') }}"
                                       class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                            </div>
                            <button type="submit" class="w-full flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transform hover:-translate-y-1 transition-all duration-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span id="publish-text">Update Post</span>
                            </button>
                        </div>
                    </div>

                    <!-- Categories & Tags (pre-checked) -->
                    <div class="bg-white dark:bg-slate-800/80 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700/50 p-6 backdrop-blur-sm">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Categories & Tags</h3>
                        
                        <!-- Categories -->
                        <div class="space-y-4 mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Categories</label>
                            @foreach($categories as $category)
                            <label class="flex items-center p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl border border-gray-200 dark:border-slate-600 transition-all duration-200 hover:bg-gray-100 dark:hover:bg-slate-600/50 cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded border-gray-300 dark:border-slate-600 text-blue-600 shadow-sm focus:ring-blue-500" {{ $blog->categories->contains($category->id) || in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
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
                                <label class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-slate-700/50 rounded-full border border-gray-200 dark:border-slate-600 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-slate-600/50 transition-all duration-200 cursor-pointer {{ $blog->tags->contains($tag->id) ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border-emerald-300 dark:border-emerald-600' : '' }}">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="rounded border-gray-300 dark:border-slate-600 text-emerald-600 shadow-sm focus:ring-emerald-500 mr-2" {{ $blog->tags->contains($tag->id) || in_array($tag->id, old('tags', [])) ? 'checked' : '' }}>
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
                                <label for="seo_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SEO Title</label>
                                <input type="text" 
                                       name="seo_title" 
                                       id="seo_title"
                                       value="{{ old('seo_title', $seo['title'] ?? '') }}"
                                       class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                       maxlength="60">
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    <span id="seo-title-count">{{ strlen(old('seo_title', $seo['title'] ?? '')) }}</span>/60 characters
                                </p>
                            </div>
                            <div>
                                <label for="seo_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">SEO Description</label>
                                <textarea name="seo_description" 
                                          id="seo_description"
                                          rows="3"
                                          class="w-full px-4 py-3 dark:text-gray-300 bg-gray-50 dark:bg-slate-700/50 border border-gray-200 dark:border-slate-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-all duration-200"
                                          maxlength="160">{{ old('seo_description', $seo['description'] ?? '') }}</textarea>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    <span id="seo-desc-count">{{ strlen(old('seo_description', $seo['description'] ?? '')) }}</span>/160 characters
                                </p>
                            </div>
                            <div>
                                <label for="seo_keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Keywords</label>
                                <input type="text" 
                                       name="seo_keywords" 
                                       id="seo_keywords"
                                       value="{{ old('seo_keywords', $seo['keywords'] ?? '') }}"
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize EasyMDE with existing content
    const easyMDE = new EasyMDE({
        element: document.getElementById('content'),
        initialValue: `@if(old('content')){{ old('content') }}@else{{ $blog->content }}@endif`,
        autofocus: false,
        autosave: {
            enabled: true,
            delay: 5000,
            uniqueId: 'blog-post-{{ $blog->id }}'
        },
        sideBySideFullscreen: false,
        toolbar: [
            "bold", "italic", "heading", "|",
            "quote", "unordered-list", "ordered-list", "|",
            "link", "image", "table", "|",
            "preview", "side-by-side", "fullscreen", "|",
            "guide"
        ]
    });

    // Status change handler
    const statusSelect = document.getElementById('status');
    const publishDateGroup = document.getElementById('publish-date-group');
    
    function togglePublishDate() {
        if (statusSelect.value === 'scheduled') {
            publishDateGroup.style.display = 'block';
        } else {
            publishDateGroup.style.display = 'none';
        }
    }
    
    statusSelect.addEventListener('change', togglePublishDate);
    togglePublishDate();

    // SEO character counters
    const seoTitleInput = document.getElementById('seo_title');
    const seoTitleCount = document.getElementById('seo-title-count');
    const seoDescInput = document.getElementById('seo_description');
    const seoDescCount = document.getElementById('seo-desc-count');

    function updateSeoTitleCount() {
        seoTitleCount.textContent = seoTitleInput.value.length;
    }

    function updateSeoDescCount() {
        seoDescCount.textContent = seoDescInput.value.length;
    }

    seoTitleInput.addEventListener('input', updateSeoTitleCount);
    seoDescInput.addEventListener('input', updateSeoDescCount);
    updateSeoTitleCount();
    updateSeoDescCount();
});
</script>
@endsection