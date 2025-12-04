@extends('layouts.admin')

@section('title', 'Create Blog Category')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 
            dark:from-gray-950 dark:via-gray-900 dark:to-slate-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        
        <!-- Breadcrumb & Back Button -->
        <div class="flex items-center gap-4 mb-10">
            <a href="{{ route('admin.blogs.categories.index') }}" 
               class="group flex items-center gap-3 px-6 py-3 bg-white/80 dark:bg-gray-800/80 
                      backdrop-blur-sm border border-gray-200/50 dark:border-gray-700/50 
                      rounded-2xl shadow-lg hover:shadow-xl hover:bg-white dark:hover:bg-gray-800 
                      transition-all duration-300 transform hover:-translate-y-1">
                <i class="fas fa-arrow-left text-gray-500 group-hover:text-indigo-600 transition-colors"></i>
                <span class="font-semibold text-gray-700 dark:text-gray-300 group-hover:text-indigo-600">
                    All Categories
                </span>
            </a>
        </div>

        <!-- Hero Header -->
        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br 
                        from-emerald-500 to-teal-600 rounded-3xl shadow-2xl mb-6">
                <i class="fas fa-folder-plus text-3xl text-white"></i>
            </div>
            <h1 class="text-5xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-emerald-600 
                       bg-clip-text text-transparent mb-4">
                New Category
            </h1>
            <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto leading-relaxed">
                Create a beautiful, colorful category to organize your inspiring stories and impact reports
            </p>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl 
                    border border-white/20 dark:border-gray-700/50 overflow-hidden">
            
            <!-- Form Header -->
            <div class="  px-10 py-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-cog text-2xl text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-white">Category Details</h2>
                            <p class="text-indigo-100">Fill in the information below to create your category</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-4 justify-center lg:justify-end">
                        <div class="flex items-center gap-2 px-4 py-2 bg-white/20 rounded-2xl backdrop-blur-sm">
                            <i class="fas fa-circle-check text-emerald-400"></i>
                            <span class="text-white font-medium">Required fields</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Body -->
            <form method="POST" action="{{ route('admin.blogs.categories.store') }}" class="p-10">
                @csrf
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                    
                    <!-- Left Column -->
                    <div class="space-y-8">
                        
                        <!-- Category Name -->
                        <div>
                            <label class="block mb-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 
                                                rounded-2xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-tag text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                            Category Name
                                        </span>
                                        <span class="block text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                                            <i class="fas fa-asterisk text-xs mr-1"></i>Required
                                        </span>
                                    </div>
                                </div>
                                <input 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name') }}"
                                    required
                                    class="w-full px-6 py-5 text-lg bg-gradient-to-r from-gray-50/80 to-white/80 
                                           dark:from-gray-700/50 dark:to-gray-600/30
                                           border-2 border-gray-200/60 dark:border-gray-600/50 
                                           rounded-2xl shadow-inner backdrop-blur-sm
                                           focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500
                                           transition-all duration-300 @error('name') border-red-500 @enderror"
                                    placeholder="e.g. Education for All, Health & Wellness, Community Heroes"
                                >
                                @error('name')
                                    <div class="mt-3 p-4 bg-red-50/80 dark:bg-red-950/50 border border-red-200/50 dark:border-red-800/50 
                                                rounded-2xl backdrop-blur-sm">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 flex-shrink-0"></i>
                                            <p class="text-sm text-red-700 dark:text-red-300">{{ $message }}</p>
                                        </div>
                                    </div>
                                @enderror
                            </label>
                        </div>

                        <!-- Category Color -->
                        <div>
                            <label class="block mb-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-600 
                                                rounded-2xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-palette text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                            Category Color
                                        </span>
                                        <span class="block text-indigo-600 dark:text-indigo-400 text-sm font-medium">
                                            <i class="fas fa-asterisk text-xs mr-1"></i>Required
                                        </span>
                                    </div>
                                </div>
                                <div class="relative">
                                    <input 
                                        type="color" 
                                        name="color" 
                                        value="{{ old('color', '#3B82F6') }}" 
                                        required
                                        class="w-full h-20 rounded-2xl border-2 border-gray-200/60 dark:border-gray-600/50 
                                               shadow-xl cursor-pointer transition-all duration-300 hover:shadow-2xl
                                               focus:ring-4 focus:ring-purple-500/30 focus:border-purple-500"
                                        title="Click to choose a beautiful color for your category"
                                    >
                                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-r from-purple-500/20 to-indigo-500/20 
                                                blur opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>
                                </div>
                                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <i class="fas fa-info-circle text-blue-500"></i>
                                    Choose a color that represents your category's theme
                                </p>
                            </label>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="space-y-8">
                        
                        <!-- Description -->
                        <div>
                            <label class="block mb-4">
                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-emerald-600 
                                                rounded-2xl flex items-center justify-center shadow-lg">
                                        <i class="fas fa-align-left text-white text-xl"></i>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                            Description
                                        </span>
                                        <span class="block text-emerald-600 dark:text-emerald-400 text-sm font-medium">
                                            Optional
                                        </span>
                                    </div>
                                </div>
                                <textarea 
                                    name="description" 
                                    rows="6"
                                    class="w-full px-6 py-5 text-lg bg-gradient-to-r from-gray-50/80 to-white/80 
                                           dark:from-gray-700/50 dark:to-gray-600/30
                                           border-2 border-gray-200/60 dark:border-gray-600/50 
                                           rounded-2xl shadow-inner backdrop-blur-sm
                                           focus:ring-4 focus:ring-teal-500/20 focus:border-teal-500
                                           transition-all duration-300 resize-none"
                                    placeholder="Briefly describe what kind of posts belong in this category... 
