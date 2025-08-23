<?php

namespace App\Models;

use Carbon\Carbon;
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
    ];

     protected $appends = ['status'];

         /**
     * Accessor status động theo ngày thực tế
     */
     public function getStatusAttribute($value)
    {
        $today = Carbon::today();

        if ($this->release_date && $this->end_date) {
            if ($today->lt(Carbon::parse($this->release_date))) {
                return 'upcoming';
            } elseif ($today->between(
                Carbon::parse($this->release_date),
                Carbon::parse($this->end_date)
            )) {
                return 'showing';
            } else {
                return 'ended';
            }
        }

        // fallback: nếu DB đã có sẵn status thì dùng
        return $value ?? 'upcoming';
    }


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


    public function reviews()
    {
        return $this->hasMany(Review::class);
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
     * Tự động cập nhật trạng thái phim dựa trên ngày kết thúc
     */
    public function updateStatusBasedOnEndDate()
    {
        if ($this->end_date && $this->end_date < now()) {
            if ($this->status !== \App\Enums\MovieStatus::Ended) {
                $this->update(['status' => \App\Enums\MovieStatus::Ended]);
            }
        }
    }

    /**
     * Lấy trạng thái thực tế của phim (bao gồm kiểm tra ngày kết thúc)
     */
    public function getRealStatusAttribute()
    {
        if ($this->end_date && $this->end_date < now()) {
            return \App\Enums\MovieStatus::Ended;
        }
        return $this->status;
    }

    /**
     * Scope để tự động cập nhật trạng thái các phim đã kết thúc
     */
    public function scopeUpdateExpiredMovies($query)
    {
        return $query->where('end_date', '<', now())
                    ->where('status', '!=', \App\Enums\MovieStatus::Ended)
                    ->update(['status' => \App\Enums\MovieStatus::Ended]);
    }

    public function directors()
{
    return $this->belongsToMany(Director::class, 'movie_directors', 'movie_id', 'director_id');
}
}