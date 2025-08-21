<?php

return [
    // Số ghế tối đa mà một người có thể đặt trong 1 lần booking
    'max_seats_per_booking' => env('MAX_SEATS_PER_BOOKING', 8),
    
    // Cấu hình các loại ghế
    'seat_types' => [
        'standard' => [
            'name' => 'Ghế thường',
            'color' => '#28a745',
        ],
        'vip' => [
            'name' => 'Ghế VIP',
            'color' => '#ffc107',
        ],
        'couple' => [
            'name' => 'Ghế đôi',
            'color' => '#e83e8c',
        ],
    ],
];
