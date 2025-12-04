<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\VolunteerHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VolunteerHourController extends Controller
{
    /**
     * Display volunteer hours dashboard
     */
    public function index(Request $request)
    {
        $volunteer = Auth::user()->volunteer;
        
        if (!$volunteer) {
            return redirect()->route('volunteer.dashboard')
                ->with('error', 'Volunteer profile not found.');
        }

        // Get filter parameters
        $status = $request->get('status');
        $campaignId = $request->get('campaign_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Build query
        $query = VolunteerHour::where('volunteer_id', $volunteer->id)
            ->with(['campaign', 'approvedBy'])
            ->orderBy('date', 'desc');

        // Apply filters
        if ($status) {
            $query->where('status', $status);
        }
        if ($campaignId) {
            $query->where('campaign_id', $campaignId);
        }
        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }

        $hours = $query->paginate(15);

        // Get campaigns for filter dropdown
        $campaigns = $volunteer->campaigns()
            ->orderBy('title')
            ->get(['id', 'title']);

        // Calculate statistics
        $stats = [
            'total_hours' => VolunteerHour::where('volunteer_id', $volunteer->id)
                ->approved()
                ->sum('hours'),
            'pending_hours' => VolunteerHour::where('volunteer_id', $volunteer->id)
                ->pending()
                ->sum('hours'),
            'this_month_hours' => VolunteerHour::where('volunteer_id', $volunteer->id)
                ->approved()
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('hours'),
            'pending_count' => VolunteerHour::where('volunteer_id', $volunteer->id)
                ->pending()
                ->count(),
        ];

        return view('volunteer.hours.index', compact('hours', 'campaigns', 'stats'));
    }

    /**
     * Show form to log new hours
     */
    public function create()
    {
        $volunteer = Auth::user()->volunteer;
        
        if (!$volunteer) {
            return redirect()->route('volunteer.dashboard')
                ->with('error', 'Volunteer profile not found.');
        }

        // Get assigned campaigns
        $campaigns = $volunteer->campaigns()
            ->wherePivot('status', 'active')
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('volunteer.hours.create', compact('campaigns'));
    }

    /**
     * Store newly logged hours
     */
    public function store(Request $request)
    {
        $volunteer = Auth::user()->volunteer;

        if (!$volunteer) {
            return redirect()->route('volunteer.dashboard')
                ->with('error', 'Volunteer profile not found.');
        }

        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'date' => 'required|date|before_or_equal:today',
            'hours' => 'required|numeric|min:0.25|max:24',
            'description' => 'required|string|max:1000',
        ]);

        // Verify volunteer is assigned to this campaign
        $isAssigned = $volunteer->campaigns()
            ->where('campaign_id', $validated['campaign_id'])
            ->exists();

        if (!$isAssigned) {
            return back()->withErrors(['campaign_id' => 'You are not assigned to this campaign.']);
        }

        VolunteerHour::create([
            'volunteer_id' => $volunteer->id,
            'campaign_id' => $validated['campaign_id'],
            'date' => $validated['date'],
            'hours' => $validated['hours'],
            'description' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()->route('volunteer.hours.index')
            ->with('success', 'Volunteer hours logged successfully and pending approval.');
    }

    /**
     * Show details of specific hour entry
     */
    public function show(VolunteerHour $hour)
    {
        $volunteer = Auth::user()->volunteer;

        // Ensure the hour belongs to this volunteer
        if ($hour->volunteer_id !== $volunteer->id) {
            abort(403, 'Unauthorized access.');
        }

        $hour->load(['campaign', 'approvedBy']);

        return view('volunteer.hours.show', compact('hour'));
    }

    /**
     * Show form to edit hour entry (only if pending)
     */
    public function edit(VolunteerHour $hour)
    {
        $volunteer = Auth::user()->volunteer;

        // Ensure the hour belongs to this volunteer
        if ($hour->volunteer_id !== $volunteer->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow editing pending hours
        if ($hour->status !== 'pending') {
            return redirect()->route('volunteer.hours.index')
                ->with('error', 'Only pending hours can be edited.');
        }

        $campaigns = $volunteer->campaigns()
            ->wherePivot('status', 'active')
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('volunteer.hours.edit', compact('hour', 'campaigns'));
    }

    /**
     * Update hour entry
     */
    public function update(Request $request, VolunteerHour $hour)
    {
        $volunteer = Auth::user()->volunteer;

        // Ensure the hour belongs to this volunteer
        if ($hour->volunteer_id !== $volunteer->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow editing pending hours
        if ($hour->status !== 'pending') {
            return redirect()->route('volunteer.hours.index')
                ->with('error', 'Only pending hours can be edited.');
        }

        $validated = $request->validate([
            'campaign_id' => 'required|exists:campaigns,id',
            'date' => 'required|date|before_or_equal:today',
            'hours' => 'required|numeric|min:0.25|max:24',
            'description' => 'required|string|max:1000',
        ]);

        // Verify volunteer is assigned to this campaign
        $isAssigned = $volunteer->campaigns()
            ->where('campaign_id', $validated['campaign_id'])
            ->exists();

        if (!$isAssigned) {
            return back()->withErrors(['campaign_id' => 'You are not assigned to this campaign.']);
        }

        $hour->update($validated);

        return redirect()->route('volunteer.hours.index')
            ->with('success', 'Volunteer hours updated successfully.');
    }

    /**
     * Delete hour entry (only if pending)
     */
    public function destroy(VolunteerHour $hour)
    {
        $volunteer = Auth::user()->volunteer;

        // Ensure the hour belongs to this volunteer
        if ($hour->volunteer_id !== $volunteer->id) {
            abort(403, 'Unauthorized access.');
        }

        // Only allow deleting pending hours
        if ($hour->status !== 'pending') {
            return redirect()->route('volunteer.hours.index')
                ->with('error', 'Only pending hours can be deleted.');
        }

        $hour->delete();

        return redirect()->route('volunteer.hours.index')
            ->with('success', 'Volunteer hours deleted successfully.');
    }

    /**
     * Export hours to CSV
     */
    public function export(Request $request)
    {
        $volunteer = Auth::user()->volunteer;

        $query = VolunteerHour::where('volunteer_id', $volunteer->id)
            ->with(['campaign'])
            ->orderBy('date', 'desc');

        // Apply filters if provided
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        if ($request->has('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $hours = $query->get();

        $fileName = 'volunteer_hours_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];

        $callback = function() use ($hours) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, ['Date', 'Campaign', 'Hours', 'Description', 'Status', 'Approved By', 'Approved Date']);
            
            foreach ($hours as $hour) {
                fputcsv($file, [
                    $hour->date->format('Y-m-d'),
                    $hour->campaign ? $hour->campaign->title : 'N/A',
                    $hour->hours,
                    $hour->description,
                    $hour->getStatusDisplayName(),
                    $hour->approvedBy ? $hour->approvedBy->name : 'N/A',
                    $hour->updated_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}