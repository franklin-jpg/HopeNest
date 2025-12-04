<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CampaignReportExport;
use App\Models\CampaignCategory;

class CampaignReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'all');
        $category = $request->get('category');

        $campaigns = Campaign::with(['campaignCategory', 'successfulDonations'])
            ->when($category, fn($q) => $q->where('campaign_category_id', $category))
            ->when($period !== 'all', function ($q) use ($period) {
                $start = match ($period) {
                    'week' => now()->startOfWeek(),
                    'month' => now()->startOfMonth(),
                    'year' => now()->startOfYear(),
                    default => now()->subYears(100),
                };
                return $q->where('created_at', '>=', $start);
            })
            ->get();

        $report = $this->generateReport($campaigns);

        $categories = CampaignCategory::where('status', 'active')->get();

        return view('admin.campaigns.reports.index', compact(
            'report', 'period', 'category', 'categories'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $report = $this->generateReport(Campaign::with('successfulDonations')->get());

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.campaigns.reports.campaign-pdf', compact('report'))
                       ->setPaper('a4', 'landscape');
            return $pdf->download('campaign-performance-report-' . now()->format('Y-m-d') . '.pdf');
        }

        return Excel::download(
            new CampaignReportExport($report),
            'campaign-performance-report-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    private function generateReport($campaigns)
    {
        $completed = $campaigns->where('status', 'completed');
        $successful = $campaigns->filter(fn($c) => $c->raised_amount >= $c->goal_amount);

        $avgTimeToGoal = $successful->avg(function ($c) {
            $firstDonation = $c->successfulDonations()->oldest('paid_at')->first();
            $lastDonation = $c->successfulDonations()->latest('paid_at')->first();
            if (!$firstDonation || !$lastDonation) return null;
            return $firstDonation->paid_at?->diffInDays($lastDonation->paid_at);
        });

        $topEngagement = $campaigns->sortByDesc(fn($c) => $c->successfulDonations()->count())->take(10);

        return [
            'total_campaigns' => $campaigns->count(),
            'active_campaigns' => $campaigns->where('status', 'active')->count(),
            'completed_campaigns' => $completed->count(),
            'goal_achieved_count' => $successful->count(),
            'success_rate' => $campaigns->count() > 0
                ? round(($successful->count() / $campaigns->count()) * 100, 2)
                : 0,
            'avg_time_to_goal_days' => round($avgTimeToGoal ?? 0, 1),
            'total_raised' => $campaigns->sum('raised_amount'),
            'total_goal' => $campaigns->sum('goal_amount'),
            'overall_progress' => $campaigns->sum('goal_amount') > 0
                ? round(($campaigns->sum('raised_amount') / $campaigns->sum('goal_amount')) * 100, 2)
                : 0,

            'top_performers' => $campaigns->map(fn($c) => [
                'id' => $c->id,
                'title' => $c->title,
                'category' => $c->campaignCategory?->name,
                'goal' => $c->goal_amount,
                'raised' => $c->raised_amount,
                'progress' => $c->getProgressPercentageAttribute(),
                'donations' => $c->successfulDonations()->count(),
                'donors' => $c->successfulDonations()->distinct('donor_email')->count('donor_email'),
                'avg_donation' => $c->successfulDonations()->avg('amount_in_base_currency') ?? 0,
                'status' => ucfirst($c->status),
                'goal_achieved' => $c->raised_amount >= $c->goal_amount,
            ])->sortByDesc('raised')->take(15),

            'most_engaging' => $topEngagement->map(fn($c) => [
                'title' => $c->title,
                'donations_count' => $c->successfulDonations()->count(),
                'donors' => $c->successfulDonations()->distinct('donor_email')->count(),
            ])->take(10),
        ];
    }
}