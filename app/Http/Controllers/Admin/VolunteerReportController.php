<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Volunteer;
use App\Models\VolunteerHour;
use App\Models\VolunteerCampaign;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VolunteerReportController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'totalVolunteers' => Volunteer::approved()->count(),
            'pendingVolunteers' => Volunteer::pending()->count(),
            'rejectedVolunteers' => Volunteer::rejected()->count(),
            'totalHours' => VolunteerHour::approved()->sum('hours'),
            'pendingHours' => VolunteerHour::pending()->sum('hours'),
            'activeCampaigns' => VolunteerCampaign::active()->count(),
        ];

        $topVolunteers = Volunteer::withCount(['hours as approved_hours' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->whereHas('hours', function ($query) {
                $query->where('status', 'approved');
            })
            ->orderByDesc('approved_hours')
            ->limit(10)
            ->get();

        $recentHours = VolunteerHour::with(['volunteer.user', 'campaign'])
            ->where('status', 'approved')
            ->latest()
            ->limit(10)
            ->get();

        $filter = $request->get('filter', 'all');
        $volunteers = Volunteer::with(['user', 'hours'])
            ->when($filter === 'active', function ($query) {
                return $query->where('status', 'approved');
            })
            ->when($filter === 'pending', function ($query) {
                return $query->where('status', 'pending');
            })
            ->latest()
            ->paginate(10);

        return view('admin.volunteer.reports.index', compact(
            'stats',
            'topVolunteers',
            'recentHours',
            'volunteers',
            'filter'
        ));
    }

    public function hoursReport(Request $request)
    {
        $stats = [
            'totalHours' => VolunteerHour::approved()->sum('hours'),
            'pendingHours' => VolunteerHour::pending()->sum('hours'),
            'rejectedHours' => VolunteerHour::where('status', 'rejected')->sum('hours'),
            'volunteersWithHours' => VolunteerHour::approved()->distinct('volunteer_id')->count(),
        ];

        $hours = VolunteerHour::with(['volunteer.user', 'campaign'])
            ->when($request->date_range, function ($query) use ($request) {
                $dates = explode(' - ', $request->date_range);
                if (count($dates) === 2) {
                    $query->whereBetween('date', $dates);
                }
            })
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(15);

        return view('admin.volunteer.reports.hours', compact('stats', 'hours'));
    }

    public function export(Request $request)
    {
        $filename = 'volunteer-report-' . now()->format('Y-m-d') . '.csv';
        
        $volunteers = Volunteer::with(['user', 'hours'])
            ->when($request->status, function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($volunteers) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Name', 'Email', 'Phone', 'Status', 'Total Hours', 'Campaigns', 'Applied Date'
            ]);

            foreach ($volunteers as $volunteer) {
                fputcsv($file, [
                    $volunteer->user->name,
                    $volunteer->user->email,
                    $volunteer->phone,
                    ucfirst($volunteer->status),
                    $volunteer->hours()->where('status', 'approved')->sum('hours'),
                    $volunteer->campaigns()->count(),
                    $volunteer->created_at->format('M d, Y')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}