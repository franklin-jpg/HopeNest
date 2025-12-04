<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Volunteer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'phone',
        'address',
        'skills',
        'availability',
        'motivation',
        'status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];



    // **ADD THESE SCOPES** (for the badge in sidebar)
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Helper method
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function hours()
    {
        return $this->hasMany(VolunteerHour::class);
    }

 public function campaigns()
{
    return $this->belongsToMany(Campaign::class, 'volunteerCampaign')  
        ->withPivot('status', 'assigned_at', 'assigned_by')
        ->withTimestamps();
    
}



    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}