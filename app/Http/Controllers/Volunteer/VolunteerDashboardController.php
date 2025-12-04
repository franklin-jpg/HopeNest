<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\Campaign;
use App\Models\VolunteerHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VolunteerDashboardController extends Controller
{
    public function index()
    {
        try {
            // Get the authenticated user's volunteer record
            $volunteer = Volunteer::where('user_id', Auth::id())
                ->with(['campaigns', 'hours.campaign'])
                ->first();

            // Redirect if not a volunteer
            if (!$volunteer) {
                return redirect()->route('home')->with('error', 'You are not registered as a volunteer.');
            }

        // Calculate statistics
        $stats = [
            'total_hours' => $volunteer->hours()->where('volunteer_hours.status', 'approved')->sum('hours'),
            'pending_hours' => $volunteer->hours()->where('volunteer_hours.status', 'pending')->sum('hours'),
            'hours_this_month' => $volunteer->hours()
                ->where('volunteer_hours.status', 'approved')
                ->whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->sum('hours'),
            
            'tasks_completed' => $volunteer->campaigns()
                ->wherePivot('status', 'completed')
                ->count(),
            'tasks_pending' => $volunteer->campaigns()
                ->wherePivotIn('status', ['assigned', 'active'])
                ->count(),
            
            'events_attended' => $volunteer->campaigns()
                ->wherePivot('status', 'completed')
                ->count(),
            'upcoming_events' => $volunteer->campaigns()
                ->where('campaigns.status', 'active')
                ->where('campaigns.end_date', '>=', now())
                ->wherePivotIn('status', ['assigned', 'active'])
                ->count(),
            
            // Impact Score: combination of hours and campaigns
            'impact_score' => ($volunteer->hours()->where('volunteer_hours.status', 'approved')->sum('hours') * 5) + 
                             ($volunteer->campaigns()->count() * 20)
        ];

        // Get active campaigns/tasks
        $activeTasks = $volunteer->campaigns()
            ->where('campaigns.status', 'active')
            ->wherePivotIn('status', ['assigned', 'active'])
            ->with('campaignCategory')
            ->orderByPivot('assigned_at', 'desc')
            ->take(5)
            ->get();

        // Get upcoming events (campaigns with future end dates)
        $upcomingEvents = $volunteer->campaigns()
            ->where('campaigns.status', 'active')
            ->where('campaigns.end_date', '>=', now())
            ->wherePivotIn('status', ['assigned', 'active'])
            ->orderBy('campaigns.start_date', 'asc')
            ->take(3)
            ->get();

        // Get recent activity (recent volunteer hours)
        $recentActivity = $volunteer->hours()
            ->with('campaign')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

     // Calculate volunteer rank/percentile (optional)
$totalVolunteers = Volunteer::where('status', 'approved')->count();

// Count volunteers with fewer approved hours
$approvedHours = $volunteer->hours()->where('volunteer_hours.status', 'approved')->sum('hours');

// Subquery: Get approved hours summed per volunteer
$sub = DB::table('volunteer_hours')
    ->select('volunteer_id', DB::raw('SUM(hours) as total_hours'))
    ->where('status', 'approved')
    ->groupBy('volunteer_id');

// Count approved volunteers who have less hours
$volunteersWithLessHours = DB::table('volunteers')
    ->joinSub($sub, 'hours', function($join) {
        $join->on('volunteers.id', '=', 'hours.volunteer_id');
    })
    ->where('volunteers.status', 'approved')
    ->where('hours.total_hours', '<', $approvedHours)
    ->count();

$percentile = $totalVolunteers > 0 ? round(($volunteersWithLessHours / $totalVolunteers) * 100) : 0;

            // Use lowercase 'volunteer' for the view path (Laravel convention)
            return view('volunteer.dashboard', compact(
                'volunteer',
                'stats',
                'activeTasks',
                'upcomingEvents',
                'recentActivity',
                'percentile'
            ));

        } catch (\Exception $e) {
            // Log the error
            dd($e->getMessage());
            Log::error('Volunteer Dashboard Error: ' . $e->getMessage());
            
            // Return with error message
            return redirect()->route('home')->with('error', 'An error occurred loading your dashboard. Please try again.');
        }
    }
}