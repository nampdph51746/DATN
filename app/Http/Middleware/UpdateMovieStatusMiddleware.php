<?php

namespace App\Http\Middleware;

use App\Models\Movie;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Cache;

class UpdateMovieStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Chỉ cập nhật mỗi 5 phút một lần để tránh làm chậm ứng dụng
        $cacheKey = 'movie_status_updated_at';
        $lastUpdated = Cache::get($cacheKey);
        
        if (!$lastUpdated || now()->diffInMinutes($lastUpdated) >= 5) {
            Movie::updateAllMovieStatuses();
            Cache::put($cacheKey, now(), 300); // Cache 5 phút
        }

        return $next($request);
    }
}
