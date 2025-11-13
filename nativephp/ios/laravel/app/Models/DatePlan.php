<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class DatePlan extends Model
{
    protected $fillable = [
        'couple_id',
        'title',
        'description',
        'planned_date',
        'location',
        'category',
        'estimated_cost',
        'status',
        'notes',
        'photos',
        'reminder_sent'
    ];

    protected $casts = [
        'planned_date' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'photos' => 'array'
    ];

    public function couple(): BelongsTo
    {
        return $this->belongsTo(Couple::class);
    }

    // Check if date is upcoming
    public function getIsUpcomingAttribute()
    {
        return $this->planned_date > Carbon::now() && $this->status !== 'cancelled';
    }

    // Get time until date
    public function getTimeUntilDateAttribute()
    {
        if ($this->planned_date <= Carbon::now()) {
            return null;
        }

        return Carbon::now()->diffForHumans($this->planned_date, true);
    }

    // Get category badge color
    public function getCategoryColorAttribute()
    {
        return match($this->category) {
            'romantic' => 'bg-pink-500',
            'adventure' => 'bg-green-500',
            'casual' => 'bg-blue-500',
            'special' => 'bg-purple-500',
            default => 'bg-gray-500'
        };
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'planned' => 'bg-yellow-500',
            'confirmed' => 'bg-blue-500',
            'completed' => 'bg-green-500',
            'cancelled' => 'bg-red-500',
            default => 'bg-gray-500'
        };
    }
}
