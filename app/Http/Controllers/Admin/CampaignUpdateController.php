<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Mail\CampaignUpdateMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CampaignUpdateController extends Controller
{
    /**
     * Display all updates for a campaign
     */
    public function index($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $updates = $campaign->updates()->latest()->paginate(10);

        return view('admin.campaigns.updates.index', compact('campaign', 'updates'));
    }

    /**
     * Show the form for creating a new update
     */
    public function create($campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        return view('admin.campaigns.updates.create', compact('campaign'));
    }

    /**
     * Store a newly created update and email donors
     */
    public function store(Request $request, $campaignId)
    {
        $campaign = Campaign::findOrFail($campaignId);


        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
           'notify_donors' => 'required|in:0,1',  // or 'boolean'
    'publish_now' => 'required|in:0,1',
        ]);

        // Handle images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('campaign-updates', 'public');
            }
        }

        // Create the update
        $update = CampaignUpdate::create([
            'campaign_id' => $campaign->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'images' => $imagePaths,
            'published_at' => $request->has('publish_now') ? now() : null,
            'notify_donors' => $request->has('notify_donors'),
        ]);

        // Send email notifications to donors if enabled and published
        if ($request->has('notify_donors') && $request->has('publish_now')) {
            try {
                $donorCount = $this->emailDonors($campaign, $update);
                
                return redirect()
                    ->route('admin.campaigns.updates.index', $campaign->id)
                    ->with('success', "Campaign update posted successfully! Email notifications sent to {$donorCount} donors.");
                    
            } catch (\Exception $e) {
                Log::error('Failed to send donor notifications: ' . $e->getMessage());
                return redirect()
                    ->route('admin.campaigns.updates.index', $campaign->id)
                    ->with('warning', 'Campaign update posted, but some email notifications failed to send.');
            }
        }

        return redirect()
            ->route('admin.campaigns.updates.index', $campaign->id)
            ->with('success', 'Campaign update posted successfully!');
    }

    /**
     * Show the form for editing an update
     */
    public function edit($campaignId, $updateId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $update = CampaignUpdate::where('campaign_id', $campaignId)
            ->findOrFail($updateId);

        return view('admin.campaigns.updates.edit', compact('campaign', 'update'));
    }

    /**
     * Update an existing campaign update
     */
    public function update(Request $request, $campaignId, $updateId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $update = CampaignUpdate::where('campaign_id', $campaignId)
            ->findOrFail($updateId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'nullable|image|max:2048',
            'remove_images' => 'nullable|array',
            'notify_donors' => 'boolean',
        ]);

        // Handle image removal
        if ($request->filled('remove_images') && $update->images) {
            $remainingImages = array_diff($update->images, $request->remove_images);
            foreach ($request->remove_images as $imageToRemove) {
                Storage::disk('public')->delete($imageToRemove);
            }
            $update->images = array_values($remainingImages);
        }

        // Handle new images
        if ($request->hasFile('images')) {
            $newImages = [];
            foreach ($request->file('images') as $image) {
                $newImages[] = $image->store('campaign-updates', 'public');
            }
            $update->images = array_merge($update->images ?? [], $newImages);
        }

        $update->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'images' => $update->images,
            'notify_donors' => $request->has('notify_donors'),
        ]);

        return redirect()
            ->route('admin.campaigns.updates.index', $campaign->id)
            ->with('success', 'Update edited successfully!');
    }

    /**
     * Delete a campaign update
     */
    public function destroy($campaignId, $updateId)
    {
        $update = CampaignUpdate::where('campaign_id', $campaignId)
            ->findOrFail($updateId);

        // Delete images
        if ($update->images) {
            foreach ($update->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }

        $update->delete();

        return back()->with('success', 'Update deleted successfully!');
    }

    /**
     * Email all campaign donors about the update
     * 
     * @return int Number of donors emailed
     */
    protected function emailDonors(Campaign $campaign, CampaignUpdate $update): int
    {
        // Get all unique donors from successful donations
        $donors = $campaign->donations()
            ->where('status', 'successful')
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id')
            ->filter(); // Remove null users

        $successCount = 0;

        foreach ($donors as $donor) {
            if ($donor && $donor->email) {
                try {
                    Mail::to($donor->email)->send(
                        new CampaignUpdateMail($campaign, $update)
                    );
                    $successCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to send campaign update email to ' . $donor->email . ': ' . $e->getMessage());
                }
            }
        }

        Log::info('Campaign update emails sent', [
            'campaign_id' => $campaign->id,
            'update_id' => $update->id,
            'total_donors' => $donors->count(),
            'successful_emails' => $successCount
        ]);

        return $successCount;
    }

    /**
     * Resend update email to all donors
     */
    public function resendEmails($campaignId, $updateId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $update = CampaignUpdate::where('campaign_id', $campaignId)
            ->findOrFail($updateId);

        try {
            $donorCount = $this->emailDonors($campaign, $update);
            
            return back()->with('success', "Update emails sent to {$donorCount} donors!");
        } catch (\Exception $e) {
            Log::error('Failed to resend emails: ' . $e->getMessage());
            return back()->with('error', 'Failed to resend emails. Please try again.');
        }
    }

    /**
     * Publish a draft update
     */
    public function publish($campaignId, $updateId)
    {
        $campaign = Campaign::findOrFail($campaignId);
        $update = CampaignUpdate::where('campaign_id', $campaignId)
            ->findOrFail($updateId);

        if ($update->published_at) {
            return back()->with('error', 'This update is already published.');
        }

        $update->update(['published_at' => now()]);

        // Send emails if notify_donors was enabled
        if ($update->notify_donors) {
            try {
                $donorCount = $this->emailDonors($campaign, $update);
                return redirect()->route('admin.campaigns.updates.index')->with('success', "Update published and emails sent to {$donorCount} donors!");
            } catch (\Exception $e) {
                return back()->with('warning', 'Update published, but some emails failed to send.');
            }
        }

        return back()->with('success', 'Update published successfully!');
    }
}