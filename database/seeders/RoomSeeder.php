<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $rooms = [
            [
                'name' => 'IMAX 01',
                'room_type_id' => 3, // IMAX
                'cinema_id' => 1,
                'capacity' => 120,
            ],
            [
                'name' => '4DX 01',
                'room_type_id' => 4, // 4DX
                'cinema_id' => 1,
                'capacity' => 60,
            ],
            [
                'name' => 'Couple Sofa 01',
                'room_type_id' => 5, // Couple Sofa
                'cinema_id' => 1,
                'capacity' => 40,
            ],
            [
                'name' => 'Premium Recliner 01',
                'room_type_id' => 6, // Premium Recliner
                'cinema_id' => 1,
                'capacity' => 30,
            ],
            [
                'name' => 'Living Sofa 01',
                'room_type_id' => 7, // Living Sofa
                'cinema_id' => 1,
                'capacity' => 25,
            ],
        ];

        foreach ($rooms as $room) {
            Room::updateOrCreate([
                'name' => $room['name'],
            ], $room);
        }
    }
}
