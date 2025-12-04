<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignView extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'ip_address',
        'user_agent',
        'referrer',
        'device_type',
        'browser',
        'platform',
        'country',
        'city',
        'session_id',
    ];

    /**
     * Get the campaign that was viewed
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the user who viewed (if authenticated)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get device type from user agent
     */
    public static function detectDeviceType($userAgent)
    {
        if (preg_match('/mobile/i', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/tablet/i', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Get browser from user agent
     */
    public static function detectBrowser($userAgent)
    {
        if (preg_match('/Firefox/i', $userAgent)) {
            return 'Firefox';
        } elseif (preg_match('/Chrome/i', $userAgent)) {
            return 'Chrome';
        } elseif (preg_match('/Safari/i', $userAgent)) {
            return 'Safari';
        } elseif (preg_match('/Edge/i', $userAgent)) {
            return 'Edge';
        }
        return 'Other';
    }

    /**
     * Get platform from user agent
     */
    public static function detectPlatform($userAgent)
    {
        if (preg_match('/Windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            return 'MacOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            return 'Linux';
        } elseif (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/iOS|iPhone|iPad/i', $userAgent)) {
            return 'iOS';
        }
        return 'Other';
    }
}