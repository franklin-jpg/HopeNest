<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignedCampaignsController extends Controller
{
    public function index(Request $request)
    {
        $volunteer = Volunteer::where('user_id', Auth::id())
            ->with(['campaigns' => function ($query) {
                $query->with('campaignCategory')
                    ->withPivot('status', 'assigned_at', 'assigned_by')
                    ->orderByPivot('assigned_at', 'desc');
            }])
            ->firstOrFail();

        $status = $request->get('status');

        // Build the query properly using the relationship
        $campaignsQuery = $volunteer->campaigns()
            ->with('campaignCategory')
            ->withPivot('status', 'assigned_at', 'assigned_by')
            ->orderByPivot('assigned_at', 'desc');

        if ($status && in_array($status, ['assigned', 'active', 'completed', 'removed'])) {
            $campaignsQuery->wherePivot('status', $status);
        }

        $campaigns = $campaignsQuery->paginate(10);


        return view('volunteer.assigned-campaigns.index', compact('volunteer', 'campaigns', 'status'));
    }
}