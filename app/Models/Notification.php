<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'entity_type',
        'entity_id',
        'title',
        'message',
        'type',
        'priority',
        'old_status',
        'new_status',
        'is_global',
        'link_url',
        'event_details',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'type' => \App\Enums\NotificationType::class,
        'priority' => 'string',
        'is_global' => 'boolean',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ đa hình đến entity liên quan (Booking, Movie, ...)
     */
    public function entity()
    {
        return $this->morphTo(null, 'entity_type', 'entity_id');
    }
}