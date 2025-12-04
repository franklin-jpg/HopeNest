<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CampaignController extends Controller
{
    /**
     * Display a listing of campaigns with filters
     */
    public function index(Request $request)
    {
        $query = Campaign::with(['campaignCategory', 'donations']);

        // Search Filter
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Category Filter
        if ($request->filled('category')) {
            $query->where('campaign_category_id', $request->category);
        }

        // Date Range Filter
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Featured Filter
        if ($request->filled('is_featured')) {
            $query->where('is_featured', $request->is_featured);
        }

        // Urgent Filter
        if ($request->filled('is_urgent')) {
            $query->where('is_urgent', $request->is_urgent);
        }

        $campaigns = $query->latest()->paginate(15);
        $categories = CampaignCategory::where('status', 'active')->get();
        $archivedCount = Campaign::onlyTrashed()->count();




        // Statistics
        $stats = [
            'total' => Campaign::count(),
            'active' => Campaign::where('status', 'active')->count(),
            'draft' => Campaign::where('status', 'draft')->count(),
            'completed' => Campaign::where('status', 'completed')->count(),
        ];

        return view('admin.campaigns.index', compact('campaigns', 'categories', 'archivedCount', 'stats'));
    }

    /**
     * Show archived campaigns
     */
    public function archived(Request $request)
    {
        $query = Campaign::onlyTrashed()->with(['campaignCategory', 'donations']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $campaigns = $query->latest()->paginate(15);
        $activeCount = Campaign::count();

        return view('admin.campaigns.archived', compact('campaigns', 'activeCount'));
    }

    /**
     * Show the form for creating a new campaign
     */
    public function create()
    {
        $categories = CampaignCategory::where('status', 'active')->get();
        return view('admin.campaigns.create', compact('categories'));
    }

    /**
     * Store a newly created campaign
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:campaigns,title',
            'campaign_category_id' => 'required|exists:campaign_categories,id',
            'short_description' => 'required|string|max:150',
            'full_description' => 'required|string',
            'location' => 'required|string|max:255',
            'goal_amount' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'minimum_donation' => 'required|numeric|min:1',
            'featured_image' => 'required|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'is_featured' => 'nullable|boolean',
            'is_urgent' => 'nullable|boolean',
            'recurring_donations' => 'nullable|boolean',
            'custom_thank_you' => 'nullable|string',
            'status' => 'required|in:draft,active,completed',
        ]);

          // Manually handle boolean checkboxes AFTER validation
    $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
    $validated['is_urgent'] = $request->has('is_urgent') ? 1 : 0;
    $validated['recurring_donations'] = $request->has('recurring_donations') ? 1 : 0;

        $validated['slug'] = Str::slug($request->title);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')
                ->store('campaigns/featured', 'public');
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('campaigns/gallery', 'public');
            }
            $validated['gallery_images'] = $galleryPaths;
        }

    
        Campaign::create($validated);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign created successfully!');
    }

    /**
     * Display campaign details with analytics
     */
    public function show($id)
    {
        $campaign = Campaign::with(['campaignCategory', 'donations.user'])
            ->withCount(['donations', 'successfulDonations'])
            ->findOrFail($id);

        // Analytics Data
        $analytics = [
            'total_raised' => $campaign->raised_amount,
            'goal_amount' => $campaign->goal_amount,
            'progress_percentage' => $campaign->progress_percentage,
            'total_donors' => $campaign->donorsCount(),
            'average_donation' => $campaign->successfulDonations()->avg('amount') ?? 0,
            'largest_donation' => $campaign->successfulDonations()->max('amount') ?? 0,
            'recent_donations' => $campaign->successfulDonations()
                ->latest()
                ->take(10)
                ->get(),
            'donations_by_day' => $campaign->successfulDonations()
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(amount) as total')
                ->groupBy('date')
                ->orderBy('date', 'desc')
                ->take(30)
                ->get(),
            
        ];

        return view('admin.campaigns.show', compact('campaign', 'analytics'));
    }

    /**
     * Show the form for editing campaign
     */
    public function edit($id)
    {
        $campaign = Campaign::findOrFail($id);
        $categories = CampaignCategory::where('status', 'active')->get();
        
        return view('admin.campaigns.edit', compact('campaign', 'categories'));
    }

    /**
     * Update campaign
     */
  public function update(Request $request, $id)
{
    try {
        $campaign = Campaign::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255|unique:campaigns,title,' . $campaign->id,
            'campaign_category_id' => 'required|exists:campaign_categories,id',
            'short_description' => 'required|string|max:150',
            'full_description' => 'required|string',
            'location' => 'required|string|max:255',
            'goal_amount' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'minimum_donation' => 'required|numeric|min:1',
            'featured_image' => 'nullable|image|max:2048',
            'gallery_images.*' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
          
            'custom_thank_you' => 'nullable|string',
            'status' => 'required|in:draft,active,completed',
        ]);

         // Manually handle boolean checkboxes AFTER validation
        $validated['is_featured'] = $request->has('is_featured') ? 1 : 0;
        $validated['is_urgent'] = $request->has('is_urgent') ? 1 : 0;
        $validated['recurring_donations'] = $request->has('recurring_donations') ? 1 : 0;

        $validated['slug'] = Str::slug($request->title);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            if ($campaign->featured_image) {
                Storage::disk('public')->delete($campaign->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')
                ->store('campaigns/featured', 'public');
        }

        // Handle gallery images
        $galleryImages = $campaign->gallery_images ?? [];

        // Remove deleted images
        if ($request->has('delete_gallery') && is_array($request->delete_gallery)) {
            foreach ($request->delete_gallery as $deletedPath) {
                if (($key = array_search($deletedPath, $galleryImages)) !== false) {
                    Storage::disk('public')->delete($deletedPath);
                    unset($galleryImages[$key]);
                }
            }
            $galleryImages = array_values($galleryImages); // reindex
        }

        // Add new images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('campaigns/gallery', 'public');
            }
        }

        // Save final gallery
        $validated['gallery_images'] = $galleryImages;
        $validated['is_featured'] = $request->has('is_featured') ? true : false;
   $validated['is_urgent'] = $request->has('is_urgent') ? true : false;
   $validated['recurring_donations'] = $request->has('recurring_donations') ? true : false;

        $campaign->update($validated);

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign updated successfully!');
            
    } catch (\Exception $e) {
        Log::error('Campaign Update Error: ' . $e->getMessage());
        return back()
            ->withInput()
            ->with('error', 'Error updating campaign: ' . $e->getMessage());
    }
}

    /**
     * Pause/Resume campaign
     */
    public function toggleStatus($id)
    {
        $campaign = Campaign::findOrFail($id);
        
        if ($campaign->status === 'active') {
            $campaign->update(['status' => 'draft']);
            $message = 'Campaign paused successfully!';
        } else if ($campaign->status === 'draft') {
            $campaign->update(['status' => 'active']);
            $message = 'Campaign activated successfully!';
        }

        return back()->with('success', $message ?? 'Status updated');
    }

    /**
     * Mark campaign as completed
     */
    public function markCompleted($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->update([
            'status' => 'completed',
            'end_date' => now()
        ]);

        return back()->with('success', 'Campaign marked as completed!');
    }

    /**
     * Duplicate campaign
     */
    public function duplicate($id)
    {
        $campaign = Campaign::findOrFail($id);
        
        $newCampaign = $campaign->replicate();
        $newCampaign->title = $campaign->title . ' (Copy)';
        $newCampaign->slug = Str::slug($newCampaign->title) . '-' . time();
        $newCampaign->status = 'draft';
        $newCampaign->raised_amount = 0;
        $newCampaign->start_date = now();
        $newCampaign->end_date = null;
        $newCampaign->save();

        return redirect()
            ->route('admin.campaigns.edit', $newCampaign->id)
            ->with('success', 'Campaign duplicated successfully! Please update the details.');
    }

    /**
     * Soft delete (archive) campaign
     */
   /**
 * Archive (soft delete) a campaign
 */
public function destroy($id)
{
    $campaign = Campaign::findOrFail($id);

    // Block only active campaigns that already have donations
    if ($campaign->status === 'active' && $campaign->donations()->count() > 0) {
        return back()->with('error', 'Cannot archive an active campaign that has received donations. Please complete the campaign first.');
    }

   
    $campaign->delete();

    return back()->with('success', 'Campaign archived successfully!');
}

    /**
     * Restore archived campaign
     */
    public function restore($id)
    {
        $campaign = Campaign::onlyTrashed()->findOrFail($id);
        $campaign->restore();

        return redirect()
            ->route('admin.campaigns.index')
            ->with('success', 'Campaign restored successfully!');
    }

    /**
     * Permanently delete campaign
     */
    public function forceDelete($id)
    {
        $campaign = Campaign::onlyTrashed()->findOrFail($id);

        // Delete images
        if ($campaign->featured_image) {
            Storage::disk('public')->delete($campaign->featured_image);
        }
        
        if ($campaign->gallery_images) {
            foreach ($campaign->gallery_images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $campaign->forceDelete();

        return redirect()
            ->route('admin.campaigns.archived')
            ->with('success', 'Campaign permanently deleted!');
    }
}