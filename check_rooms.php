<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$rooms = App\Models\Room::select('id', 'name', 'room_type_id')->with('roomType:id,name')->get();

echo "Available Rooms:\n";
echo "================\n";

foreach ($rooms as $room) {
    echo "ID: {$room->id}, Name: {$room->name}, Type: {$room->roomType->name}\n";
}

echo "\n";
