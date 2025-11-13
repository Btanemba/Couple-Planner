<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Trip extends Model
{
    protected $fillable = [
        'couple_id',
        'title',
        'description',
        'destination',
        'start_date',
        'end_date',
        'budget',
        'spent_amount',
        'status',
        'itinerary',
        'packing_list',
        'photos',
        'notes',
        'accommodation',
        'transportation'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'budget' => 'decimal:2',
        'spent_amount' => 'decimal:2',
        'itinerary' => 'array',
        'packing_list' => 'array',
        'photos' => 'array'
    ];

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    // Get trip duration in days
    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }

    // Check if trip is upcoming
    public function getIsUpcomingAttribute()
    {
        return $this->start_date > Carbon::now() && $this->status !== 'cancelled';
    }

    // Get remaining budget
    public function getRemainingBudgetAttribute()
    {
        if (!$this->budget) return null;
        return $this->budget - $this->spent_amount;
    }

    // Get budget utilization percentage
    public function getBudgetUtilizationAttribute()
    {
        if (!$this->budget) return 0;
        return ($this->spent_amount / $this->budget) * 100;
    }

    // Get time until trip
    public function getTimeUntilTripAttribute()
    {
        if ($this->start_date <= Carbon::now()) {
            return null;
        }

        return Carbon::now()->diffForHumans($this->start_date, true);
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'planning' => 'bg-yellow-500',
            'booked' => 'bg-blue-500',
            'in_progress' => 'bg-green-500',
            'completed' => 'bg-green-600',
            'cancelled' => 'bg-red-500',
            default => 'bg-gray-500'
        };
    }

    // Check if trip is currently active
    public function getIsActiveAttribute()
    {
        $now = Carbon::now()->toDateString();
        return $now >= $this->start_date->toDateString() &&
               $now <= $this->end_date->toDateString() &&
               $this->status === 'in_progress';
    }
}
