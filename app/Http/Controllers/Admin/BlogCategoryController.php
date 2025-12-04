<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::withCount('posts')->paginate(10);
        return view('admin.blog.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.blog.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:100|unique:blog_categories',
            'color' => 'required'
        ]);

        BlogCategory::create($request->all());
        return redirect()->route('admin.blogs.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(BlogCategory $category)
    {
        return view('admin.blog.categories.edit', compact('category'));
    }

    public function update(Request $request, BlogCategory $category)
    {
        $request->validate([
            'name' => 'required|max:100|unique:blog_categories,name,' . $category->id,
            'color' => 'required'
        ]);

        $category->update($request->all());
        return redirect()->route('admin.blogs.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(BlogCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.blogs.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}