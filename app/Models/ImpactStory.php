<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ImpactStory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'campaign_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'featured_image',
        'beneficiary_name',
        'beneficiary_location',
        'impact_date',
        'metrics',
        'status',
        'is_featured',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'metrics' => 'array',
        'is_featured' => 'boolean',
        'impact_date' => 'date',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($story) {
            if (empty($story->slug)) {
                $story->slug = Str::slug($story->title);
            }
        });

        static::updating(function ($story) {
            if ($story->isDirty('title')) {
                $story->slug = Str::slug($story->title);
            }
        });
    }

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Accessors & Mutators
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at <= now();
    }

    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200);
        return $minutes;
    }

    // Helper Methods
    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function unpublish()
    {
        $this->update([
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }
}