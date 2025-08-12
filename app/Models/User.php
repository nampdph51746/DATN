<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Log;


class User extends Authenticatable
{
    use HasFactory, HasRoles;

    /**
     * Cập nhật hạng của user dựa trên tổng số tiền đã tiêu (booking thành công)
     */
    public function updateRankByTotalSpent()
    {
        $totalSpent = $this->bookings()
            ->where('status', 'confirmed')
            ->sum('final_amount');

        $ranks = \App\Models\CustomerRank::orderBy('min_points_required')->get();
        $newRank = null;

        foreach ($ranks as $rank) {
            if ($totalSpent >= $rank->min_points_required) {
                $newRank = $rank;
            } else {
                break;
            }
        }

        // Debug: Log thông tin
        Log::info("User {$this->id} - Total spent: $totalSpent, Current rank: {$this->customer_rank_id}, New rank: " . ($newRank ? $newRank->id : 'null'));

        $this->refresh(); // Đảm bảo lấy dữ liệu mới nhất từ DB
        if ($newRank && (int)$this->customer_rank_id !== (int)$newRank->id) {
            $this->customer_rank_id = $newRank->id;
            $result = $this->save();
            Log::info("User {$this->id} - Save result: " . ($result ? 'success' : 'fail') . ", Updated rank: {$this->customer_rank_id}");
        }
    }

    protected $fillable = [
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
        'google_id',
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