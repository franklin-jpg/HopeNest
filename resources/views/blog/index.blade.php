@extends('layouts.app')

@section('title', 'Blog - Hopenest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-orange-400 text-white">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold tracking-tight mb-6 text-gray-300">
                    Hopenest Blog
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 max-w-3xl mx-auto mb-8">
                    Discover inspiring stories, helpful tips, and the latest news from our community
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center max-w-md mx-auto">
                    <a href="#posts" class="group flex items-center justify-center gap-3 px-8 py-4 bg-white/20 backdrop-blur-sm text-white font-semibold rounded-2xl border-2 border-white/30 hover:border-white/50 hover:bg-white/25 transition-all duration-300 shadow-2xl hover:shadow-3xl transform hover:-translate-y-2">
                        <i class="fas fa-newspaper text-lg"></i>
                        <span>Explore Posts</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Filter -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                    Browse by Category
                </h2>
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    Find articles that interest you
                </p>
            </div>
            
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <a href="{{ route('blog.index') }}" 
                   class="group flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transform hover:-translate-y-1 transition-all duration-300 {{ !request('category') ? 'ring-2 ring-blue-500/30' : '' }}">
                    <i class="fas fa-th-large"></i>
                    <span>All Categories</span>
                </a>
                @foreach($categories->take(8) as $category)
                <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                   class="group flex items-center gap-2 px-6 py-3 font-semibold rounded-2xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 border-2 {{ request('category') == $category->slug ? 'border-blue-500 bg-gradient-to-r from-blue-500/10 text-blue-700' : 'border-transparent bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 hover:from-blue-500/10 hover:to-indigo-500/10 hover:text-blue-700' }}"
                   style="background-color: {{ $category->color }}20 !important; border-color: {{ $category->color }}40 !important;">
                    <i class="fas fa-folder text-sm"></i>
                    <span>{{ $category->name }}</span>
                    <span class="ml-2 px-2 py-1 bg-white/80 rounded-full text-xs font-bold text-gray-700">
                        {{ $category->posts()->where('status', 'published')->count() }}
                    </span>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Search -->
    <section class="py-8 bg-gradient-to-r from-slate-50 to-blue-50">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="GET" action="{{ route('blog.index') }}" class="relative">
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           class="w-full pl-16 pr-12 py-4 bg-white/80 backdrop-blur-lg border-2 border-gray-200 rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-orange-500 transition-all duration-300 text-lg shadow-xl" 
                           placeholder="Search for articles, topics, or keywords..."
                           value="{{ request('search') }}">
                    <i class="fas fa-search absolute left-6 top-1/2 -translate-y-1/2 text-gray-400 text-xl"></i>
                    <button type="submit" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 group flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:from-orange-600 hover:to-orange-700 transform hover:-translate-y-1 transition-all duration-300">
                        <i class="fas fa-search"></i>
                        <span class="hidden sm:inline">Search</span>
                    </button>
                </div>
            </form>
        </div>
    </section>

    <!-- Posts Grid -->
    <section id="posts" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6 mb-12">
                <div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900">
                        Latest Articles
                    </h2>
                    <p class="text-gray-600 mt-2">
                        @if(request('category'))
                            Articles in <span class="font-semibold text-orange-600">{{ $categories->where('slug', request('category'))->first()->name ?? 'Category' }}</span>
                        @elseif(request('search'))
                            Search results for "<span class="font-semibold text-orange-600">{{ request('search') }}</span>"
                        @else
                            Fresh content from our blog
                        @endif
                    </p>
                </div>
                <div class="text-sm text-gray-500">
                    Showing {{ ($posts->currentPage() - 1) * $posts->perPage() + 1 }} - 
                    {{ min($posts->total(), $posts->currentPage() * $posts->perPage()) }} 
                    of {{ $posts->total() }} articles
                </div>
            </div>

            @if($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($posts as $post)
                        <article class="group relative bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-3 hover:rotate-1">
                            <!-- Featured Image -->
                            <div class="relative h-64 overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                @if($post->featured_image)
                                    <img src="{{ Storage::url($post->featured_image) }}" 
                                         alt="{{ $post->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                    @if($post->is_featured)
                                        <div class="absolute top-4 left-4 z-10">
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-gradient-to-r from-amber-400 to-yellow-500 text-white text-sm font-bold rounded-full shadow-lg">
                                                <i class="fas fa-star"></i>
                                                Featured
                                            </span>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-orange-500 via-orange-600 to-purple-700 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-6xl text-white/80"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="p-8">
                                <!-- Categories -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    @forelse($post->categories->take(3) as $category)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold text-white shadow-sm"
                                              style="background-color: {{ $category->color }}; color: {{ $category->color }};">
                                            {{ $category->name }}
                                        </span>
                                    @empty
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            <i class="fas fa-folder-open mr-1"></i>
                                            Uncategorized
                                        </span>
                                    @endforelse
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-gray-900 mb-3 group-hover:text-orange-600 transition-colors duration-300 line-clamp-2">
                                    {{ $post->title }}
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                                    {!! Str::limit(strip_tags($post->content), 120) !!}
                                </p>

                                <!-- Meta -->
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-calendar"></i>
                                        {{ $post->published_at->format('M d, Y') }}
                                    </span>
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-eye"></i>
                                        {{ number_format($post->views_count) }} views
                                    </span>
                                </div>

                                <!-- Read More Button -->
                                <a href="{{ route('blog.show', $post) }}" 
                                   class="mt-6 inline-flex items-center gap-3 text-orange-600 font-semibold hover:text-orange-700 transition-colors duration-300 group-hover:translate-x-2 transform">
                                    <span>Read More</span>
                                    <i class="fas fa-arrow-right text-sm group-hover:translate-x-1 transition-transform duration-300"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($posts->hasPages())
                <div class="mt-16 flex justify-center">
                    {{ $posts->appends(request()->query())->links('vendor.pagination.tailwind') }}
                </div>
                @endif
            @else
                <div class="text-center py-24">
                    <div class="mx-auto w-32 h-32 bg-gradient-to-br from-orange-500 to-orange-600 rounded-full flex items-center justify-center mb-8">
                        <i class="fas fa-search text-4xl text-white"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-900 mb-4">
                        No posts found
                    </h3>
                    <p class="text-gray-600 mb-8 max-w-2xl mx-auto">
                        @if(request('search'))
                            No posts match your search for "<strong>{{ request('search') }}</strong>"
                        @elseif(request('category'))
                            No posts found in this category
                        @else
                            No blog posts available yet. Check back soon!
                        @endif
                    </p>
                    <a href="{{ route('blog.index') }}" 
                       class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-semibold rounded-2xl shadow-lg hover:shadow-xl hover:from-blue-600 hover:to-indigo-700 transform hover:-translate-y-2 transition-all duration-300">
                        <i class="fas fa-arrow-left"></i>
                        <span>Browse All Posts</span>
                    </a>
                </div>
            @endif
        </div>
    </section>
</div>
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush