<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'status', 
        'published_at', 'featured_image', 'seo_meta',
        'views_count', 'is_featured'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'seo_meta' => 'array',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_categories');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tags');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->orWhere(function ($q) {
                        $q->where('status', 'scheduled')
                          ->where('published_at', '<=', now());
                    })
                    ->where('published_at', '<=', now());
    }

    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled')
                    ->where('published_at', '>', now());
    }

    public function isPublished(): bool
    {
        return $this->status === 'published' || 
               ($this->status === 'scheduled' && $this->published_at <= now());
    }

    public function getSeoMetaAttribute($value)
    {
        return $value ? json_decode($value, true) : [
            'title' => $this->title,
            'description' => $this->excerpt,
            'keywords' => ''
        ];
    }
}