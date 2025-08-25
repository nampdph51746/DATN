<?php

namespace App\Models;

use App\Enums\MovieStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'director_id', 'duration_minutes', 'release_date',
        'end_date', 'description', 'poster_url', 'trailer_url', 'language',
        'country_id', 'age_limit_id', 'status', 'average_rating', 'image_path', 
    ];

    protected $casts = [
        'release_date' => 'date',
        'end_date' => 'date',
        'average_rating' => 'decimal:1',
        'status' => MovieStatus::class
    ];


         /**
     * Accessor status động theo ngày thực tế
     */


    public function country()
    {
        return $this->belongsTo(Country::class);
    }


    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'movie_actors')
            ->withPivot('character_name', 'is_main_character')
            ->withTimestamps();
    }

    public function ageLimit()
    {
        return $this->belongsTo(\App\Models\AgeLimit::class, 'age_limit_id');
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'movie_genres');
    }


    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class);
    }

    /**
     * Kiểm tra xem phim có vé đã được đặt không
     */
    public function hasBookedTickets()
    {
        return $this->showtimes()
            ->whereHas('tickets', function($query) {
                $query->whereNotNull('booking_id');
            })
            ->exists();
    }

    /**
     * Kiểm tra xem phim có thể chỉnh sửa không
     */
    public function canBeEdited()
    {
        // Không thể chỉnh sửa nếu phim đã kết thúc
        $movieStatus = is_object($this->status) ? $this->status->value : $this->status;
        if ($movieStatus === 'ended') {
            return false;
        }
        
        // Không thể chỉnh sửa nếu phim đang chiếu
        if ($movieStatus === 'showing') {
            return false;
        }
        
        // Không thể chỉnh sửa nếu đã có vé được đặt
        if ($this->hasBookedTickets()) {
            return false;
        }
        
        return true;
    }

    /**
     * Tự động cập nhật trạng thái phim dựa trên thời gian hệ thống
     */
    public static function updateAllMovieStatuses()
    {
        $today = now()->toDateString();
        
        // Cập nhật phim sắp chiếu -> đang chiếu
        static::where('status', 'upcoming')
            ->where('release_date', '<=', $today)
            ->update(['status' => 'showing']);
            
        // Cập nhật phim đang chiếu -> đã kết thúc (nếu có ngày kết thúc)
        static::where('status', 'showing')
            ->whereNotNull('end_date')
            ->where('end_date', '<', $today)
            ->update(['status' => 'ended']);
    }

    /**
     * Cập nhật trạng thái của phim hiện tại dựa trên thời gian hệ thống
     */
    public function updateStatusBasedOnTime()
    {
        $today = now()->toDateString();
        $currentStatus = $this->status;
        
        if ($this->end_date && $this->end_date < $today) {
            $newStatus = 'ended';
        } elseif ($this->release_date > $today) {
            $newStatus = 'upcoming';
        } elseif ($this->release_date <= $today && (!$this->end_date || $this->end_date >= $today)) {
            $newStatus = 'showing';
        } else {
            $newStatus = 'showing';
        }
        
        if ($currentStatus !== $newStatus) {
            $this->update(['status' => $newStatus]);
        }
    }

    /**
     * Lấy trạng thái thực tế của phim dựa trên thời gian hệ thống
     */
    public function getRealTimeStatusAttribute()
    {
        $today = now()->toDateString();
        
        if ($this->end_date && $this->end_date < $today) {
            return 'ended';
        } elseif ($this->release_date > $today) {
            return 'upcoming';
        } elseif ($this->release_date <= $today && (!$this->end_date || $this->end_date >= $today)) {
            return 'showing';
        } else {
            return 'showing';
        }
    }

    /**
     * Scope để tự động cập nhật trạng thái các phim
     */
    public function scopeUpdateExpiredMovies($query)
    {
        $today = now()->toDateString();
        
        // Cập nhật phim sắp chiếu -> đang chiếu
        static::where('status', 'upcoming')
            ->where('release_date', '<=', $today)
            ->update(['status' => 'showing']);
            
        // Cập nhật phim đang chiếu -> đã kết thúc
        return $query->whereNotNull('end_date')
                    ->where('end_date', '<', $today)
                    ->where('status', '!=', 'ended')
                    ->update(['status' => 'ended']);
    }

    public function directors()
{
    return $this->belongsToMany(Director::class, 'movie_directors', 'movie_id', 'director_id');
}
}