<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Volunteer;
use App\Mail\VolunteerApproved;
use App\Mail\VolunteerRejected;
use App\Mail\VolunteerAssignedToCampaign;
use App\Mail\BulkVolunteerEmail;
use App\Mail\VolunteerAssignedToCampaignMail;
use App\Mail\VolunteerRejectedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class VolunteerController extends Controller
{
    public function index(Request $request)
    {
        $query = Volunteer::with(['user', 'approvedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $volunteers = $query->latest()->paginate(15);

        return view('admin.volunteer.index', compact('volunteers'));
    }

    public function pending()
    {
        $volunteers = Volunteer::with(['user'])
            ->pending()
            ->latest()
            ->paginate(15);

        return view('admin.volunteer.pending', compact('volunteers'));
    }

    public function show(Volunteer $volunteer)
    {
        $volunteer->load(['user', 'approvedBy', 'campaigns', 'hours.campaign']);
        
        $totalHours = $volunteer->hours()->where('status', 'approved')->sum('hours');
        $pendingHours = $volunteer->hours()->where('status', 'pending')->sum('hours');

        return view('admin.volunteer.show', compact('volunteer', 'totalHours', 'pendingHours'));
    }

   public function approve(Request $request, Volunteer $volunteer)
{
    $volunteer->update([
        'status' => 'approved',
        'approved_at' => now(),
        'approved_by' => auth()->id(),
        'notes' => $request->notes,
    ]);

    
    $volunteer->user->update(['role' => 'volunteer']);

    // Send approval email
    Mail::to($volunteer->user->email)->send(new VolunteerApproved($volunteer));

    return redirect()->back()->with('success', 'Volunteer application approved successfully.');
}

    public function reject(Request $request, Volunteer $volunteer)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $volunteer->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
        ]);

        // Send rejection email
        Mail::to($volunteer->user->email)->send(new VolunteerRejectedMail($volunteer));

        return redirect()->back()->with('success', 'Volunteer application rejected.');
    }

    public function suspend(Volunteer $volunteer)
    {
        $volunteer->update(['status' => 'suspended']);

        return redirect()->back()->with('success', 'Volunteer suspended successfully.');
    }

    public function reactivate(Volunteer $volunteer)
    {
        $volunteer->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Volunteer reactivated successfully.');
    }

 public function assignToCampaign(Request $request, Volunteer $volunteer)
{
    $request->validate([
        'campaign_id' => 'required|exists:campaigns,id',
    ]);

    if (!$volunteer->isApproved()) {
        return redirect()->back()->with('error', 'Only approved volunteers can be assigned to campaigns.');
    }

    if ($volunteer->campaigns()->where('campaign_id', $request->campaign_id)->exists()) {
        return redirect()->back()->with('error', 'Volunteer is already assigned to this campaign.');
    }

    // Assign campaign
    $volunteer->campaigns()->syncWithoutDetaching([$request->campaign_id => [
        'assigned_by' => auth()->id(),
        'assigned_at' => now(),
        'status' => 'assigned',
    ]]);

    $campaign = Campaign::find($request->campaign_id);
    
    // 🔥 DEBUG: Log EVERYTHING
    Log::info('🔥 SENDING EMAIL DEBUG', [
        'volunteer_id' => $volunteer->id,
        'volunteer_email' => $volunteer->user->email,
        'campaign_id' => $campaign->id,
        'campaign_title' => $campaign->title,
    ]);

    // 🔥 REMOVE try-catch TEMPORARILY to see REAL errors
    Mail::to($volunteer->user->email)->send(new VolunteerAssignedToCampaignMail($volunteer, $campaign));

    return redirect()->back()->with('success', 'Volunteer assigned to campaign successfully.');
}

    public function removeFromCampaign(Volunteer $volunteer, Campaign $campaign)
    {
        $volunteer->campaigns()->updateExistingPivot($campaign->id, [
            'status' => 'removed',
        ]);

        return redirect()->back()->with('success', 'Volunteer removed from campaign.');
    }

public function bulkEmail(Request $request)
{
    try {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'recipient_type' => 'required|in:all,approved,pending,campaign',
            'campaign_id' => 'required_if:recipient_type,campaign|nullable|exists:campaigns,id',
        ]);

        $query = Volunteer::with('user')->whereHas('user');

        switch ($request->recipient_type) {
            case 'approved':
                $query->approved();
                break;
            case 'pending':
                $query->pending();
                break;
            case 'campaign':
                if (!$request->filled('campaign_id')) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Campaign is required for campaign-specific emails.');
                }
                $query->whereHas('campaigns', function($q) use ($request) {
                    $q->where('campaign_id', $request->campaign_id);
                });
                break;
        }

        $volunteers = $query->get();

        if ($volunteers->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('warning', 'No volunteers found for the selected criteria.');
        }

        
        $sentCount = 0;
        $failedEmails = [];
        
        foreach ($volunteers as $volunteer) {
            if (empty($volunteer->user->email)) {
                $failedEmails[] = $volunteer->user->name . ' (no email)';
                continue;
            }

            try {
                
                Mail::to($volunteer->user->email)->send(
                    new BulkVolunteerEmail($request->subject, $request->message, $volunteer)
                );
                $sentCount++;
                
                // Small delay to prevent rate limiting
                usleep(100000); // 0.1 second
                
            } catch (\Exception $e) {
                Log::error("Failed to send email to {$volunteer->user->email}: " . $e->getMessage());
                $failedEmails[] = $volunteer->user->email;
            }
        }

        // 🔥 BETTER SUCCESS MESSAGE
        $message = "✅ Successfully sent to {$sentCount} volunteer(s)";
        
        if (!empty($failedEmails)) {
            $message .= "<br>⚠️ Failed: " . count($failedEmails) . " emails";
        }

        return redirect()->back()
            ->withInput()
            ->with('success', $message);

    } catch (ValidationException $e) {
        return redirect()->back()->withErrors($e->validator)->withInput();
    } catch (\Exception $e) {
        Log::error('Bulk email error: ' . $e->getMessage());
        return redirect()->back()
            ->withInput()
            ->with('error', ' Failed to send emails. Check logs for details.');
    }
}

    public function export(Request $request)
    {
        $query = Volunteer::with(['user', 'campaigns']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $volunteers = $query->get();

        $filename = 'volunteers_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($volunteers) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, ['Name', 'Email', 'Phone', 'Status', 'Total Hours', 'Campaigns', 'Applied Date']);

            foreach ($volunteers as $volunteer) {
                fputcsv($file, [
                    $volunteer->user->name,
                    $volunteer->user->email,
                    $volunteer->phone,
                    ucfirst($volunteer->status),
                    $volunteer->total_hours,
                    $volunteer->campaigns->count(),
                    $volunteer->created_at->format('Y-m-d'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function updateNotes(Request $request, Volunteer $volunteer)
    {
        $request->validate([
            'notes' => 'nullable|string|max:2000',
        ]);

        $volunteer->update(['notes' => $request->notes]);

        return redirect()->back()->with('success', 'Notes updated successfully.');
    }

    public function destroy(Volunteer $volunteer)
    {
        $volunteer->delete();

        return redirect()->route('admin.volunteers.index')->with('success', 'Volunteer deleted successfully.');
    }
}