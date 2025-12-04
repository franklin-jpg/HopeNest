<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['categories', 'tags'])
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->category) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $posts = $query->paginate(9);
        $categories = BlogCategory::whereHas('posts', function ($q) {
            $q->where('status', 'published');
        })->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show(BlogPost $post)
    {
        // Only show published posts
        if (!$post->isPublished()) {
            abort(404);
        }

        $post->increment('views_count');

        // FIXED: Get related posts with explicit table prefixes
        $relatedPosts = BlogPost::with(['categories'])
            ->where('status', 'published')
            ->where('blog_posts.id', '!=', $post->id) // ✅ FIXED: Explicit table name
            ->where(function ($query) use ($post) {
                $query->whereHas('categories', function ($q) use ($post) {
                    $q->whereIn('blog_categories.id', $post->categories->pluck('id')); // ✅ FIXED: Explicit table name
                });
            })
            ->inRandomOrder() // Add variety to related posts
            ->limit(6)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts')); 
    }
}