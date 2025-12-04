<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CampaignView;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class TrackCampaignViews
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track GET requests to campaign pages
        if ($request->isMethod('GET') && $request->route('campaign')) {
            $this->trackView($request);
        }

        return $response;
    }

    /**
     * Track the campaign view
     */
    protected function trackView(Request $request)
    {
        try {
            $campaignId = $request->route('campaign');
            
            // Don't track if it's an admin viewing
            if ($request->user() && $request->user()->hasRole('admin')) {
                return;
            }

            // Check if this user/IP has viewed this campaign recently (within last hour)
            $recentView = CampaignView::where('campaign_id', $campaignId)
                ->where(function($query) use ($request) {
                    $query->where('ip_address', $request->ip())
                          ->orWhere('session_id', session()->getId());
                })
                ->where('created_at', '>=', now()->subHour())
                ->exists();

            // Don't create duplicate view within the hour
            if ($recentView) {
                return;
            }

            // Create the view record
            CampaignView::create([
                'campaign_id' => $campaignId,
                'user_id' => auth()->id(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer') ?? 'direct',
                'device_type' => CampaignView::detectDeviceType($request->userAgent()),
                'browser' => CampaignView::detectBrowser($request->userAgent()),
                'platform' => CampaignView::detectPlatform($request->userAgent()),
                'session_id' => session()->getId(),
                // You can add geolocation data here if you have a service for it
                'country' => null,
                'city' => null,
            ]);
        } catch (\Exception $e) {
            // Silently fail - don't break the user experience if tracking fails
            Log::error('Campaign view tracking failed: ' . $e->getMessage());
        }
    }
}