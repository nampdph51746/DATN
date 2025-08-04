<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\ShowtimeStatusService;
use Illuminate\Support\Facades\Log;

class UpdateShowtimeStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Chỉ chạy cho các route admin và cứ 5 phút một lần
        if ($request->route() && str_contains($request->route()->getName() ?? '', 'admin.showtimes')) {
            $lastUpdate = cache('last_showtime_status_update', 0);
            $now = time();
            
            // Cập nhật mỗi 5 phút (300 giây)
            if ($now - $lastUpdate > 300) {
                try {
                    $service = new ShowtimeStatusService();
                    $updatedCount = $service->updateShowtimeStatuses();
                    
                    if ($updatedCount > 0) {
                        Log::info("Middleware đã cập nhật trạng thái cho {$updatedCount} suất chiếu");
                    }
                    
                    cache(['last_showtime_status_update' => $now], 600); // Cache 10 phút
                } catch (\Exception $e) {
                    Log::error('Lỗi middleware cập nhật trạng thái suất chiếu: ' . $e->getMessage());
                }
            }
        }

        return $next($request);
    }
}