(e.g. 'Stories about our education programs and student success')"
                                >{{ old('description') }}</textarea>
                                <p class="mt-3 text-sm text-gray-600 dark:text-gray-400 flex items-center gap-2">
                                    <i class="fas fa-lightbulb text-yellow-500"></i>
                                    Helps your team understand what content fits here
                                </p>
                            </label>
                        </div>

                        <!-- Preview Section -->
                        <div class="lg:col-span-2">
                            <div class="bg-gradient-to-r from-slate-50 to-indigo-50/30 dark:from-gray-800/50 dark:to-gray-700/30 
                                        rounded-2xl p-8 border-2 border-dashed border-gray-200/50 dark:border-gray-600/50">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-3">
                                    <i class="fas fa-eye text-indigo-500"></i>
                                    Live Preview
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Category Badge Preview -->
                                    <div class="text-center p-6 bg-white/60 dark:bg-gray-900/60 rounded-2xl shadow-sm">
                                        <div class="mb-4">
                                            <div class="inline-flex items-center px-6 py-3 rounded-full font-bold text-lg shadow-lg" 
                                                 id="previewBadge" 
                                                 style="background-color: #3B82F6; color: white;">
                                                Sample Category
                                            </div>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            This is how your category badge will look on blog posts
                                        </p>
                                    </div>

                                    <!-- Icon Preview -->
                                    <div class="text-center p-6 bg-white/60 dark:bg-gray-900/60 rounded-2xl shadow-sm">
                                        <div class="w-20 h-20 mx-auto mb-4 rounded-2xl shadow-lg flex items-center justify-center" 
                                             id="previewIcon" 
                                             style="background-color: #3B82F6;">
                                            <i class="fas fa-folder text-2xl text-white font-bold"></i>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            Category icon appearance in sidebar and lists
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 pt-12 
                            border-t-2 border-gray-100/50 dark:border-gray-700/50 mt-12">
                    <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-lock text-emerald-500"></i>
                            <span>Secure & Private</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-bolt text-yellow-500"></i>
                            <span>Instantly Available</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('admin.blogs.categories.index') }}" 
                           class="flex items-center justify-center gap-3 px-8 py-4 
                                  bg-gradient-to-r from-gray-500/80 to-gray-600/80 
                                  text-white font-bold rounded-2xl shadow-lg 
                                  hover:shadow-xl hover:from-gray-600/90 hover:to-gray-700/90
                                  transition-all duration-300 transform hover:-translate-y-1">
                            <i class="fas fa-times"></i>
                            Cancel
                        </a>
                        <button 
                            type="submit"
                            class="group flex items-center justify-center gap-4 px-10 py-4 
                                   bg-gradient-to-r from-emerald-500 via-teal-600 to-indigo-600 
                                   text-white font-bold rounded-2xl shadow-2xl 
                                   hover:shadow-3xl hover:from-emerald-600 hover:via-teal-700 hover:to-indigo-700
                                   transition-all duration-300 transform hover:-translate-y-2
                                   focus:ring-4 focus:ring-emerald-500/30">
                            <i class="fas fa-check-circle group-hover:scale-110 transition-transform"></i>
                            <span class="tracking-wide">Create Category</span>
                            <i class="fas fa-sparkles text-yellow-300/80 group-hover:text-yellow-300/100 
                                   transition-colors"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorInput = document.querySelector('input[type="color"]');
    const nameInput = document.querySelector('input[name="name"]');
    const previewBadge = document.getElementById('previewBadge');
    const previewIcon = document.getElementById('previewIcon');
    
    function updatePreview() {
        const color = colorInput.value;
        const name = nameInput.value || 'Sample Category';
        
        // Update badge preview
        previewBadge.textContent = name;
        previewBadge.style.backgroundColor = color;
        previewBadge.style.color = getContrastYIQ(color) === 'dark' ? '#ffffff' : '#1f2937';
        
        // Update icon preview
        previewIcon.style.backgroundColor = color;
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