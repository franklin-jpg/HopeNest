<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\VolunteerHour;
use Illuminate\Http\Request;

class VolunteerHourController extends Controller
{
    public function index(Request $request)
    {
        $query = VolunteerHour::with(['volunteer.user', 'campaign', 'approvedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by volunteer
        if ($request->filled('volunteer_id')) {
            $query->where('volunteer_id', $request->volunteer_id);
        }

        // Filter by campaign
        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', $request->campaign_id);
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->date_to);
        }

        $hours = $query->latest('date')->paginate(20);
        $volunteers = Volunteer::approved()->with('user')->get();

        return view('admin.volunteer-hours.index', compact('hours', 'volunteers'));
    }

    public function pending()
    {
        $hours = VolunteerHour::with(['volunteer.user', 'campaign'])
            ->pending()
            ->latest('date')
            ->paginate(15);

        return view('admin.volunteer-hours.pending', compact('hours'));
    }

    public function approve(VolunteerHour $hour)
    {
        $hour->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Volunteer hours approved successfully.');
    }

    public function reject(Request $request, VolunteerHour $hour)
    {
        $hour->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Volunteer hours rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $request->validate([
            'hour_ids' => 'required|array',
            'hour_ids.*' => 'exists:volunteer_hours,id',
        ]);

        VolunteerHour::whereIn('id', $request->hour_ids)->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Selected hours approved successfully.');
    }
}
