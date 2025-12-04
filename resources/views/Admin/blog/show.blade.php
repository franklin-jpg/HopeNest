@extends('layouts.admin')

@section('title', $blog->title)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-950 p-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6 mb-8">
            <div class="flex-1">
                <div class="flex items-center gap-4">
                    <div class="p-3 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl shadow-lg">
                        <i class="fas fa-eye text-2xl text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                            {{ $blog->title }}
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 flex items-center gap-4">
                            <span class="flex items-center gap-1">
                                <i class="fas fa-calendar-alt"></i>
                                {{ $blog->published_at ? $blog->published_at->format('M d, Y') : 'Not published' }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-eye"></i>
                                {{ number_format($blog->views_count ?? 0) }} views
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="fas fa-clock"></i>
                                {{ $blog->reading_time ?? '3' }} min read
                            </span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.blogs.blog.edit', $blog) }}" 
                   class="group flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-edit"></i>
                    <span>Edit Post</span>
                </a>
                <a href="{{ route('admin.blogs.blog.index') }}" 
                   class="group flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-gray-600 hover:to-gray-700 transform hover:-translate-y-1 transition-all duration-300">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Posts</span>
                </a>
            </div>
        </div>

        <!-- Main Content & Sidebar -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                    <!-- Featured Image -->
                    @if($blog->featured_image)
                    <div class="relative overflow-hidden bg-gradient-to-r from-blue-600/10 to-indigo-600/10">
                        <img src="{{ Storage::url($blog->featured_image) }}" 
                             alt="{{ $blog->title }}" 
                             class="w-full h-64 object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent"></div>
                        @if($blog->is_featured)
                        <div class="absolute top-4 right-4 inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-medium bg-gradient-to-r from-amber-400 to-yellow-500 text-white shadow-lg">
                            <i class="fas fa-star"></i>
                            Featured
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Content -->
                    <div class="p-8 prose prose-sm dark:prose-invert max-w-none">
                        <div class="markdown-content">
                            {!! $blog->content_html ?? nl2br(e($blog->content)) !!}
                        </div>
                    </div>

                    <!-- Post Meta -->
                    <div class="px-8 py-6 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800/50 dark:to-gray-700/50 border-t border-gray-200 dark:border-gray-700/50">
                        <div class="flex flex-wrap items-center justify-between gap-6">
                            <!-- Categories -->
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-folder"></i>
                                    Categories:
                                </span>
                                @forelse($blog->categories as $category)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium text-white shadow-sm"
                                          style="background-color: {{ $category->color }};">
                                        {{ $category->name }}
                                    </span>
                                @empty
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500">
                                        <i class="fas fa-folder-open mr-1"></i> No categories
                                    </span>
                                @endforelse
                            </div>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-700 rounded-xl shadow-sm border border-gray-200 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <i class="fas fa-tags"></i>
                                    Tags:
                                </span>
                                @forelse($blog->tags as $tag)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700/50 shadow-sm">
                                        #{{ $tag->name }}
                                    </span>
                                @empty
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-500">
                                        <i class="fas fa-tag mr-1"></i> No tags
                                    </span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Posts -->
                @if($relatedPosts->count() > 0)
                <div class="mt-8">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 overflow-hidden">
                        <div class="px-8 py-6 bg-gradient-to-r from-indigo-500 to-purple-600">
                            <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                                <i class="fas fa-thumbs-up"></i>
                                Related Posts
                            </h2>
                        </div>
                        <div class="p-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($relatedPosts as $related)
                                <div class="group relative overflow-hidden rounded-xl bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-700/50 border border-gray-200 dark:border-gray-700/50 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                                    @if($related->featured_image)
                                    <div class="relative h-40 overflow-hidden">
                                        <img src="{{ Storage::url($related->featured_image) }}" 
                                             alt="{{ $related->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    </div>
                                    @endif
                                    <div class="p-6">
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @forelse($related->categories->take(2) as $category)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white shadow-sm"
                                                  style="background-color: {{ $category->color }};">
                                                {{ $category->name }}
                                            </span>
                                            @endforeach
                                        </div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white text-sm leading-tight mb-2 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors duration-200">
                                            {{ $related->title }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                                            {{ $related->excerpt }}
                                        </p>
                                        <a href="{{ route('admin.blogs.blog.show', $related) }}" 
                                           class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors duration-200">
                                            <span>Read more</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Status & Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-6 sticky top-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-cog text-emerald-600"></i>
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <!-- Status Toggle -->
                        <form method="POST" action="{{ route('admin.blogs.blog.toggle-status', $blog) }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 @if($blog->status === 'published') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-lg hover:shadow-xl hover:-translate-y-1 hover:from-emerald-600 hover:to-green-700 @else bg-gradient-to-r from-gray-500 to-gray-600 text-white shadow-lg hover:shadow-xl hover:-translate-y-1 hover:from-gray-600 hover:to-gray-700 @endif">
                                @if($blog->status === 'published')
                                    <i class="fas fa-pause-circle"></i>
                                    <span>Unpublish</span>
                                @else
                                    <i class="fas fa-play-circle"></i>
                                    <span>Publish Now</span>
                                @endif
                            </button>
                        </form>

                        <!-- Edit -->
                        <a href="{{ route('admin.blogs.blog.edit', $blog) }}" 
                           class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 hover:from-blue-600 hover:to-indigo-700 transition-all duration-300">
                            <i class="fas fa-edit"></i>
                            <span>Edit Post</span>
                        </a>

                        <!-- Delete -->
                        <form method="POST" action="{{ route('admin.blogs.blog.destroy', $blog) }}" class="inline w-full">
                            @csrf @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-gradient-to-r from-red-500 to-rose-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 hover:from-red-600 hover:to-rose-700 transition-all duration-300"
                                    onclick="return confirm('Are you sure you want to delete this post? This action cannot be undone.')">
                                <i class="fas fa-trash"></i>
                                <span>Delete Post</span>
                            </button>
                        </form>
                    </div>

                    <!-- Current Status -->
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700/50">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
                            <i class="fas fa-info-circle"></i>
                            Post Status
                        </h4>
                        @php $statusClass = match($blog->status) {
                            'published' => 'bg-gradient-to-r from-emerald-500 to-green-600',
                            'scheduled' => 'bg-gradient-to-r from-blue-500 to-indigo-600',
                            'draft' => 'bg-gradient-to-r from-gray-400 to-gray-500',
                            default => 'bg-gradient-to-r from-gray-400 to-gray-500'
                        }; @endphp
                        <div class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium text-white shadow-sm {{ $statusClass }}">
                            @if($blog->status === 'published')
                                <i class="fas fa-check-circle mr-2"></i> Published
                            @elseif($blog->status === 'scheduled')
                                <i class="fas fa-clock mr-2"></i> Scheduled
                                @if($blog->published_at)
                                    <span class="ml-2 text-xs opacity-90">({{ $blog->published_at->format('M d, Y H:i') }})</span>
                                @endif
                            @else
                                <i class="fas fa-edit mr-2"></i> Draft
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SEO Preview -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-3">
                        <i class="fas fa-search text-purple-600"></i>
                        SEO Preview
                    </h3>
                    <div class="bg-gradient-to-r from-gray-900 to-slate-800 rounded-xl p-6 text-white relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-purple-500 to-pink-500"></div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-400 to-pink-500 rounded-full flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-globe text-white text-sm"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg truncate max-w-[200px]" id="seo-title-preview">
                                    {{ $blog->seo_meta['title'] ?? $blog->title }}
                                </h4>
                                <p class="text-sm opacity-90 truncate max-w-[200px]" id="seo-url-preview">
                                    yoursite.com/blog/{{ $blog->slug }}
                                </p>
                            </div>
                        </div>
                        <p class="text-sm opacity-90 line-clamp-2" id="seo-description-preview">
                            {{ $blog->seo_meta['description'] ?? $blog->excerpt }}
                        </p>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-4 text-sm">
                        <div class="text-center">
                            <div class="font-mono text-xs text-gray-500 dark:text-gray-400 mb-1">Title</div>
                            <div class="font-semibold text-green-600 dark:text-green-400">
                                {{ strlen($blog->seo_meta['title'] ?? $blog->title) }}/60
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="font-mono text-xs text-gray-500 dark:text-gray-400 mb-1">Desc</div>
                            <div class="font-semibold text-blue-600 dark:text-blue-400">
                                {{ strlen($blog->seo_meta['description'] ?? $blog->excerpt) }}/160
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="font-mono text-xs text-gray-500 dark:text-gray-400 mb-1">Slug</div>
                            <div class="font-semibold text-purple-600 dark:text-purple-400 truncate">
                                /blog/{{ $blog->slug }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700/50 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-3">
                        <i class="fas fa-chart-bar text-blue-600"></i>
                        Post Stats
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-1">
                                {{ number_format($blog->views_count ?? 0) }}
                            </div>
                            <div class="text-xs font-medium text-blue-700 dark:text-blue-300 uppercase tracking-wide">Views</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-emerald-50 to-green-50 dark:from-emerald-900/20 dark:to-green-900/20 rounded-xl">
                            <div class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 mb-1">
                                {{ $blog->categories->count() }}
                            </div>
                            <div class="text-xs font-medium text-emerald-700 dark:text-emerald-300 uppercase tracking-wide">Categories</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 rounded-xl">
                            <div class="text-2xl font-bold text-purple-600 dark:text-purple-400 mb-1">
                                {{ $blog->tags->count() }}
                            </div>
                            <div class="text-xs font-medium text-purple-700 dark:text-purple-300 uppercase tracking-wide">Tags</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-xl">
                            <div class="text-2xl font-bold text-amber-600 dark:text-amber-400 mb-1">
                                {{ $blog->comments_count ?? 0 }}
                            </div>
                            <div class="text-xs font-medium text-amber-700 dark:text-amber-300 uppercase tracking-wide">Comments</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tailwind Typography for better content rendering -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                utilities: {
                    '.line-clamp-2': {
                        overflow: 'hidden',
                        display: '-webkit-box',
                        '-webkit-box-orient': 'vertical',
                        '-webkit-line-clamp': '2',
                    }
                }
            }
        }
    }
