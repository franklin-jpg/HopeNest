<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\Campaign;
use App\Models\CampaignCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\DonationsReportExport;

class DonationReportController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $report = $this->generateReport($startDate, $endDate);

        $campaigns = Campaign::select('id', 'title')->get();
        $categories = CampaignCategory::withCount('campaign')->get();

        return view('admin.donations.reports.index', compact(
            'report', 'period', 'startDate', 'endDate', 'campaigns', 'categories'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $period = $request->get('period', 'monthly');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $report = $this->generateReport($startDate, $endDate);

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.donations.reports.donations-pdf', compact('report', 'period', 'startDate', 'endDate'))
                       ->setPaper('a4', 'landscape');

            return $pdf->download("donation-report-{$period}-" . now()->format('Y-m-d') . ".pdf");
        }

        return Excel::download(
            new DonationsReportExport($report, $period, $startDate, $endDate),
            "donation-report-{$period}-" . now()->format('Y-m-d') . ".xlsx"
        );
    }

    private function getStartDate($period)
    {
        return match ($period) {
            'daily'   => now()->startOfDay(),
            'weekly'  => now()->startOfWeek(),
            'monthly' => now()->startOfMonth(),
            'yearly'  => now()->startOfYear(),
            default   => now()->subDays(30),
        };
    }

    private function generateReport($startDate, $endDate)
    {
        $donations = Donation::successful()
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('paid_at', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->whereNull('paid_at')
                         ->whereBetween('created_at', [$startDate, $endDate])
                         ->where('status', 'successful');
                  });
            });

        $summary = (clone $donations)->selectRaw('
                COUNT(*) as total_donations,
                COALESCE(SUM(amount_in_base_currency), 0) as total_revenue,
                COALESCE(SUM(processing_fee * exchange_rate), 0) as total_fees,
                COALESCE(AVG(amount_in_base_currency), 0) as avg_donation
            ')->first();

        return [
            'period' => [
                'start' => $startDate->format('d M Y'),
                'end'   => $endDate->format('d M Y'),
            ],
            'summary' => [
                'total_donations' => $summary->total_donations ?? 0,
                'total_revenue'   => round($summary->total_revenue, 2),
                'total_fees'      => round($summary->total_fees, 2),
                'net_revenue'     => round($summary->total_revenue - $summary->total_fees, 2),
                'avg_donation'    => round($summary->avg_donation, 2),
            ],
            'by_campaign'       => $this->byCampaign($startDate, $endDate),
            'by_category'       => $this->byCategory($startDate, $endDate),
            'by_payment_method' => $this->byPaymentMethod($startDate, $endDate),
            'timeline'          => $this->timelineData($startDate, $endDate),
        ];
    }

    private function byCampaign($start, $end)
    {
        return Donation::successful()
            ->with('campaign')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('paid_at', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->whereNull('paid_at')
                         ->whereBetween('created_at', [$start, $end])
                         ->where('status', 'successful');
                  });
            })
            ->groupBy('campaign_id')
            ->selectRaw('
                campaign_id,
                COUNT(*) as count,
                SUM(amount_in_base_currency) as total
            ')
            ->with('campaign:id,title')
            ->orderByDesc('total')
            ->get()
            ->map(fn($item) => [
                'campaign' => $item->campaign?->title ?? 'Unknown',
                'count'    => $item->count,
                'total'    => round($item->total, 2),
            ]);
    }

    private function byCategory($start, $end)
    {
        return CampaignCategory::selectRaw('
                campaign_categories.id,
                campaign_categories.name,
                COUNT(donations.id) as donation_count,
                COALESCE(SUM(donations.amount_in_base_currency), 0) as total_amount
            ')
            ->leftJoin('campaigns', 'campaign_categories.id', '=', 'campaigns.campaign_category_id')
            ->leftJoin('donations', function ($join) use ($start, $end) {
                $join->on('campaigns.id', '=', 'donations.campaign_id')
                     ->where('donations.status', 'successful')
                     ->where(function ($q) use ($start, $end) {
                         $q->whereBetween('donations.paid_at', [$start, $end])
                           ->orWhere(function ($q2) use ($start, $end) {
                               $q2->whereNull('donations.paid_at')
                                  ->whereBetween('donations.created_at', [$start, $end])
                                  ->where('donations.status', 'successful');
                           });
                     });
            })
            ->groupBy('campaign_categories.id', 'campaign_categories.name')
            ->orderByDesc('total_amount')
            ->get()
            ->map(fn($cat) => [
                'category' => $cat->name,
                'count'    => $cat->donation_count ?? 0,
                'total'    => round($cat->total_amount, 2),
            ]);
    }

    private function byPaymentMethod($start, $end)
    {
        return Donation::successful()
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('paid_at', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->whereNull('paid_at')
                         ->whereBetween('created_at', [$start, $end])
                         ->where('status', 'successful');
                  });
            })
            ->groupBy('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount_in_base_currency) as total')
            ->orderByDesc('total')
            ->get()
            ->map(fn($item) => [
                'method' => ucfirst(str_replace('_', ' ', $item->payment_method ?? 'Unknown')),
                'count'  => $item->count,
                'total'  => round($item->total, 2),
            ]);
    }

    private function timelineData($start, $end)
    {
        $interval = $this->getInterval($start, $end);

        return Donation::successful()
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('paid_at', [$start, $end])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->whereNull('paid_at')
                         ->whereBetween('created_at', [$start, $end])
                         ->where('status', 'successful');
                  });
            })
            ->selectRaw("DATE(COALESCE(paid_at, created_at)) as date, SUM(amount_in_base_currency) as total")
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date')
            ->mapWithKeys(fn($value, $key) => [Carbon::parse($key)->format($interval) => round($value, 2)])
            ->toArray();
    }

    private function getInterval($start, $end)
    {
        $days = $start->diffInDays($end);
        return $days <= 31 ? 'd M' : ($days <= 365 ? 'M Y' : 'Y');
    }
}