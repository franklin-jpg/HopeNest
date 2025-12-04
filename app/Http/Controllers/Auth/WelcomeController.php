<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
   public function index()
{
    $urgentCampaigns = Campaign::with('campaignCategory')
        ->where('status', 'active')
        ->where('is_urgent', true)
        ->latest('start_date')
        ->take(3) // Show only 3 urgent ones
        ->get();

    // Or if you want featured + urgent mixed:
    // ->where(function($q) {
    //     $q->where('is_urgent', true)->orWhere('is_featured', true);
    // })

    return view('home.welcome', compact('urgentCampaigns'));
}

public function all_campaigns()
{
    $campaigns = Campaign::where('status', 'active')
        ->latest()
        ->paginate(9);

    return view('home.all', compact('campaigns'));
}

public function show($slug)
{
    $campaign = Campaign::where('slug', $slug)
        ->where('status', 'active')
        ->firstOrFail();

    return view('home.show', compact('campaign'));
}


}
