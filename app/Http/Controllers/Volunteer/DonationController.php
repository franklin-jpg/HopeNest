<?php

namespace App\Http\Controllers\Volunteer;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Donation::where(function ($q) {
            // Donations made by logged-in user
            $q->where('user_id', Auth::id())
              ->orWhere('donor_email', Auth::user()->email);
        })
        ->with('campaign')
        ->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('campaign')) {
            $query->where('campaign_id', $request->campaign);
        }

        $donations = $query->paginate(15);

        $campaigns = Auth::user()->donation()
            ->with('campaign')
            ->get()
            ->pluck('campaign.title', 'campaign_id')
            ->unique()
            ->prepend('All Campaigns', '');

        return view('volunteer.donations.index', compact('donations', 'campaigns'));
    }

    public function show(Donation $donation)
    {
        // Security: Only allow if donation belongs to this user/email
        if ($donation->user_id !== Auth::id() && $donation->donor_email !== Auth::user()->email) {
            abort(403);
        }

        $donation->load('campaign');

        return view('volunteer.donations.show', compact('donation'));
    }

    public function export(Request $request): StreamedResponse
    {
        $donations = Donation::where(function ($q) {
            $q->where('user_id', Auth::id())
              ->orWhere('donor_email', Auth::user()->email);
        })
        ->with('campaign')
        ->latest()
        ->get();

        $filename = "my-donations-" . now()->format('Y-m-d') . ".csv";

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($donations) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF"); // BOM for UTF-8

            // CSV Header
            fputcsv($file, [
                'Date',
                'Receipt No.',
                'Campaign',
                'Amount',
                'Fee Covered',
                'Processing Fee',
                'Total Paid',
                'Status',
                'Payment Method',
                'Frequency',
                'Anonymous'
            ]);

            foreach ($donations as $donation) {
                fputcsv($file, [
                    $donation->paid_at?->format('d M Y H:i') ?? $donation->created_at->format('d M Y H:i'),
                    $donation->receipt_number ?? '—',
                    $donation->campaign?->title ?? 'Deleted Campaign',
                    $donation->getFormattedAmountAttribute(),
                    $donation->cover_fee ? 'Yes' : 'No',
                    $donation->currency . ' ' . number_format($donation->processing_fee, 2),
                    $donation->getFormattedTotalAmountAttribute(),
                    ucfirst($donation->status),
                    ucfirst(str_replace('_', ' ', $donation->payment_method ?? '—')),
                    ucfirst($donation->frequency),
                    $donation->is_anonymous ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}