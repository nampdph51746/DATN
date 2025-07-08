<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'avatar_url',
        'date_of_birth',
        'status',
        'email_verified_at',
        'last_login_at',
        'customer_rank_id',
    ];

    protected $casts = [
        'status' => \App\Enums\UserStatus::class,
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function customerRank()
    {
        return $this->belongsTo(CustomerRank::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function points()
    {
        return $this->hasOne(Point::class, 'user_id', 'id');
    }

    public function pointHistory()
    {
        return $this->hasMany(PointHistory::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
