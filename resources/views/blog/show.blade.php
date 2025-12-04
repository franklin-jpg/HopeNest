@extends('layouts.app')

@section('title', $post->seo_meta['title'] ?? $post->title . ' - Hopenest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50">
    <!-- Breadcrumb -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center py-4">
                <a href="{{ route('blog.index') }}" class="flex items-center gap-2 text-sm text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back to Blog</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Featured Image -->
        @if($post->featured_image)
        <div class="relative rounded-3xl overflow-hidden shadow-2xl mb-8">
            <img src="{{ Storage::url($post->featured_image) }}" 
                 alt="{{ $post->title }}" 
                 class="w-full h-96 object-cover">
            @if($post->is_featured)
                <div class="absolute top-6 left-6">
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-500 to-yellow-600 text-white font-bold rounded-full shadow-lg">
                        <i class="fas fa-star"></i>
                        Featured Post
                    </span>
                </div>
            @endif
        </div>
        @endif

        <!-- Post Header -->
        <article class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="p-10">
                <!-- Categories -->
                <div class="flex flex-wrap gap-3 mb-6">
                    @forelse($post->categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                           class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold text-white shadow-sm"
                           style="background-color: {{ $category->color }}; color: {{ $category->color }};">
                            {{ $category->name }}
                        </a>
                    @empty
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gray-100 text-gray-600">
                            <i class="fas fa-folder-open mr-2"></i>
                            Uncategorized
                        </span>
                    @endforelse
                </div>

                <!-- Title -->
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
                    {{ $post->title }}
                </h1>

                <!-- Meta -->
                <div class="flex flex-wrap items-center gap-6 text-sm text-gray-500 mb-10 pb-8 border-b border-gray-200">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-user text-blue-600"></i>
                        <span>Hopenest Team</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-alt"></i>
                        <time datetime="{{ $post->published_at }}">{{ $post->published_at->format('F d, Y') }}</time>
                    </div>
                    <div class="flex items-center gap-2">
                        <i class="fas fa-eye"></i>
                        <span>{{ number_format($post->views_count) }} views</span>
                    </div>
                    @if($post->tags->count())
                        <div class="flex items-center gap-2">
                            <i class="fas fa-tags"></i>
                            @foreach($post->tags->take(3) as $tag)
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">#{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Content -->
                <div class="prose prose-lg max-w-none">
                    {!! $post->content !!}
                </div>
            </div>
        </article>

        <!-- Related Posts -->
        @if($relatedPosts->count())
        <section class="mt-20">
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="p-10 border-b border-gray-200">
                    <h2 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                        <i class="fas fa-thumbs-up text-blue-600"></i>
                        You might also like
                    </h2>
                    <p class="text-gray-600 mt-2">More articles from similar topics</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 p-10">
                    @foreach($relatedPosts as $related)
                        <article class="group relative overflow-hidden rounded-2xl bg-gradient-to-br from-gray-50 to-blue-50 p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-2">
                            @if($related->featured_image)
                                <div class="relative h-48 rounded-xl overflow-hidden mb-4">
                                    <img src="{{ Storage::url($related->featured_image) }}" 
                                         alt="{{ $related->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                </div>
                            @endif
                            
                            <div class="flex flex-wrap gap-2 mb-4">
                                @forelse($related->categories->take(2) as $category)
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold text-white shadow-sm"
                                          style="background-color: {{ $category->color }};">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>

                            <h3 class="font-semibold text-gray-900 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                {{ $related->title }}
                            </h3>

                            <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                                {!! Str::limit(strip_tags($related->content), 80) !!}
                            </p>

                            <a href="{{ route('blog.show', $related) }}" 
                               class="inline-flex items-center gap-2 text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                                <span>Read more</span>
                                <i class="fas fa-arrow-right text-sm"></i>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    </div>
</div>
@endsection