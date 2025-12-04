<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerHour extends Model
{
    // Table name
    protected $table = 'volunteer_hours';

    // Fillable fields for mass assignment
    protected $fillable = [
        'volunteer_id',
        'campaign_id',
        'date',
        'hours',
        'description',
        'status',
        'approved_by',
    ];

    // Casts for proper data types
    protected $casts = [
        'date' => 'date',
        'hours' => 'decimal:2',
    ];

    // Scopes for easy querying
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

    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
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

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    // Get status badge class for frontend
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'approved' => 'bg-green-500 text-white',
            'pending' => 'bg-yellow-500 text-yellow-900',
            'rejected' => 'bg-red-500 text-white',
            default => 'bg-gray-400 text-white'
        };
    }

    // Get status display name
    public function getStatusDisplayName(): string
    {
        return match($this->status) {
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => ucfirst($this->status)
        };
    }

    // Get formatted hours (e.g., "3.50 hours" or "3h 30m")
    public function getFormattedHoursAttribute(): string
    {
        $hours = (float) $this->hours;
        $wholeHours = floor($hours);
        $minutes = round(($hours - $wholeHours) * 60);
        
        if ($minutes === 0) {
            return $wholeHours . ' hour' . ($wholeHours !== 1 ? 's' : '');
        }
        
        return $wholeHours . 'h ' . $minutes . 'm';
    }

    // Scope for hours within date range
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}