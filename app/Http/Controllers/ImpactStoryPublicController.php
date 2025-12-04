<?php

namespace App\Http\Controllers;

use App\Models\ImpactStory;
use App\Models\Campaign;
use Illuminate\Http\Request;

class ImpactStoryPublicController extends Controller
{
    public function index(Request $request)
    {
        $query = ImpactStory::published()
            ->with('campaign')
            ->latest('published_at');

        // Filter by campaign
        if ($request->filled('campaign')) {
            $query->where('campaign_id', $request->campaign);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $request->search . '%')
                  ->orWhere('beneficiary_name', 'like', '%' . $request->search . '%');
            });
        }

        $stories = $query->paginate(12)->withQueryString();
        
        // Get featured stories
        $featuredStories = ImpactStory::published()
            ->featured()
            ->latest('published_at')
            ->take(3)
            ->get();

        // Get campaigns for filter
        $campaigns = Campaign::whereHas('impactStories', function ($q) {
                $q->published();
            })
            ->select('id', 'title')
            ->orderBy('title')
            ->get();

        return view('impact-stories.index', compact('stories', 'featuredStories', 'campaigns'));
    }

    public function show($slug)
    {
        $story = ImpactStory::where('slug', $slug)
            ->published()
            ->with('campaign')
            ->firstOrFail();

        // Increment view count
        $story->incrementViews();

        // Get related stories (same campaign or random)
        $relatedStories = ImpactStory::published()
            ->where('id', '!=', $story->id)
            ->when($story->campaign_id, function ($query) use ($story) {
                $query->where('campaign_id', $story->campaign_id);
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        // If no related stories from same campaign, get random ones
        if ($relatedStories->count() < 3) {
            $additionalStories = ImpactStory::published()
                ->where('id', '!=', $story->id)
                ->whereNotIn('id', $relatedStories->pluck('id'))
                ->inRandomOrder()
                ->take(3 - $relatedStories->count())
                ->get();
            
            $relatedStories = $relatedStories->merge($additionalStories);
        }

        return view('impact-stories.show', compact('story', 'relatedStories'));
    }
}