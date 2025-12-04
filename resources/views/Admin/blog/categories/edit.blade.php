@extends('layouts.admin')

@section('title', 'Edit Blog Category')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-950 p-6">
    <div class="max-w-7xl mx-auto">
        
        <!-- Header (SAME as Index/Create) -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl shadow-lg">
                    <i class="fas fa-edit text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Edit Category
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Update "{{ $category->name }}" details
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.blogs.categories.index') }}">
                    <button class="group flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>All Categories</span>
                    </button>
                </a>
            </div>
        </div>

        <!-- Form Card (SAME style as Index Table) -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-6 py-6">
                <h2 class="text-xl font-semibold text-white flex items-center gap-3">
                    <i class="fas fa-cog"></i>
                    Update Category Details
                </h2>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('admin.blogs.categories.update', $category) }}" class="p-8">
                @csrf @method('PATCH')
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Category Name -->
                    <div>
                        <label class="block mb-4">
                            <span class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <i class="fas fa-tag text-emerald-500"></i>
                                Category Name
                            </span>
                            <input 
                                type="text" 
                                name="name" 
                                value="{{ old('name', $category->name) }}"
                                required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 dark:text-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300 @error('name') border-red-500 @enderror"
                                placeholder="e.g. Education, Health, Community"
                            >
                            @error('name')
                                <div class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-2">
                                    <i class="fas fa-exclamation-circle"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </label>
                    </div>

                    <!-- Category Color -->
                    <div>
                        <label class="block mb-4">
                            <span class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <i class="fas fa-palette text-purple-500"></i>
                                Category Color
                            </span>
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" 
                                     style="background-color: {{ $category->color }};">
                                    <i class="fas fa-circle text-white text-sm"></i>
                                </div>
                                <input 
                                    type="color" 
                                    name="color" 
                                    value="{{ old('color', $category->color) }}" 
                                    required
                                    class="w-full h-14 rounded-xl border-2 border-gray-200 dark:border-gray-600 shadow-sm cursor-pointer transition-all duration-300 hover:shadow-md focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                    title="Choose category color"
                                >
                            </div>
                            <div class="mt-3 flex items-center justify-center">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-purple-500 to-indigo-600 text-white shadow-sm" 
                                      id="colorPreview" 
                                      style="background-color: {{ $category->color }}; color: white;">
                                    {{ $category->color }}
                                </span>
                            </div>
                        </label>
                    </div>

                    <!-- Description (Full Width) -->
                    <div class="lg:col-span-2">
                        <label class="block mb-4">
                            <span class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                <i class="fas fa-align-left text-blue-500"></i>
                                Description
                            </span>
                            <textarea 
                                name="description" 
                                rows="4"
                                class="w-full px-4 py-3 dark:text-gray-200 bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300"
                                placeholder="Brief description of this category...">{{ old('description', $category->description) }}</textarea>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 flex items-center gap-2">
                                <i class="fas fa-info-circle"></i>
                                Helps your team understand what posts belong in this category
                            </p>
                        </label>
                    </div>

                </div>

                <!-- Live Preview (SAME as Create Page) -->
                <div class="mt-8 p-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 rounded-2xl border border-gray-200/50 dark:border-gray-700/50">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-eye text-indigo-500"></i>
                        Live Preview
                    </h3>
                    <div class="flex flex-col sm:flex-row gap-8 items-start justify-center">
                        
                        <!-- Badge Preview -->
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                                <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-sm" 
                                     id="previewBadge" 
                                     style="background-color: {{ $category->color }}; color: white;">
                                    {{ $category->name }}
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 max-w-xs">
                                Category badge on blog posts
                            </p>
                        </div>

                        <!-- Icon Preview -->
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-4 p-4 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" 
                                     id="previewIcon" 
                                     style="background-color: {{ $category->color }};">
                                    <i class="fas fa-folder text-white text-lg font-semibold"></i>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600 dark:text-gray-400 max-w-xs">
                                Category icon in lists
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row sm:justify-end gap-4 pt-8 border-t border-gray-200 dark:border-gray-700/50 mt-8">
                    <a href="{{ route('admin.blogs.categories.index') }}" 
                       class="flex items-center justify-center gap-2 px-6 py-3 bg-gray-500/20 dark:bg-gray-600/30 text-gray-700 dark:text-gray-300 font-semibold rounded-xl border border-gray-300 dark:border-gray-600 hover:bg-gray-500/30 dark:hover:bg-gray-600/40 transition-all duration-300">
                        <i class="fas fa-times"></i>
                        Cancel
                    </a>
                    <button 
                        type="submit"
                        class="group flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-save"></i>
                        <span>Update Category</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- <!-- Category Stats (Bonus Info) -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-500 to-indigo-600 px-6 py-6">
                <h3 class="text-xl font-semibold text-white flex items-center gap-3">
                    <i class="fas fa-chart-bar"></i>
                    Category Stats
                </h3>
            </div>
            <div class="p-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mb-2">
                        {{ $category->posts_count }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Published Posts</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-2">
                        {{ $category->created_at->diffForHumans() }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Created</p>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-orange-600 dark:text-orange-400 mb-2">
                        {{ $category->updated_at->diffForHumans() }}
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Last Updated</p>
                </div>
            </div>
        </div> --}}
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.querySelector('input[type="color"]');
    const nameInput = document.querySelector('input[name="name"]');
    const previewBadge = document.getElementById('previewBadge');
    const previewIcon = document.getElementById('previewIcon');
    const colorPreview = document.getElementById('colorPreview');
    
    function updatePreview() {
        const color = colorInput.value;
        const name = nameInput.value || '{{ $category->name }}';
        
        // Update badge preview
        previewBadge.textContent = name;
        previewBadge.style.backgroundColor = color;
        previewBadge.style.color = getContrastYIQ(color) === 'dark' ? '#ffffff' : '#1f2937';
        
        // Update icon preview
        previewIcon.style.backgroundColor = color;
        
        // Update color preview badge
        colorPreview.textContent = color.toUpperCase();
        colorPreview.style.backgroundColor = color;
        colorPreview.style.color = getContrastYIQ(color) === 'dark' ? '#ffffff' : '#1f2937';
    }
    
    // Update preview on input change
    colorInput.addEventListener('input', updatePreview);
    nameInput.addEventListener('input', updatePreview);
    
    // Initial preview
    updatePreview();
    
    // Contrast calculation for text color
    function getContrastYIQ(color) {
        const r = parseInt(color.substr(1,2), 16);
        const g = parseInt(color.substr(3,2), 16);
        const b = parseInt(color.substr(5,2), 16);
        const yiq = ((r * 299) + (g * 587) + (b * 114)) / 1000;
        return yiq >= 128 ? 'light' : 'dark';
    }
});
</script>
@endpush
@endsection