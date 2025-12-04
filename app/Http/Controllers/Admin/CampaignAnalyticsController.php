<?php

namespace App\Http\Controllers\Admin;

use App\Exports\CampaignAnalyticsExport;
use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use App\Models\CampaignView;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class CampaignAnalyticsController extends Controller
{
    /**
     * Display analytics overview for all campaigns
     */
    public function index(Request $request)
    {
        $dateRange = $request->get('date_range', '30'); // Default 30 days
        $startDate = Carbon::now()->subDays($dateRange);

        // Overall Statistics
        $overallStats = [
            'total_campaigns' => Campaign::count(),
            'active_campaigns' => Campaign::where('status', 'active')->count(),
            'total_raised' => Donation::where('status', 'successful')->sum('amount'),
            'total_donors' => Donation::where('status', 'successful')->distinct('user_id')->count('user_id'),
            'total_donations' => Donation::where('status', 'successful')->count(),
            'average_donation' => Donation::where('status', 'successful')->avg('amount') ?? 0,
        ];

        // Top Performing Campaigns
        $topCampaigns = Campaign::withCount(['successfulDonations as donors_count' => function($query) {
                $query->distinct('user_id');
            }])
            ->withSum('successfulDonations as total_raised', 'amount')
            ->where('status', '!=', 'draft')
            ->orderByDesc('total_raised')
            ->take(10)
            ->get()
            ->map(function($campaign) {
                return [
                    'id' => $campaign->id,
                    'title' => $campaign->title,
                    'total_raised' => $campaign->total_raised ?? 0,
                    'goal_amount' => $campaign->goal_amount,
                    'progress_percentage' => $campaign->goal_amount > 0 
                        ? round(($campaign->total_raised / $campaign->goal_amount) * 100, 2) 
                        : 0,
                    'donors_count' => $campaign->donors_count ?? 0,
                ];
            });

        // Donations Timeline (Last 30 days)
        $donationsTimeline = Donation::where('status', 'successful')
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Category Performance
        $categoryPerformance = Campaign::join('campaign_categories', 'campaigns.campaign_category_id', '=', 'campaign_categories.id')
            ->leftJoin('donations', function($join) {
                $join->on('campaigns.id', '=', 'donations.campaign_id')
                     ->where('donations.status', '=', 'successful');
            })
            ->select(
                'campaign_categories.name as category_name',
                DB::raw('COUNT(DISTINCT campaigns.id) as campaigns_count'),
                DB::raw('COALESCE(SUM(donations.amount), 0) as total_raised'),
                DB::raw('COUNT(donations.id) as donations_count')
            )
            ->groupBy('campaign_categories.id', 'campaign_categories.name')
            ->orderByDesc('total_raised')
            ->get();

        // Recent High-Value Donations
        $recentHighValueDonations = Donation::with(['user', 'campaign'])
            ->where('status', 'successful')
            ->orderByDesc('amount')
            ->take(10)
            ->get();

        return view('admin.analytics.index', compact(
            'overallStats',
            'topCampaigns',
            'donationsTimeline',
            'categoryPerformance',
            'recentHighValueDonations',
            'dateRange'
        ));
    }

    /**
     * Display detailed analytics for a specific campaign
     */
    public function show($id, Request $request)
    {
        $campaign = Campaign::with(['campaignCategory', 'donations'])
            ->withCount(['donations', 'successfulDonations'])
            ->findOrFail($id);

        $dateRange = $request->get('date_range', '30');
        $startDate = Carbon::now()->subDays($dateRange);

        // Core Metrics
        $metrics = [
            'total_raised' => $campaign->raised_amount,
            'goal_amount' => $campaign->goal_amount,
            'progress_percentage' => $campaign->progress_percentage,
            'total_donations' => $campaign->successfulDonations()->count(),
            'total_donors' => $campaign->successfulDonations()->distinct('user_id')->count('user_id'),
            'average_donation' => $campaign->successfulDonations()->avg('amount') ?? 0,
            'largest_donation' => $campaign->successfulDonations()->max('amount') ?? 0,
            'smallest_donation' => $campaign->successfulDonations()->min('amount') ?? 0,
            'median_donation' => $this->calculateMedianDonation($campaign),
        ];

        // View Statistics
        $viewStats = [
            'total_views' => CampaignView::where('campaign_id', $id)->count(),
            'unique_views' => CampaignView::where('campaign_id', $id)
                ->distinct('ip_address')
                ->count('ip_address'),
            'views_last_7_days' => CampaignView::where('campaign_id', $id)
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
            'views_today' => CampaignView::where('campaign_id', $id)
                ->whereDate('created_at', Carbon::today())
                ->count(),
        ];

        // Conversion Rate
        $conversionRate = $viewStats['unique_views'] > 0 
            ? round(($metrics['total_donors'] / $viewStats['unique_views']) * 100, 2)
            : 0;

        // Donation Timeline
        $donationTimeline = $campaign->successfulDonations()
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(DISTINCT user_id) as unique_donors')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Donation Distribution (by amount ranges)
        $donationDistribution = $this->getDonationDistribution($campaign);

        // Hourly Donation Pattern
        $hourlyPattern = $campaign->successfulDonations()
            ->select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Day of Week Pattern
        $dayPattern = $campaign->successfulDonations()
            ->select(
                DB::raw('DAYNAME(created_at) as day'),
                DB::raw('DAYOFWEEK(created_at) as day_number'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('day', 'day_number')
            ->orderBy('day_number')
            ->get();

        // Traffic Sources
        $trafficSources = CampaignView::where('campaign_id', $id)
            ->select(
                'referrer',
                DB::raw('COUNT(*) as views'),
                DB::raw('COUNT(DISTINCT ip_address) as unique_views')
            )
            ->groupBy('referrer')
            ->orderByDesc('views')
            ->take(10)
            ->get()
            ->map(function($source) {
                return [
                    'source' => $this->formatReferrer($source->referrer),
                    'views' => $source->views,
                    'unique_views' => $source->unique_views,
                ];
            });

        // Device Statistics
        $deviceStats = CampaignView::where('campaign_id', $id)
            ->select(
                'device_type',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('device_type')
            ->get();

        // Geographic Data (if available)
        $geographicData = CampaignView::where('campaign_id', $id)
            ->select(
                'country',
                'city',
                DB::raw('COUNT(*) as views')
            )
            ->whereNotNull('country')
            ->groupBy('country', 'city')
            ->orderByDesc('views')
            ->take(10)
            ->get();

        // Recent Donations
        $recentDonations = $campaign->successfulDonations()
            ->with('user')
            ->latest()
            ->take(15)
            ->get();

        // Top Donors
        $topDonors = $campaign->successfulDonations()
            ->select(
                'user_id',
                DB::raw('SUM(amount) as total_donated'),
                DB::raw('COUNT(*) as donation_count')
            )
            ->with('user')
            ->groupBy('user_id')
            ->orderByDesc('total_donated')
            ->take(10)
            ->get();

      

        // Anonymous vs Named Donations
        $anonymityStats = [
            'named' => $campaign->successfulDonations()->where('is_anonymous', false)->count(),
            'anonymous' => $campaign->successfulDonations()->where('is_anonymous', true)->count(),
        ];

        // Growth Rate (compare with previous period)
        $growthRate = $this->calculateGrowthRate($campaign, $dateRange);

        // Projected Completion Date
        $projectedCompletion = $this->projectCompletionDate($campaign, $donationTimeline);

        return view('admin.analytics.show', compact(
            'campaign',
            'metrics',
            'viewStats',
            'conversionRate',
            'donationTimeline',
            'donationDistribution',
            'hourlyPattern',
            'dayPattern',
            'trafficSources',
            'deviceStats',
            'geographicData',
            'recentDonations',
            'topDonors',
            'anonymityStats',
            'growthRate',
            'projectedCompletion',
            'dateRange'
        ));
    }

    /**
     * Export campaign analytics data
     */
    public function export($id, Request $request)
    {
        $campaign = Campaign::findOrFail($id);
        $format = $request->get('format', 'csv', 'excel'); // csv, pdf, excel

        // Gather all analytics data
        $data = $this->gatherAnalyticsData($campaign);

        switch ($format) {
    case 'pdf':
        return $this->exportToPdf($campaign, $data);

    case 'excel':
        return $this->exportToExcel($campaign, $data);

    case 'csv':
    default:
        return $this->exportToCsv($campaign, $data);
}

    }

    /**
     * Compare multiple campaigns
     */
    public function compare(Request $request)
    {
        $campaignIds = $request->get('campaigns', []);
        
       

        $campaigns = Campaign::whereIn('id', $campaignIds)
            ->withCount('successfulDonations')
            ->withSum('successfulDonations as total_raised', 'amount')
            ->get();

        $comparison = $campaigns->map(function($campaign) {
            return [
                'id' => $campaign->id,
                'title' => $campaign->title,
                'total_raised' => $campaign->total_raised ?? 0,
                'goal_amount' => $campaign->goal_amount,
                'progress_percentage' => $campaign->progress_percentage,
                'total_donations' => $campaign->successful_donations_count,
                'total_donors' => $campaign->successfulDonations()->distinct('user_id')->count('user_id'),
                'average_donation' => $campaign->successfulDonations()->avg('amount') ?? 0,
                'days_active' => $campaign->start_date 
                    ? Carbon::parse($campaign->start_date)->diffInDays(now()) 
                    : 0,
            ];
        });

        return view('admin.analytics.compare', compact('campaigns', 'comparison'));
    }

    // Helper Methods

    private function calculateMedianDonation($campaign)
    {
        $donations = $campaign->successfulDonations()->pluck('amount')->sort()->values();
        $count = $donations->count();
        
        if ($count === 0) return 0;
        
        $middle = floor($count / 2);
        
        if ($count % 2 === 0) {
            return ($donations[$middle - 1] + $donations[$middle]) / 2;
        }
        
        return $donations[$middle];
    }

    private function getDonationDistribution($campaign)
    {
        return [
            'under_50' => $campaign->successfulDonations()->where('amount', '<', 50)->count(),
            '50_to_100' => $campaign->successfulDonations()->whereBetween('amount', [50, 100])->count(),
            '100_to_500' => $campaign->successfulDonations()->whereBetween('amount', [100, 500])->count(),
            '500_to_1000' => $campaign->successfulDonations()->whereBetween('amount', [500, 1000])->count(),
            'over_1000' => $campaign->successfulDonations()->where('amount', '>', 1000)->count(),
        ];
    }

    private function formatReferrer($referrer)
    {
        if (empty($referrer) || $referrer === 'direct') {
            return 'Direct Traffic';
        }
        
        $domain = parse_url($referrer, PHP_URL_HOST);
        return $domain ?? 'Unknown';
    }

    private function calculateGrowthRate($campaign, $days)
    {
        $currentPeriodEnd = Carbon::now();
        $currentPeriodStart = $currentPeriodEnd->copy()->subDays($days);
        $previousPeriodStart = $currentPeriodStart->copy()->subDays($days);

        $currentTotal = $campaign->successfulDonations()
            ->whereBetween('created_at', [$currentPeriodStart, $currentPeriodEnd])
            ->sum('amount');

        $previousTotal = $campaign->successfulDonations()
            ->whereBetween('created_at', [$previousPeriodStart, $currentPeriodStart])
            ->sum('amount');

        if ($previousTotal == 0) return 0;

        return round((($currentTotal - $previousTotal) / $previousTotal) * 100, 2);
    }

    private function projectCompletionDate($campaign, $donationTimeline)
    {
        if ($campaign->progress_percentage >= 100) {
            return 'Goal Reached';
        }

        $remaining = $campaign->goal_amount - $campaign->raised_amount;
        
        if ($donationTimeline->isEmpty() || $remaining <= 0) {
            return 'Unable to project';
        }

        $averageDailyRaise = $donationTimeline->avg('total');
        
        if ($averageDailyRaise <= 0) {
            return 'Unable to project';
        }

        $daysRemaining = ceil($remaining / $averageDailyRaise);
        $projectedDate = Carbon::now()->addDays($daysRemaining);

        return $projectedDate->format('M d, Y') . ' (' . $daysRemaining . ' days)';
    }

    private function gatherAnalyticsData($campaign)
    {
        // Compile all necessary data for export
        return [
            'campaign' => $campaign,
            'donations' => $campaign->successfulDonations()->with('user')->get(),
            'summary' => [
                'total_raised' => $campaign->raised_amount,
                'goal_amount' => $campaign->goal_amount,
                'total_donors' => $campaign->donorsCount(),
                'average_donation' => $campaign->successfulDonations()->avg('amount'),
            ],
        ];
    }

    private function exportToCsv($campaign, $data)
    {
        $filename = 'campaign-analytics-' . $campaign->slug . '-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, ['Campaign Analytics Export']);
            fputcsv($file, ['Campaign', $data['campaign']->title]);
            fputcsv($file, ['Exported', date('Y-m-d H:i:s')]);
            fputcsv($file, []);
            
            // Summary
            fputcsv($file, ['Summary']);
            fputcsv($file, ['Total Raised', $data['summary']['total_raised']]);
            fputcsv($file, ['Goal Amount', $data['summary']['goal_amount']]);
            fputcsv($file, ['Total Donors', $data['summary']['total_donors']]);
            fputcsv($file, ['Average Donation', $data['summary']['average_donation']]);
            fputcsv($file, []);
            
            // Donations
            fputcsv($file, ['Donations']);
            fputcsv($file, ['Date', 'Donor', 'Amount', 'Payment Method', 'Status']);
            
            foreach ($data['donations'] as $donation) {
                fputcsv($file, [
                    $donation->created_at->format('Y-m-d H:i'),
                    $donation->user->name ?? 'Anonymous',
                    $donation->amount,
                    $donation->payment_method,
                    $donation->status,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

   private function exportToPdf($campaign, $data)
{
    $pdf = Pdf::loadView(
        'admin.campaigns.exports.analytics_pdf',
        compact('campaign', 'data')
    );

    $fileName = 'campaign_' . $campaign->id . '_analytics.pdf';

    return $pdf->download($fileName);
}

    

    private function exportToExcel($campaign, $data)
{
    $fileName = 'campaign_' . $campaign->id . '_analytics.xlsx';

    return Excel::download(
        new CampaignAnalyticsExport($data),
        $fileName
    );
}

}