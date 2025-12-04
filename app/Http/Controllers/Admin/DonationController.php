<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DonationReceiptMail;
use App\Mail\ThankYouMail;
use App\Models\Donation;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class DonationController extends Controller
{
    /**
     * Display ALL donations across all campaigns with filters
     */
    public function index(Request $request)
    {
        $query = Donation::with(['campaign', 'user']);

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('donor_name', 'like', '%' . $request->search . '%')
                  ->orWhere('donor_email', 'like', '%' . $request->search . '%')
                  ->orWhere('receipt_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('campaign', fn($q) => $q->where('title', 'like', '%' . $request->search . '%'));
            });
        }

        // Filters
        if ($request->filled('campaign_id')) $query->where('campaign_id', $request->campaign_id);
        if ($request->filled('status'))       $query->where('status', $request->status);
        if ($request->filled('min_amount'))   $query->where('amount', '>=', $request->min_amount);
        if ($request->filled('max_amount'))   $query->where('amount', '<=', $request->max_amount);
        if ($request->filled('date_from'))    $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))      $query->whereDate('created_at', '<=', $request->date_to);

        // Export
        if ($request->has('export')) {
            return $this->exportAllDonations($query->get(), $request->export);
        }

        $donations = $query->latest()->paginate(20);
        $campaigns = Campaign::select('id', 'title')->get();

        $stats = [
            'total'         => Donation::count(),
            'successful'    => Donation::where('status', 'successful')->count(),
            'pending'       => Donation::where('status', 'pending')->count(),
            'failed'        => Donation::where('status', 'failed')->count(),
            'total_amount'  => Donation::where('status', 'successful')->sum('amount'),
        ];

        return view('admin.donations.index', compact('donations', 'campaigns', 'stats'));
    }

  

    
    /**
     * Show single donation
     */
    public function show($id)
    {
        $donation = Donation::with(['campaign.campaignCategory', 'user'])->findOrFail($id);
        return view('admin.donations.show', compact('donation'));
    }

    /**
     * Show failed donations
     */
    public function failed(Request $request)
    {
        $query = Donation::with(['campaign', 'user'])->where('status', 'failed');

        if ($request->filled('date_from'))  $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))    $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('campaign_id')) $query->where('campaign_id', $request->campaign_id);

        $donations = $query->latest()->paginate(20);
        $campaigns = Campaign::select('id', 'title')->get();

        $stats = [
            'total_failed' => Donation::where('status', 'failed')->count(),
            'failed_amount' => Donation::where('status', 'failed')->sum('amount'),
            'last_24h' => Donation::where('status', 'failed')
                                  ->where('created_at', '>=', now()->subDay())
                                  ->count(),
        ];

        return view('admin.donations.failed', compact('donations', 'campaigns', 'stats'));
    }

    /**
     * AJAX donation details
     */
    public function details($id)
    {
        $donation = Donation::with(['campaign', 'user'])->findOrFail($id);

        return response()->json([
            'id'               => $donation->id,
            'receipt_number'   => $donation->receipt_number,
            'donor_name'       => $donation->is_anonymous ? 'Anonymous' : $donation->donor_name,
            'donor_email'      => $donation->is_anonymous ? null : $donation->donor_email,
            'donor_phone'      => $donation->is_anonymous ? null : $donation->donor_phone,
            'amount'           => $donation->amount,
            'currency_symbol'  => $donation->getCurrencySymbol(),
            'formatted_amount' => $donation->getFormattedAmountAttribute(),
            'processing_fee'   => $donation->processing_fee,
            'total_amount'     => $donation->total_amount,
            'payment_method'   => $donation->payment_method,
            'payment_reference'=> $donation->payment_reference,
            'transaction_id'   => $donation->transaction_id,
            'status'           => $donation->status,
            'is_anonymous'     => $donation->is_anonymous,
            'message'          => $donation->message,
            'created_at'       => $donation->created_at->format('M d, Y \a\t g:i A'),
            'paid_at'          => $donation->paid_at?->format('M d, Y \a\t g:i A'),
            'campaign'         => [
                'id'    => $donation->campaign->id,
                'title' => $donation->campaign->title,
            ],
            'user' => $donation->user ? $donation->user->only(['id', 'name', 'email']) : null,
        ]);
    }

    /**
     * Export to CSV (single campaign)
     */
    protected function exportCSV($donations, $campaign)
    {
        $filename = 'donations_' . Str::slug($campaign->title) . '_' . date('Y-m-d') . '.csv';
        $headers  = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($donations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Receipt', 'Name', 'Email', 'Phone', 'Amount', 'Fee', 'Total',
                'Method', 'Status', 'Date', 'Paid At', 'Transaction ID'
            ]);

            foreach ($donations as $d) {
                fputcsv($file, [
                    $d->receipt_number ?? '',
                    $d->is_anonymous ? 'Anonymous' : $d->donor_name,
                    $d->is_anonymous ? '' : $d->donor_email,
                    $d->is_anonymous ? '' : $d->donor_phone,
                    $d->amount,
                    $d->processing_fee,
                    $d->total_amount,
                    $d->payment_method ?? '',
                    $d->status,
                    $d->created_at->format('Y-m-d H:i'),
                    $d->paid_at?->format('Y-m-d H:i') ?? '',
                    $d->transaction_id ?? ''
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }

    /**
     * Export all donations
     */
    protected function exportAllDonations($donations, $format)
    {
        $filename = 'all_donations_' . date('Y-m-d') . '.' . $format;
        $headers  = ['Content-Type' => $format === 'xlsx' ? 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' : 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($donations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, [
                'Receipt', 'Campaign', 'Name', 'Email', 'Phone', 'Amount', 'Fee', 'Total',
                'Method', 'Status', 'Date', 'Paid At', 'Transaction ID'
            ]);

            foreach ($donations as $d) {
                fputcsv($file, [
                    $d->receipt_number ?? '',
                    $d->campaign->title ?? '',
                    $d->is_anonymous ? 'Anonymous' : $d->donor_name,
                    $d->is_anonymous ? '' : $d->donor_email,
                    $d->is_anonymous ? '' : $d->donor_phone,
                    $d->amount,
                    $d->processing_fee,
                    $d->total_amount,
                    $d->payment_method ?? '',
                    $d->status,
                    $d->created_at->format('Y-m-d H:i'),
                    $d->paid_at?->format('Y-m-d H:i') ?? '',
                    $d->transaction_id ?? ''
                ]);
            }
            fclose($file);
        }, 200, $headers);
    }

    /** Update status */
    public function updateStatus(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        $request->validate(['status' => 'required|in:pending,successful,failed,refunded']);

        $old = $donation->status;
        $donation->update(['status' => $request->status]);

        Log::info('Donation status changed', [
            'donation_id' => $id,
            'from' => $old,
            'to' => $request->status,
            'by' => auth()->id()
        ]);

        return back()->with('success', 'Status updated!');
    }

    /** Resend receipt */
   /** Resend receipt - Fully AJAX Compatible */
