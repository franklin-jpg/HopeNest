<?php

namespace App\Http\Controllers\Auth\Paystack;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Services\CurrencyService;
use Illuminate\Http\Request;

class FormDonationController extends Controller
{
    protected $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Show donation form for a specific campaign
     * 
     * @param Campaign $campaign - Route model binding will auto-load by slug
     */
    public function showForm(Campaign $campaign)
    {
        // Load the campaign with its category relationship
        $campaign->load('campaignCategory');
        
        // Get supported currencies
        $currencies = $this->currencyService->getSupportedCurrencies();

        // Check if campaign is active
        if ($campaign->status !== 'active') {
            return redirect()->route('welcome')
                ->with('error', 'This campaign is no longer accepting donations.');
        }

        // Check if campaign has ended
        if ($campaign->end_date && $campaign->end_date->isPast()) {
            return redirect()->route('welcome')
                ->with('error', 'This campaign has ended.');
        }

        // Return the donation form view
        return view('home.donation-form', compact('campaign', 'currencies'));
    }
}