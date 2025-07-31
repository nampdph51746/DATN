<?php

namespace App\Mail;

use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TicketPurchasedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tickets;
    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct($tickets,$booking)
    {
        $this->tickets = $tickets;
        $this->booking = $booking;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->subject('Xác nhận mua vé xem phim')
                    ->view('emails.ticket-purchased')
                    ->with([
                        'tickets' => $this->tickets,
                        'booking' => $this->booking,
                    ]);
    }

    /**
     * Get the message content definition.
     */
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
}
