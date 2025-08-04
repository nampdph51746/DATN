<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Ticket;

class DebugTicketData extends Command
{
    protected $signature = 'debug:tickets';
    protected $description = 'Debug ticket data';

    public function handle()
    {
        $this->info('=== DEBUG TICKET DATA ===');
        
        // Kiểm tra tổng số tickets trong database
        $totalTickets = Ticket::count();
        $this->info("Tổng số tickets trong database: {$totalTickets}");
        
        if ($totalTickets == 0) {
            $this->error('Không có ticket nào trong database!');
            return;
        }
        
        // Lấy ticket đầu tiên
        $ticket = Ticket::with(['booking', 'seat'])->first();
        $this->info("=== TICKET ĐẦU TIÊN ===");
        $this->info("ID: {$ticket->id}");
        $this->info("Booking ID: {$ticket->booking_id}");
        $this->info("Price at purchase: " . ($ticket->price_at_purchase ?? 'NULL'));
        
        if ($ticket->seat) {
            $seatName = $ticket->seat->row_char . $ticket->seat->seat_number;
            $this->info("Ghế: {$seatName}");
        } else {
            $this->info("Ghế: NULL");
        }
        
        if ($ticket->booking) {
            $this->info("Booking code: {$ticket->booking->booking_code}");
        } else {
            $this->info("Booking: NULL");
        }
        
        // Lấy booking có tickets
        $booking = Booking::has('tickets')->with(['tickets.seat'])->first();
        
        if (!$booking) {
            $this->error('Không có booking nào có tickets');
            return;
        }
        
        $this->info("=== BOOKING CÓ TICKETS ===");
        $this->info("Booking Code: {$booking->booking_code}");
        $this->info("Số vé: " . $booking->tickets->count());
        
        foreach ($booking->tickets as $index => $ticket) {
            $this->info("--- Vé " . ($index + 1) . " ---");
            $this->info("ID: {$ticket->id}");
            $this->info("Price at purchase: " . ($ticket->price_at_purchase ?? 'NULL'));
            
            if ($ticket->seat) {
                $seatName = $ticket->seat->row_char . $ticket->seat->seat_number;
                $this->info("Ghế: {$seatName}");
            } else {
                $this->info("Ghế: NULL");
            }
            
            $this->info("Status: {$ticket->status->value}");
        }
    }
}
