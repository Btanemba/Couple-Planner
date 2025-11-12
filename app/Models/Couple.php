<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Couple extends Model
{
    protected $fillable = [
        'partner_one_id',
        'partner_two_id',
        'partner_one_name',
        'partner_two_name',
        'relationship_start_date',
        'relationship_description',
        'anniversary_reminder',
        'milestones',
        'profile_picture',
        'invitation_code',
        'status'
    ];

    protected $casts = [
        'relationship_start_date' => 'date',
        'milestones' => 'array'
    ];

    public function datePlans(): HasMany
    {
        return $this->hasMany(DatePlan::class);
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class);
    }

    public function partnerOne()
    {
        return $this->belongsTo(User::class, 'partner_one_id');
    }

    public function partnerTwo()
    {
        return $this->belongsTo(User::class, 'partner_two_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Generate unique invitation code
    public static function generateInvitationCode()
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (self::where('invitation_code', $code)->exists());

        return $code;
    }

    // Check if couple is complete (both partners joined)
    public function isComplete()
    {
        return $this->partner_one_id && $this->partner_two_id && $this->status === 'active';
    }

    // Calculate relationship duration
    public function getRelationshipDurationAttribute()
    {
        $start = Carbon::parse($this->relationship_start_date);
        $now = Carbon::now();

        return [
            'years' => (int) $start->diffInYears($now),
            'months' => (int) $start->diffInMonths($now),
            'days' => (int) $start->diffInDays($now),
            'total_days' => $start->diffInDays($now),
            'readable' => $start->diffForHumans($now, true)
        ];
    }

    // Get next anniversary
    public function getNextAnniversaryAttribute()
    {
        $start = Carbon::parse($this->relationship_start_date);
        $thisYear = $start->copy()->year(Carbon::now()->year);

        if ($thisYear->isPast()) {
            $thisYear->addYear();
        }

        return $thisYear;
    }

    // Get upcoming dates
    public function getUpcomingDatesAttribute()
    {
        return $this->datePlans()
            ->where('planned_date', '>=', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('planned_date')
            ->limit(5)
            ->get();
    }

    // Get upcoming trips
    public function getUpcomingTripsAttribute()
    {
        return $this->trips()
            ->where('start_date', '>=', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_date')
            ->limit(3)
            ->get();
    }
}
