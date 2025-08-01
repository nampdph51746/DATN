<?php

require_once 'vendor/autoload.php';

// Load Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "SEAT TYPES:\n";
foreach(\App\Models\SeatType::all() as $st) {
    echo $st->id . ': ' . $st->name . ' (x' . $st->price_modifier . ")\n";
}

echo "\nROOM TYPES:\n";
foreach(\App\Models\RoomType::all() as $rt) {
    echo $rt->id . ': ' . $rt->name . ' (' . number_format($rt->base_price) . " VND)\n";
}

echo "\nCURRENT SEATS IN ROOMS:\n";
$rooms = \App\Models\Room::with(['seats.seatType', 'roomType'])->get();
foreach($rooms as $room) {
    echo "Room ID {$room->id} ({$room->name}) - Type: {$room->roomType->name}:\n";
    $seatCount = $room->seats->groupBy('seat_type_id');
    foreach($seatCount as $typeId => $seats) {
        $seatType = $seats->first()->seatType;
        echo "  - {$seatType->name}: " . count($seats) . " seats\n";
    }
    echo "\n";
}
