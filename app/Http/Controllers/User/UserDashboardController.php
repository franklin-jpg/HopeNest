<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function index()
{
    $user = Auth::user();

    $successfulDonations = $user->donation()->where('status', 'successful');

    $stats = [
        'total_donated'       => number_format($successfulDonations->sum('amount_in_base_currency') ?? 0, 2, '.', ','), // or without formatting if you want raw
        'causes_supported'    => $successfulDonations->distinct('campaign_id')->count('campaign_id'),
        'donation_streak'     => $this->calculateDonationStreak($user), // You'll need this method
        'lives_impacted'      => $this->estimateLivesImpacted($user),       // Custom logic or static estimate
        'recent_donations'    => $user->donation()
                                        ->with(['campaign'])
                                        ->latest('paid_at')
                                        ->take(5)
                                        ->get(),
    ];

    // Optional: other data
    $favoriteCampaigns = $user->favoriteCampaigns()
        ->with(['CampaignCategory'])
        ->withPivot('notify_when_close', 'notified')
        ->get();

    $featuredCampaigns = Campaign::active()
        ->featured()
        ->with(['CampaignCategory'])
        ->take(4)
        ->get();

    return view('user.dashboard', compact(
        'stats',
        'favoriteCampaigns',
        'featuredCampaigns'
    ));
}

    /**
     * Toggle favorite campaign
     */
    public function toggleFavorite(Request $request, Campaign $campaign)
    {
        $user = Auth::user();
        $notifyWhenClose = $request->boolean('notify_when_close', false);

        $isFavorited = $user->toggleFavorite($campaign->id, $notifyWhenClose);

        return response()->json([
            'success' => true,
            'favorited' => $isFavorited,
            'message' => $isFavorited 
                ? 'Campaign added to favorites!' 
                : 'Campaign removed from favorites!',
        ]);
    }

    /**
     * Update notification preference for favorite campaign
     */
    public function updateNotificationPreference(Request $request, Campaign $campaign)
    {
        $user = Auth::user();

        $favorite = $user->favorites()
            ->where('campaign_id', $campaign->id)
            ->first();

        if (!$favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not in favorites',
            ], 404);
        }

        $favorite->update([
            'notify_when_close' => $request->boolean('notify_when_close'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification preference updated!',
        ]);
    }

    private function calculateDonationStreak($user)
{
    $donations = $user->donation()
        ->where('status', 'successful')
        ->whereNotNull('paid_at')
        ->orderBy('paid_at', 'desc')
        ->get()
        ->groupBy(function ($donation) {
            return $donation->paid_at->format('Y-m');
        });

    if ($donations->isEmpty()) return 0;

    $months = $donations->keys();
    $currentMonth = now()->format('Y-m');
    $streak = 0;

    for ($i = 0; $i < 12; $i++) { // check last 12 months
        $checkMonth = now()->subMonths($i)->format('Y-m');
        if ($months->contains($checkMonth)) {
            $streak++;
        } else if ($i > 0) {
            break; // streak broken
        }
    }

    return $streak;
}

private function estimateLivesImpacted($user)
{
    // This is usually a symbolic/estimated number
    // You can base it on total donated, or use a fixed multiplier
    $totalDonated = $user->donation()->where('status', 'successful')->sum('amount_in_base_currency');
    return intval($totalDonated / 5000); // e.g., every ₦5,000 helps 1 person (customize!)
}
}