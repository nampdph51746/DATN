<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PointHistory;
use App\Models\User;

class Point extends Model
{
    use HasFactory;

    protected $table = 'points';
    protected $fillable = ['user_id', 'total_points', 'points_expiry_date'];

    protected $casts = [
        'points_expiry_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function histories()
    {
        return $this->hasMany(PointHistory::class);
    }
}