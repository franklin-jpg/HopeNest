<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\User;
use App\Models\Volunteer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Overall Statistics
        $stats = [
            'total_donations' => Donation::where('status', 'successful')->sum('amount'),
            'total_users' => User::where('role', 'user')->count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_volunteers' => Volunteer::approved()->count(),
            
            // Growth percentages (compared to last month)
            'donations_growth' => $this->calculateGrowth('donations'),
            'users_growth' => $this->calculateGrowth('users'),
            'volunteers_growth' => $this->calculateGrowth('volunteers'),
        ];

        // Recent Donations (last 5)
        $recentDonations = Donation::with(['campaign', 'user'])
            ->where('status', 'successful')
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($donation) {
                return [
                    'donor_name' => $donation->is_anonymous ? 'Anonymous' : $donation->donor_name,
                    'amount' => $donation->amount,
                    'campaign' => $donation->campaign->title ?? 'N/A',
                    'created_at' => $donation->created_at->diffForHumans(),
                ];
            });

        // Top Performing Campaigns (by raised amount)
        $topCampaigns = Campaign::withSum('successfulDonations as total_raised', 'amount')
            ->where('status', '!=', 'draft')
            ->orderByDesc('total_raised')
            ->take(3)
            ->get()
            ->map(function ($campaign) {
                $progressPercentage = $campaign->goal_amount > 0 
                    ? round(($campaign->total_raised / $campaign->goal_amount) * 100, 1)
                    : 0;
                
                return [
                    'title' => $campaign->title,
                    'total_raised' => $campaign->total_raised ?? 0,
                    'goal_amount' => $campaign->goal_amount,
                    'progress_percentage' => min($progressPercentage, 100),
                ];
            });

        // Recent Activities (last 10 activities)
        $recentActivities = $this->getRecentActivities();

        // Donation trend data for chart (last 7 days)
        $donationTrend = Donation::where('status', 'successful')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => Carbon::parse($item->date)->format('M d'),
                    'total' => (float) $item->total,
                    'count' => $item->count,
                ];
            });

        // Fill missing dates with zero values
        $donationTrend = $this->fillMissingDates($donationTrend);

        // Campaign ending soon
        $campaignsEndingSoon = Campaign::where('status', 'active')
            ->whereNotNull('end_date')
            ->where('end_date', '<=', Carbon::now()->addWeek())
            ->where('end_date', '>=', Carbon::now())
            ->count();

        return view('admin.dashboard', compact(
            'stats',
            'recentDonations',
            'topCampaigns',
            'recentActivities',
            'donationTrend',
            'campaignsEndingSoon'
        ));
    }

    /**
     * Calculate growth percentage compared to last month
     */
    private function calculateGrowth($type)
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        switch ($type) {
            case 'donations':
                $current = Donation::where('status', 'successful')
                    ->where('created_at', '>=', $currentMonth)
                    ->sum('amount');
                $previous = Donation::where('status', 'successful')
                    ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                    ->sum('amount');
                break;

            case 'users':
                $current = User::where('role', 'user')
                    ->where('created_at', '>=', $currentMonth)
                    ->count();
                $previous = User::where('role', 'user')
                    ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                    ->count();
                break;

            case 'volunteers':
                $current = User::where('role', 'volunteer')
                    ->where('created_at', '>=', $currentMonth)
                    ->count();
                $previous = User::where('role', 'volunteer')
                    ->whereBetween('created_at', [$lastMonth, $lastMonthEnd])
                    ->count();
                break;

            default:
                return 0;
        }

        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    /**
     * Get recent activities (donations, users, campaigns)
     */
    private function getRecentActivities()
    {
        $activities = collect();

        // Recent donations
        $donations = Donation::with('campaign')
            ->where('status', 'successful')
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($donation) {
                return [
                    'type' => 'donation',
                    'icon' => 'fa-donate',
                    'color' => 'primary',
                    'title' => 'New donation received',
                    'description' => ($donation->is_anonymous ? 'Anonymous' : $donation->donor_name) . ' donated $' . number_format($donation->amount, 2),
                    'time' => $donation->created_at,
                    'time_human' => $donation->created_at->diffForHumans(),
                ];
            });

        // Recent user registrations
        $users = User::where('role', 'user')
            ->latest()
            ->take(2)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'icon' => 'fa-user-plus',
                    'color' => 'blue-600',
                    'title' => 'New user registered',
                    'description' => $user->name . ' joined',
                    'time' => $user->created_at,
                    'time_human' => $user->created_at->diffForHumans(),
                ];
            });

        // Recent campaign launches
        $campaigns = Campaign::where('status', 'active')
            ->latest()
            ->take(2)
            ->get()
            ->map(function ($campaign) {
                return [
                    'type' => 'campaign',
                    'icon' => 'fa-bullhorn',
                    'color' => 'purple-600',
                    'title' => 'Campaign launched',
                    'description' => $campaign->title,
                    'time' => $campaign->created_at,
                    'time_human' => $campaign->created_at->diffForHumans(),
                ];
            });

        // Merge and sort by time
        return $activities
            ->concat($donations)
            ->concat($users)
            ->concat($campaigns)
            ->sortByDesc('time')
            ->take(10)
            ->values();
    }

    /**
     * Fill missing dates in donation trend with zero values
     */
    private function fillMissingDates($data)
    {
        $filledData = collect();
        $dates = collect();

        // Generate all dates for the last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('M d');
            $dates->push($date);
        }

        // Fill with existing data or zeros
        foreach ($dates as $date) {
            $existing = $data->firstWhere('date', $date);
            
            $filledData->push([
                'date' => $date,
                'total' => $existing ? $existing['total'] : 0,
                'count' => $existing ? $existing['count'] : 0,
            ]);
        }

        return $filledData;
    }
}