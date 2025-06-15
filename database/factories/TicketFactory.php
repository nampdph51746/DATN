<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ticket;
use App\Enums\TicketStatus;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'booking_id' => \App\Models\Booking::factory(),
            'showtime_id' => \App\Models\Showtime::factory(),
            'seat_id' => \App\Models\Seat::factory(),
            'ticket_code' => $this->faker->unique()->lexify('TICKET-?????'),
            'price_at_purchase' => $this->faker->randomFloat(2, 50, 200),
            'status' => $this->faker->randomElement(TicketStatus::cases()),
        ];
    }
}