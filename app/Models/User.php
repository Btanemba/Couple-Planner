<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'couple_id',
        'partner_role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the couple that the user belongs to.
     */
    public function couple()
    {
        return $this->belongsTo(Couple::class);
    }

    /**
     * Check if user is in a couple relationship
     */
    public function isInCouple()
    {
        return $this->couple_id !== null;
    }

    /**
     * Get partner user
     */
    public function getPartner()
    {
        if (!$this->isInCouple()) {
            return null;
        }

        return User::where('couple_id', $this->couple_id)
            ->where('id', '!=', $this->id)
            ->first();
    }
}
