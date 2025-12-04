<?php
// app/Models/CampaignUpdate.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignUpdate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'title',
        'content',
        'images',
        'published_at',
    ];

    protected $casts = [
        'images' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Get the campaign that owns the update
     */
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get formatted published date
     */
    public function getFormattedDateAttribute()
    {
        return $this->published_at?->format('M d, Y \a\t g:i A') ?? '—';
    }
}