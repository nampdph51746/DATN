<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserBookingBan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'failed_attempts',
        'banned_at',
        'banned_until',
        'is_active',
        'reason',
    ];

    protected $casts = [
        'banned_at' => 'datetime',
        'banned_until' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Kiểm tra ban có còn hiệu lực không
     */
    public function isActive(): bool
    {
        return $this->is_active && now() < $this->banned_until;
    }

    /**
     * Hết hạn ban
     */
    public function expire(): void
    {
        $this->update(['is_active' => false]);
    }

    /**
     * Scope để lấy các ban đang hiệu lực
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('banned_until', '>', now());
    }

    /**
     * Scope để lấy các ban đã hết hạn
     */
    public function scopeExpired($query)
    {
        return $query->where('banned_until', '<=', now());
    }
}
