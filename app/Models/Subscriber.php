<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscriber extends Model
{
    protected $guarded = [];

    protected $dates = ['subscribed_at', 'unsubscribed_at'];

    public function scopeActive($query)
    {
        return $query->whereNull('unsubscribed_at');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
