<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name', 'logo', 'favicon', 'email', 'phone',
        'address', 'business_hours', 'facebook', 'twitter',
        'instagram', 'linkedin', 'youtube'
    ];

    // Helper to get the only row
    public static function getSettings()
    {
        return static::first() ?? new static();
    }
}