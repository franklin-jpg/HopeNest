<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampaignCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignCategoryController extends Controller
{
    /** Active categories list */
   public function index(Request $request)
{
    $query = CampaignCategory::query();

    // SEARCH
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // STATUS FILTER
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $categories = $query->latest()->paginate(10);
    $archivedCount = CampaignCategory::onlyTrashed()->count();


    return view('admin.category.index', compact('categories', 'archivedCount'));
}


    /** Show only soft-deleted (archived) categories */
    public function archived(Request $request)
    {
        $query = CampaignCategory::onlyTrashed();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $categories = $query->latest()->paginate(10);
        $activeCount = CampaignCategory::count();

        return view('admin.category.archive', compact('categories', 'activeCount'));
    }

    public function create()
    {
        return view('admin.category.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|unique:campaign_categories,name',
            'status' => 'required|in:active,inactive',
            'image'  => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')
                ->store('category_images', 'public');
        }

        CampaignCategory::create($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(CampaignCategory $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, CampaignCategory $category)
    {
        $validated = $request->validate([
            'name'   => 'required|unique:campaign_categories,name,' . $category->id,
            'status' => 'required|in:active,inactive',
            'image'  => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($request->name);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $validated['image'] = $request->file('image')
                ->store('category_images', 'public');
        }

        $category->update($validated);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    /** Soft-delete (archive) */
    public function archive(CampaignCategory $category)
    {
        $category->delete();

        return back()->with('success', 'Category archived successfully!');
    }

    /** Restore soft-deleted category */
    public function restore($id)
    {
        $category = CampaignCategory::onlyTrashed()->findOrFail($id);
        $category->restore();

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category restored successfully!');
    }

    /** Permanently delete + remove image */
    public function forceDelete($id)
    {
        $category = CampaignCategory::onlyTrashed()->findOrFail($id);

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->forceDelete();

        return redirect()
            ->route('admin.categories.archived')
            ->with('success', 'Category permanently deleted');
    }

    /** Regular delete (hard delete from active list) */
    public function destroy(CampaignCategory $category)
    {
        // Optionally delete image here as well
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->forceDelete();

        return back()->with('success', 'Category deleted permanently!');
    }
}