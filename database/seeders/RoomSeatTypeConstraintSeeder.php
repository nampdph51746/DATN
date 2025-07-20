<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;
use App\Models\SeatType;
use App\Models\RoomSeatTypeConstraint;

class RoomSeatTypeConstraintSeeder extends Seeder
{
    public function run()
    {
        // Danh sách constraint cho từng loại phòng
        $constraints = [
            // Standard Room: Regular, VIP, Sweetbox
            [ 'room_type_id' => 1, 'seat_type_id' => 1, 'min_percentage' => 60, 'max_percentage' => 80, 'is_required' => true ], // Regular
            [ 'room_type_id' => 1, 'seat_type_id' => 2, 'min_percentage' => 10, 'max_percentage' => 30, 'is_required' => false ], // VIP
            [ 'room_type_id' => 1, 'seat_type_id' => 3, 'min_percentage' => 10, 'max_percentage' => 20, 'is_required' => false ], // Sweetbox

            // IMAX Room: IMAX Standard, IMAX Premium
            [ 'room_type_id' => 3, 'seat_type_id' => 4, 'min_percentage' => 70, 'max_percentage' => 100, 'is_required' => true ], // IMAX Standard
            [ 'room_type_id' => 3, 'seat_type_id' => 5, 'min_percentage' => 0, 'max_percentage' => 30, 'is_required' => false ], // IMAX Premium

            // 4DX Room: 4DX Motion
            [ 'room_type_id' => 4, 'seat_type_id' => 6, 'min_percentage' => 100, 'max_percentage' => 100, 'is_required' => true ], // 4DX Motion

            // Couple Sofa Room: Couple Sofa, Couple Bed
            [ 'room_type_id' => 5, 'seat_type_id' => 7, 'min_percentage' => 60, 'max_percentage' => 80, 'is_required' => true ], // Couple Sofa
            [ 'room_type_id' => 5, 'seat_type_id' => 8, 'min_percentage' => 20, 'max_percentage' => 40, 'is_required' => false ], // Couple Bed

            // Premium Recliner Room: Premium Recliner
            [ 'room_type_id' => 6, 'seat_type_id' => 9, 'min_percentage' => 100, 'max_percentage' => 100, 'is_required' => true ], // Premium Recliner

            // Living Sofa Room: Living Sofa
            [ 'room_type_id' => 7, 'seat_type_id' => 10, 'min_percentage' => 100, 'max_percentage' => 100, 'is_required' => true ], // Living Sofa
        ];

        foreach ($constraints as $c) {
            RoomSeatTypeConstraint::updateOrCreate([
                'room_type_id' => $c['room_type_id'],
                'seat_type_id' => $c['seat_type_id'],
            ], [
                'min_percentage' => $c['min_percentage'],
                'max_percentage' => $c['max_percentage'],
                'is_required' => $c['is_required'],
            ]);
        }
    }
}
