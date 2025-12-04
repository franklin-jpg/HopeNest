<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerCampaign extends Model
{
use HasFactory;

   protected $table = 'volunteerCampaign';

    // Fillable fields for mass assignment
    protected $fillable = [
        'volunteer_id',
        'campaign_id',
        'status',
        'assigned_at',
        'assigned_by',
    ];

    // Casts for proper data types
    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    // Scopes for easy querying
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeAssigned($query)
    {
        return $query->where('status', 'assigned');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Relationships
    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class);
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    // Helper methods
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isAssigned(): bool
    {
        return $this->status === 'assigned';
    }

    // Get status badge class for frontend
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'active' => 'bg-green-500 text-white',
            'assigned' => 'bg-blue-500 text-white',
            'completed' => 'bg-gray-500 text-white',
            'removed' => 'bg-red-500 text-white',
            default => 'bg-gray-400 text-white'
        };
    }

    // Get status display name
    public function getStatusDisplayName(): string
    {
        return match($this->status) {
            'active' => 'Active',
            'assigned' => 'Assigned',
            'completed' => 'Completed',
            'removed' => 'Removed',
            default => ucfirst($this->status)
        };
    }
}