</script>

<style>
    .prose {
        @apply text-gray-700 dark:text-gray-300;
    }
    .prose h1, .prose h2, .prose h3 {
        @apply font-bold text-gray-900 dark:text-white;
    }
    .prose h1 { @apply text-3xl mt-8 mb-4; }
    .prose h2 { @apply text-2xl mt-6 mb-3; }
    .prose h3 { @apply text-xl mt-5 mb-2; }
    .prose p { @apply mb-4 leading-relaxed; }
    .prose a { 
        @apply text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200;
    }
    .prose blockquote {
        @apply border-l-4 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 pl-6 py-3 italic my-6;
    }
    .prose code {
        @apply bg-gray-100 dark:bg-gray-800 text-red-600 px-2 py-1 rounded text-sm font-mono;
    }
    .prose pre {
        @apply bg-gray-900 text-white rounded-xl p-6 my-6 overflow-x-auto;
    }
    .prose ul, .prose ol {
        @apply my-6 ml-6;
    }
    .prose li {
        @apply mb-2;
    }
    .markdown-content img {
        @apply rounded-xl shadow-lg my-6 max-w-full h-auto;
    }
    .markdown-content table {
        @apply w-full border-collapse border border-gray-300 dark:border-gray-600 my-6;
    }
    .markdown-content th, .markdown-content td {
        @apply border border-gray-300 dark:border-gray-600 px-4 py-2 text-sm;
    }
    .markdown-content th {
        @apply bg-gray-100 dark:bg-gray-700 font-semibold;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-copy slug for sharing
    const seoTitle = document.getElementById('seo-title-preview');
    const seoUrl = document.getElementById('seo-url-preview');
    
    function addCopyButtons() {
        if (seoTitle && !seoTitle.querySelector('.copy-btn')) {
            seoTitle.insertAdjacentHTML('beforeend', `
                <button class="ml-2 p-1 text-white/70 hover:text-white rounded-full hover:bg-white/20 transition-all duration-200 copy-btn" onclick="copySeoTitle()" title="Copy SEO Title">
                    <i class="fas fa-copy text-xs"></i>
                </button>
            `);
        }
        
        if (seoUrl && !seoUrl.querySelector('.copy-btn')) {
            seoUrl.insertAdjacentHTML('beforeend', `
                <button class="ml-2 p-1 text-white/70 hover:text-white rounded-full hover:bg-white/20 transition-all duration-200 copy-btn" onclick="copySeoUrl()" title="Copy Full URL">
                    <i class="fas fa-copy text-xs"></i>
                </button>
            `);
        }
    }
    
    window.copySeoTitle = function() {
        navigator.clipboard.writeText(seoTitle.textContent.trim());
        const btn = seoTitle.querySelector('.copy-btn');
        const icon = btn.querySelector('i');
        icon.className = 'fas fa-check text-xs';
        setTimeout(() => icon.className = 'fas fa-copy text-xs', 2000);
    };
    
    window.copySeoUrl = function() {
        navigator.clipboard.writeText(seoUrl.textContent.trim());
        const btn = seoUrl.querySelector('.copy-btn');
        const icon = btn.querySelector('i');
        icon.className = 'fas fa-check text-xs';
        setTimeout(() => icon.className = 'fas fa-copy text-xs', 2000);
    };
    
    addCopyButtons();
});
</script>
@endsection