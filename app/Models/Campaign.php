<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'slug', 'short_description', 'full_description',
        'campaign_category_id', 'location', 'goal_amount', 'raised_amount',
        'start_date', 'end_date', 'minimum_donation', 'featured_image',
        'gallery_images', 'video_url', 'is_featured', 'is_urgent',
        'recurring_donations', 'custom_thank_you', 'status'
    ];

    protected function casts(): array
    {
        return [
            'start_date'         => 'datetime',
            'end_date'           => 'datetime',
            'goal_amount'        => 'decimal:2',
            'raised_amount'      => 'decimal:2',
            'minimum_donation'   => 'decimal:2',
            'is_featured'        => 'boolean',
            'is_urgent'          => 'boolean',
            'recurring_donations'=> 'boolean',
            'gallery_images'     => 'array',
        ];
    }

    // Auto slug
    public static function boot()
    {
        parent::boot();
        static::creating(function ($campaign) {
            $campaign->slug = Str::slug($campaign->title);
        });
    }

    // Formatted date methods
    public function getFormatedDate() 
    {
        return $this->start_date?->format('d, M Y') ?? '—';
    }

    public function getFormatedTime() 
    {
        return $this->start_date?->format('g:i A') ?? '';
    }

    public function getFormatedDateTime() 
    {
        return $this->end_date?->format('F d, Y \a\t g:i A') ?? 'No deadline';
    }

    // Progress percentage
    public function getProgressPercentageAttribute()
    {
        if ($this->goal_amount <= 0) return 0;
        return min(100, round(($this->raised_amount / $this->goal_amount) * 100));
    }

    // Relationships
    public function campaignCategory()
    {
        return $this->belongsTo(CampaignCategory::class, 'campaign_category_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function updates(): HasMany
    {
        return $this->hasMany(CampaignUpdate::class);
    }

     public function report(): HasMany
    {
        return $this->hasMany(Reports::class);
    }

public function volunteers()
{
    return $this->belongsToMany(Volunteer::class, 'volunteerCampaign')  
        ->withPivot('status', 'assigned_at', 'assigned_by')
        ->withTimestamps();
}

   public function volunteerHours()
{
    return $this->hasMany(VolunteerHour::class);
}


public function impactStories()
{
    return $this->hasMany(ImpactStory::class);
}
public function getTotalVolunteerHoursAttribute()
{
    return $this->volunteerHours()->where('status', 'approved')->sum('hours');
}




/**
 * Get campaign views
 */
public function views()
{
    return $this->hasMany(CampaignView::class);
}

/**
 * Get raised amount (cached attribute)
 */
public function getRaisedAmountAttribute()
{
    // If you have a 'raised_amount' column, return it
    if (isset($this->attributes['raised_amount'])) {
        return $this->attributes['raised_amount'];
    }
    
    // Otherwise calculate it
    return $this->successfulDonations()->sum('amount');
}

/**
 * Calculate and update raised amount
 */
public function updateRaisedAmount()
{
    $this->raised_amount = $this->successfulDonations()->sum('amount');
    $this->save();
    
    return $this->raised_amount;
}

/**
 * Get total views count
 */
public function getTotalViewsAttribute()
{
    return $this->views()->count();
}

/**
 * Get unique views count
 */
public function getUniqueViewsAttribute()
{
    return $this->views()->distinct('ip_address')->count('ip_address');
}

/**
 * Get conversion rate
 */
public function getConversionRateAttribute()
{
    $uniqueViews = $this->unique_views;
    
    if ($uniqueViews <= 0) {
        return 0;
    }
    
    return round(($this->donorsCount() / $uniqueViews) * 100, 2);
}
    // Donation helpers
    public function successfulDonations()
    {
        return $this->donations()->where('status', 'successful');
    }

    public function donorsCount()
    {
        return $this->successfulDonations()->distinct('donor_email')->count();
    }

    // Status helpers
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isExpired()
    {
        return $this->end_date && $this->end_date->isPast();
    }

    public function daysRemaining()
    {
        if (!$this->end_date) return null;
        
        $now = now();
        if ($this->end_date->isPast()) return 0;
        
        return $now->diffInDays($this->end_date);
    }

    // Gallery helpers
    public function getGalleryImagesAttribute($value)
    {
        if (is_string($value)) {
            return $value ? explode(',', $value) : [];
        }
        return $value ?? [];
    }

    public function setGalleryImagesAttribute($value)
    {
        if (is_array($value)) {
            $clean = array_filter(array_map('trim', $value));
            $this->attributes['gallery_images'] = !empty($clean) ? implode(',', $clean) : null;
        } else {
            $this->attributes['gallery_images'] = $value;
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }
}