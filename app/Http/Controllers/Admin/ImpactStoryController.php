<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImpactStory;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImpactStoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ImpactStory::with('campaign')
            ->withTrashed();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('campaign')) {
            $query->where('campaign_id', $request->campaign);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('beneficiary_name', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $stories = $query->paginate(15)->withQueryString();
        $campaigns = Campaign::select('id', 'title')->get();

        return view('admin.impact-stories.index', compact('stories', 'campaigns'));
    }

    public function create()
    {
        $campaigns = Campaign::where('status', 'active')
            ->orWhere('status', 'completed')
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        return view('admin.impact-stories.create', compact('campaigns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'campaign_id' => 'nullable|exists:campaigns,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:200',
            'content' => 'required|string',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'beneficiary_name' => 'nullable|string|max:255',
            'beneficiary_location' => 'nullable|string|max:255',
            'impact_date' => 'nullable|date',
            'metrics' => 'nullable|array',
            'metrics.*.label' => 'required_with:metrics|string',
            'metrics.*.value' => 'required_with:metrics|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('impact-stories', 'public');
        }

        // Set published_at if status is published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $story = ImpactStory::create($validated);

        return redirect()
            ->route('admin.impact-stories.index')
            ->with('success', 'Impact story created successfully!');
    }

    public function show(ImpactStory $impactStory)
    {
        $impactStory->load('campaign');
        
        // Get analytics data
        $analytics = [
            'total_views' => $impactStory->views_count,
            'reading_time' => $impactStory->reading_time,
            'published_days' => $impactStory->published_at ? 
                $impactStory->published_at->diffInDays(now()) : 0,
        ];

        return view('admin.impact-stories.show', compact('impactStory', 'analytics'));
    }

    public function edit(ImpactStory $impactStory)
    {
        $campaigns = Campaign::where('status', 'active')
            ->orWhere('status', 'completed')
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        return view('admin.impact-stories.edit', compact('impactStory', 'campaigns'));
    }

    public function update(Request $request, ImpactStory $impactStory)
    {
        $validated = $request->validate([
            'campaign_id' => 'nullable|exists:campaigns,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:200',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'beneficiary_name' => 'nullable|string|max:255',
            'beneficiary_location' => 'nullable|string|max:255',
            'impact_date' => 'nullable|date',
            'metrics' => 'nullable|array',
            'metrics.*.label' => 'required_with:metrics|string',
            'metrics.*.value' => 'required_with:metrics|string',
            'status' => 'required|in:draft,published,archived',
            'is_featured' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($impactStory->featured_image) {
                Storage::disk('public')->delete($impactStory->featured_image);
            }
            
            $validated['featured_image'] = $request->file('featured_image')
                ->store('impact-stories', 'public');
        }

        // Set published_at if status changed to published
        if ($validated['status'] === 'published' && $impactStory->status !== 'published') {
            $validated['published_at'] = now();
        }

        // Remove published_at if status changed from published
        if ($validated['status'] !== 'published' && $impactStory->status === 'published') {
            $validated['published_at'] = null;
        }

        $impactStory->update($validated);

        return redirect()
            ->route('admin.impact-stories.index')
            ->with('success', 'Impact story updated successfully!');
    }

    public function destroy(ImpactStory $impactStory)
    {
        $impactStory->delete();

        return redirect()
            ->route('admin.impact-stories.index')
            ->with('success', 'Impact story moved to trash successfully!');
    }

    public function toggleStatus(ImpactStory $impactStory)
    {
        if ($impactStory->status === 'published') {
            $impactStory->unpublish();
            $message = 'Story unpublished successfully!';
        } else {
            $impactStory->publish();
            $message = 'Story published successfully!';
        }

        return back()->with('success', $message);
    }

    public function toggleFeatured(ImpactStory $impactStory)
    {
        $impactStory->update([
            'is_featured' => !$impactStory->is_featured
        ]);

        $status = $impactStory->is_featured ? 'featured' : 'unfeatured';
        return back()->with('success', "Story {$status} successfully!");
    }

    public function analytics(ImpactStory $impactStory)
    {
        $analytics = [
            'views' => [
                'total' => $impactStory->views_count,
                'avg_per_day' => $impactStory->published_at ? 
                    round($impactStory->views_count / max(1, $impactStory->published_at->diffInDays(now())), 2) : 0,
            ],
            'engagement' => [
                'reading_time' => $impactStory->reading_time,
                'word_count' => str_word_count(strip_tags($impactStory->content)),
            ],
            'status' => [
                'is_published' => $impactStory->is_published,
                'is_featured' => $impactStory->is_featured,
                'published_at' => $impactStory->published_at,
                'days_published' => $impactStory->published_at ? 
                    $impactStory->published_at->diffInDays(now()) : 0,
            ],
        ];

        return view('admin.impact-stories.analytics', compact('impactStory', 'analytics'));
    }

    public function restore($id)
    {
        $story = ImpactStory::withTrashed()->findOrFail($id);
        $story->restore();

        return back()->with('success', 'Impact story restored successfully!');
    }

    public function forceDelete($id)
    {
        $story = ImpactStory::withTrashed()->findOrFail($id);
        
        // Delete image
        if ($story->featured_image) {
            Storage::disk('public')->delete($story->featured_image);
        }
        
        $story->forceDelete();

        return back()->with('success', 'Impact story permanently deleted!');
    }
}