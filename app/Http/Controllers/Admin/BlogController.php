<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Stmt\TryCatch;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::with(['categories', 'tags'])
            ->orderBy('published_at', 'desc')
            ->orderBy('created_at', 'desc');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        return view('admin.blog.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
    
    

             $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|max:2048',
            'categories' => 'array',
            'tags' => 'array',
            'seo_title' => 'nullable|max:60',
            'seo_description' => 'nullable|max:160',
            'seo_keywords' => 'nullable'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['excerpt'] = Str::limit(strip_tags($request->content), 150);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        if ($request->status === 'scheduled' && $request->published_at) {
            $data['published_at'] = $request->published_at;
        }

        $data['seo_meta'] = json_encode([
            'title' => $request->seo_title ?: $request->title,
            'description' => $request->seo_description ?: $data['excerpt'],
            'keywords' => $request->seo_keywords
        ]);

        $post = BlogPost::create($data);

        if ($request->categories) {
            $post->categories()->sync($request->categories);
        }

        if ($request->tags) {
            $post->tags()->sync($request->tags);
        }

        return redirect()->route('admin.blogs.blog.index')
            ->with('success', 'Blog post created successfully!');
    
        
    }

    public function edit(BlogPost $blog)
    {
        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        $seo = $blog->seo_meta;
        return view('admin.blog.edit', compact('blog', 'categories', 'tags', 'seo'));
    }

    public function update(Request $request, BlogPost $blog)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'status' => 'required|in:draft,published,scheduled',
            'published_at' => 'nullable|date',
            'featured_image' => 'nullable|image|max:2048',
            'categories' => 'array',
            'tags' => 'array',
            'seo_title' => 'nullable|max:60',
            'seo_description' => 'nullable|max:160',
            'seo_keywords' => 'nullable'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['excerpt'] = Str::limit(strip_tags($request->content), 150);

        if ($request->hasFile('featured_image')) {
            if ($blog->featured_image) {
                Storage::disk('public')->delete($blog->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('blog-images', 'public');
        }

        if ($request->status === 'scheduled' && $request->published_at) {
            $data['published_at'] = $request->published_at;
        } elseif ($request->status === 'published') {
            $data['published_at'] = now();
        }

        $data['seo_meta'] = json_encode([
            'title' => $request->seo_title ?: $request->title,
            'description' => $request->seo_description ?: $data['excerpt'],
            'keywords' => $request->seo_keywords
        ]);

        $blog->update($data);

        if ($request->categories) {
            $blog->categories()->sync($request->categories);
        }

        if ($request->tags) {
            $blog->tags()->sync($request->tags);
        }

        return redirect()->route('admin.blogs.blog.index')
            ->with('success', 'Blog post updated successfully!');
    }

    public function destroy(BlogPost $blog)
    {
        if ($blog->featured_image) {
            Storage::disk('public')->delete($blog->featured_image);
        }
        $blog->delete();
        return redirect()->route('admin.blogs.blog.index')
            ->with('success', 'Blog post deleted successfully!');
    }

    public function toggleStatus(BlogPost $blog)
    {
        if ($blog->status === 'published') {
            $blog->update(['status' => 'draft']);
        } else {
            $blog->update(['status' => 'published', 'published_at' => now()]);
        }
        return back()->with('success', 'Status updated successfully!');
    }

    public function show(BlogPost $blog)
    {
        // Only show published posts or allow admins to see drafts
        if (!$blog->isPublished() && !auth()->user()->can('access-admin-dashboard')) {
            abort(404);
        }

        $blog->increment('views_count'); // Track admin views too
        
        $relatedPosts = BlogPost::with(['categories'])
            ->where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->limit(5)
            ->get();

        return view('admin.blog.show', compact('blog', 'relatedPosts'));
    }

    /**
     *  Bulk actions (delete, publish, etc.)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,publish,draft',
            'selected' => 'required|array',
            'selected.*' => 'exists:blog_posts,id'
        ]);

        $posts = BlogPost::whereIn('id', $request->selected);

        switch ($request->action) {
            case 'delete':
                $posts->each(function ($post) {
                    if ($post->featured_image) {
                        Storage::disk('public')->delete($post->featured_image);
                    }
                    $post->delete();
                });
                $message = 'Selected posts deleted successfully!';
                break;
                
            case 'publish':
                $posts->update([
                    'status' => 'published',
                    'published_at' => now()
                ]);
                $message = 'Selected posts published successfully!';
                break;
                
            case 'draft':
                $posts->update(['status' => 'draft']);
                $message = 'Selected posts moved to draft successfully!';
                break;
                
            default:
                return back()->with('error', 'Invalid action selected!');
        }

        return back()->with('success', $message);
    }
}