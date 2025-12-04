<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogTag;
use Illuminate\Http\Request;

class BlogTagController extends Controller
{
    public function index()
    {
        $tags = BlogTag::withCount('posts')->paginate(10);
        return view('admin.blog.tags.index', compact('tags'));
    }

    public function create()
    {
        return view('admin.blog.tags.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50|unique:blog_tags'
        ]);

        BlogTag::create($request->all());
        return  redirect()->route('admin.blogs.tags.index')
            ->with('success', 'Tag created successfully!');
    }

    public function edit(BlogTag $tag)
{
    return view('admin.blog.tags.edit', compact('tag'));
}

public function update(Request $request, BlogTag $tag)
{
    $request->validate([
        'name' => 'required|max:50|unique:blog_tags,name,' . $tag->id
    ]);

    $tag->update($request->all());
    return redirect()->route('admin.blogs.tags.index')
        ->with('success', 'Tag updated successfully!');
}

    public function destroy(BlogTag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.blogs.tags.index')
            ->with('success', 'Tag deleted successfully!');
    }
}