public function resendReceipt($id)
{
    $donation = Donation::findOrFail($id);

    // Block anonymous or non-successful donations
    if ($donation->is_anonymous) {
        return response()->json([
            'success' => false,
            'message' => 'Cannot send receipt to anonymous donor.'
        ], 403);
    }

    if ($donation->status !== 'successful') {
        return response()->json([
            'success' => false,
            'message' => 'Only successful donations can have receipts resent.'
        ], 400);
    }

    try {
        // This will now actually send the email
        Mail::to($donation->donor_email)->queue(new DonationReceiptMail($donation));

        // Optional: Log the action
        Log::info('Receipt manually resent by admin', [
            'donation_id' => $donation->id,
            'donor_email' => $donation->donor_email,
            'admin_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Receipt has been queued and will be sent shortly!'
        ]);

    } catch (\Exception $e) {
        Log::error('Failed to resend donation receipt', [
            'donation_id' => $donation->id,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Failed to send receipt. Please try again later.'
        ], 500);
    }
}

    /** Mark tax cert sent */
    public function markTaxCertificateSent($id)
    {
        Donation::where('id', $id)->update([
            'tax_certificate_sent' => true,
            'tax_certificate_sent_at' => now()
        ]);

        return back()->with('success', 'Tax certificate marked as sent.');
    }

    /** Refund */
    public function refund(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        if ($donation->status !== 'successful') {
            return back()->with('error', 'Only successful donations can be refunded.');
        }

        $request->validate(['refund_reason' => 'required|string|max:500']);

        $donation->update([
            'status' => 'refunded',
            'refund_reason' => $request->refund_reason,
            'refunded_at' => now(),
        ]);

        // Decrement campaign total
        $donation->campaign->decrement('raised_amount', $donation->amount_in_base_currency ?? $donation->amount);

        return back()->with('success', 'Donation refunded.');
    }

    /** Send thank you email */
    public function sendThankYou($id)
    {
        $donation = Donation::with('campaign')->findOrFail($id);

        if ($donation->is_anonymous) {
            return back()->with('error', 'Cannot email anonymous donor.');
        }

        try {
            Mail::to($donation->donor_email)->queue(new ThankYouMail($donation));
            return back()->with('success', 'Thank you email queued!');
        } catch (\Exception $e) {
            Log::error('ThankYouMail failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send email.');
        }
    }
}