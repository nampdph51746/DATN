<?php

namespace App\Jobs;

use App\Models\Booking;
use App\Models\Ticket;
use App\Enums\BookingStatus;
use App\Enums\TicketStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoCancelExpiredTickets implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     * Tự động hủy vé nếu không được sử dụng sau khi suất chiếu kết thúc
     */
    public function handle(): void
    {
        // Chỉ hủy vé thôi - không hủy booking
        $this->cancelExpiredTickets();
    }

    /**
     * Hủy các vé đã hết hạn (ngay khi suất chiếu kết thúc)
     */
    private function cancelExpiredTickets(): void
    {
        $now = Carbon::now();
        
        Log::info("Starting auto-cancel expired tickets job", [
            'current_time' => $now->toDateTimeString()
        ]);

        // Tìm các vé chưa được sử dụng và suất chiếu đã kết thúc
        $expiredTickets = Ticket::whereIn('status', [TicketStatus::Valid, TicketStatus::Checked])
            ->whereHas('showtime', function($query) use ($now) {
                $query->where('start_time', '<', $now);
            })
            ->with('showtime.movie', 'booking', 'seat')
            ->get();

        Log::info("Found expired tickets", [
            'count' => $expiredTickets->count(),
            'current_time' => $now->toDateTimeString()
        ]);

        $cancelledTicketCount = 0;

        foreach ($expiredTickets as $ticket) {
            try {
                $hoursSinceShowtime = Carbon::parse($ticket->showtime->start_time)->diffInHours($now);
                
                Log::info("Processing expired ticket", [
                    'ticket_id' => $ticket->id,
                    'current_status' => $ticket->status->value,
                    'showtime_start' => $ticket->showtime->start_time,
                    'hours_since_showtime' => $hoursSinceShowtime,
                    'movie_name' => $ticket->showtime?->movie?->name ?? 'N/A'
                ]);

                $ticket->update([
                    'status' => TicketStatus::Cancelled,
                ]);
                $cancelledTicketCount++;

                Log::info("Auto cancelled expired ticket", [
                    'ticket_id' => $ticket->id,
                    'booking_id' => $ticket->booking_id,
                    'booking_code' => $ticket->booking->booking_code ?? 'N/A',
                    'movie_name' => $ticket->showtime?->movie?->name ?? 'N/A',
                    'seat' => $ticket->seat?->seat_number ?? 'N/A',
                    'showtime_start' => $ticket->showtime->start_time ?? 'N/A',
                    'hours_since_showtime' => $hoursSinceShowtime,
                    'new_status' => TicketStatus::Cancelled->value
                ]);

            } catch (\Exception $e) {
                Log::error("Failed to cancel expired ticket", [
                    'ticket_id' => $ticket->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        if ($cancelledTicketCount > 0) {
            Log::info("Auto cancelled {$cancelledTicketCount} expired tickets");
        } else {
            Log::info("No expired tickets found to cancel");
        }
    }
}