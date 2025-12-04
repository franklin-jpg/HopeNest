<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class UserDonationController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->donation()->with('campaign')->latest();

        // Filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('campaign')) {
            $query->where('campaign_id', $request->campaign);
        }

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('paid_at', [$request->from, $request->to . ' 23:59:59']);
        }

        $donations = $query->paginate(15);

        $campaigns = Auth::user()->donation()
            ->with('campaign')
            ->get()
            ->pluck('campaign.title', 'campaign_id')
            ->unique()
            ->sort();

        return view('user.donations.index', compact('donations', 'campaigns'));
    }


    public function show($id)
{
    $donation = Auth::user()->donation()
        ->with('campaign.campaignCategory')
        ->withTrashed() // In case campaign was soft-deleted
        ->findOrFail($id);

    return view('user.donations.show', compact('donation'));
}
    public function receipt($id)
    {
        $donation = Auth::user()->donation()->with('campaign')->findOrFail($id);
        return view('user.donations.receipt', compact('donation'));
    }

    public function downloadPdf($id)
    {
        $donation = Auth::user()->donation()->with('campaign')->findOrFail($id);

        $pdf = Pdf::loadView('user.donations.pdf', compact('donation'));
        return $pdf->download('Receipt-' . $donation->receipt_number . '.pdf');
    }
}