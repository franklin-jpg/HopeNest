@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-950 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-8">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl shadow-lg">
                    <i class="fas fa-folder-open text-2xl text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                        Blog Categories
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Organize your blog posts with colorful categories
                    </p>
                </div>
            </div>
            
           <a href="{{ route('admin.blogs.categories.create') }}">
             <button class="group flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transform hover:-translate-y-1 transition-all duration-300" 
                    data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="fas fa-plus"></i>
                <span>New Category</span>
            </button>
           </a>
        </div>

        <!-- Stats Card -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Categories</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">
                            {{ $categories->total() }}
                        </p>
                    </div>
                    <div class="p-4 bg-purple-100 dark:bg-purple-900/20 rounded-xl">
                        <i class="fas fa-folder text-purple-600 dark:text-purple-400 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Posts</p>
                        <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-400 mt-2">
                            {{ \App\Models\BlogCategory::withCount('posts')->get()->sum('posts_count') }}
                        </p>
                    </div>
                    <div class="p-4 bg-emerald-100 dark:bg-emerald-900/20 rounded-xl">
                        <i class="fas fa-newspaper text-emerald-600 dark:text-emerald-400 text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700/50">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Posts/Category</p>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400 mt-2">
                            {{ $categories->total() > 0 ? round(\App\Models\BlogCategory::withCount('posts')->get()->avg('posts_count'), 1) : 0 }}
                        </p>
                    </div>
                    <div class="p-4 bg-blue-100 dark:bg-blue-900/20 rounded-xl">
                        <i class="fas fa-chart-bar text-blue-600 dark:text-blue-400 text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Grid -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 sticky top-0">
                        <tr>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Posts</th>
                            <th class="px-6 py-5 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Color</th>
                            <th class="px-6 py-5 text-right text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700/50">
                        @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-colors duration-200">
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm" 
                                         style="background-color: {{ $category->color }};">
                                        <i class="fas fa-folder text-white text-lg font-semibold"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">
                                            {{ $category->name }}
                                        </div>
                                        @if($category->description)
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                {{ Str::limit($category->description, 60) }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <code class="bg-gray-100 dark:bg-gray-700/50 px-3 py-1.5 rounded-lg text-sm font-mono text-gray-600 dark:text-gray-300">
                                    {{ $category->slug }}
                                </code>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-sm">
                                        {{ $category->posts_count }}
                                    </span>
                                    @if($category->posts_count > 0)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $category->posts_count }} post{{ $category->posts_count > 1 ? 's' : '' }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full shadow-sm flex items-center justify-center" 
                                         style="background-color: {{ $category->color }};">
                                        <i class="fas fa-circle text-white text-sm"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-mono text-gray-600 dark:text-gray-300">
                                            {{ $category->color }}
                                        </span>
                                        <div class="w-20 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden mt-1">
                                            <div class="h-full rounded-full" style="background-color: {{ $category->color }};"></div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <a href="{{ route('admin.blogs.categories.edit', $category) }}" 
                                       class="group relative inline-flex items-center justify-center p-3 text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all duration-200 hover:scale-110"
                                       title="Edit Category">
                                        <i class="fas fa-edit text-lg"></i>
                                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-blue-500 group-hover:w-8 transition-all duration-300 rounded-full"></div>
                                    </a>
                                    
                                    <form method="POST" action="{{ route('admin.blogs.categories.destroy', $category) }}" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" 
                                                class="group relative inline-flex items-center justify-center p-3 text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-all duration-200 hover:scale-110"
                                                onclick="return confirm('Are you sure you want to delete {{ $category->name }}? This cannot be undone!')"
                                                title="Delete Category">
                                            <i class="fas fa-trash text-lg"></i>
                                            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-0 h-0.5 bg-red-500 group-hover:w-8 transition-all duration-300 rounded-full"></div>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-2xl flex items-center justify-center mb-4">
                                        <i class="fas fa-folder-open text-3xl text-gray-400 dark:text-gray-500"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">No categories yet</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">
                                        Categories help organize your blog posts. Create your first category to get started!
                                    </p>
                                    <a href="{{ route('admin.blogs.categories.create') }}">
                                        <button class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-emerald-600 hover:to-green-700 transform hover:-translate-y-1 transition-all duration-300" 
                                            data-bs-toggle="modal" data-bs-target="#categoryModal">
                                        <i class="fas fa-plus"></i>
                                        Create First Category
                                    </button>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($categories->hasPages())
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 px-6 py-6 border-t border-gray-200 dark:border-gray-700/50">
                {{ $categories->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Enhanced Category Modal
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.blogs.categories.store') }}">
                @csrf
                <div class="modal-header bg-gradient-to-r from-emerald-500 to-green-600 text-white">
                    <h5 class="modal-title font-bold" id="categoryModalLabel">
                        <i class="fas fa-plus me-2"></i>New Category
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-gray-800 dark:text-white">Category Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="e.g. Education, Health, Community" required value="{{ old('name') }}">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-gray-800 dark:text-white">Color</label>
                            <div class="position-relative">
                                <input type="color" name="color" class="form-control form-control-color w-100" 
                                       value="#3B82F6" required title="Choose category color">
                                <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                                    <span class="badge fs-6" style="background-color: #3B82F6; color: white;" id="colorPreview">#3B82F6</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-gray-800 dark:text-white">Description (Optional)</label>
                            <textarea name="description" class="form-control" rows="2" 
                                      placeholder="Brief description of this category...">{{ old('description') }}</textarea>
                            <div class="form-text">Help your team understand what posts belong in this category</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-2"></i>Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> --}}

@push('scripts')
<script>
document.querySelector('input[type="color"]').addEventListener('input', function(e) {
    document.getElementById('colorPreview').style.backgroundColor = e.target.value;
    document.getElementById('colorPreview').textContent = e.target.value;
});
</script>
@endpush
@endsection