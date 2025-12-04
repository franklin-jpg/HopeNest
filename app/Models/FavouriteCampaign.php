<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavoriteCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'notify_when_close',
        'notified',
    ];

    protected $casts = [
        'notify_when_close' => 'boolean',
        'notified' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Check if campaign is close to goal (90% or more)
     */
    public function isCampaignCloseToGoal(): bool
    {
        if (!$this->campaign) {
            return false;
        }

        return $this->campaign->progress_percentage >= 90;
    }

    /**
     * Send notification if campaign is close to goal
     */
    public function checkAndNotify(): void
    {
        if ($this->notify_when_close && !$this->notified && $this->isCampaignCloseToGoal()) {
            // Send notification (you can implement email/SMS here)
            // For now, we'll just mark as notified
            $this->update(['notified' => true]);
        }
    }
}