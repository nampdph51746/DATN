<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Định nghĩa seat types được phép cho mỗi room type
        $constraints = [
            // Standard room (id=2) - Chỉ cho phép ghế cơ bản
            [
                'room_type_name' => 'Standard',
                'allowed_seats' => [
                    ['name' => 'Regular', 'min' => 60, 'max' => 80, 'required' => true],
                    ['name' => 'VIP', 'min' => 20, 'max' => 40, 'required' => false],
                ]
            ],
            
            // Premium room (id=3) - Cho phép ghế cao cấp hơn
            [
                'room_type_name' => 'Premium',
                'allowed_seats' => [
                    ['name' => 'Regular', 'min' => 40, 'max' => 60, 'required' => true],
                    ['name' => 'VIP', 'min' => 30, 'max' => 50, 'required' => true],
                    ['name' => 'Premium Recliner', 'min' => 10, 'max' => 30, 'required' => false],
                ]
            ],
            
            // VIP room (id=4) - Chủ yếu ghế VIP và cao cấp
            [
                'room_type_name' => 'VIP',
                'allowed_seats' => [
                    ['name' => 'VIP', 'min' => 50, 'max' => 70, 'required' => true],
                    ['name' => 'Premium Recliner', 'min' => 20, 'max' => 40, 'required' => true],
                    ['name' => 'Sweetbox', 'min' => 10, 'max' => 30, 'required' => false],
                ]
            ],
            
            // IMAX room (id=5) - Chỉ ghế IMAX
            [
                'room_type_name' => 'IMAX',
                'allowed_seats' => [
                    ['name' => 'IMAX Standard', 'min' => 60, 'max' => 80, 'required' => true],
                    ['name' => 'IMAX Premium', 'min' => 20, 'max' => 40, 'required' => true],
                ]
            ],
            
            // 4DX room (id=6) - Chỉ ghế 4DX
            [
                'room_type_name' => '4DX',
                'allowed_seats' => [
                    ['name' => '4DX Motion', 'min' => 80, 'max' => 100, 'required' => true],
                ]
            ],
            
            // Couple room (id=7) - Chỉ ghế đôi
            [
                'room_type_name' => 'Couple',
                'allowed_seats' => [
                    ['name' => 'Couple Sofa', 'min' => 60, 'max' => 80, 'required' => true],
                    ['name' => 'Couple Bed', 'min' => 20, 'max' => 40, 'required' => false],
                ]
            ],
            
            // Cine & Living room (id=1) - Ghế thoải mái như ở nhà
            [
                'room_type_name' => 'Cine & Living',
                'allowed_seats' => [
                    ['name' => 'Living Sofa', 'min' => 50, 'max' => 70, 'required' => true],
                    ['name' => 'VIP', 'min' => 20, 'max' => 40, 'required' => false],
                    ['name' => 'Sweetbox', 'min' => 10, 'max' => 30, 'required' => false],
                ]
            ],
        ];

        foreach ($constraints as $constraint) {
            $roomType = DB::table('room_types')->where('name', $constraint['room_type_name'])->first();
            if ($roomType) {
                foreach ($constraint['allowed_seats'] as $seatConstraint) {
                    $seatType = DB::table('seat_types')->where('name', $seatConstraint['name'])->first();
                    if ($seatType) {
                        DB::table('room_seat_type_constraints')->insert([
                            'room_type_id' => $roomType->id,
                            'seat_type_id' => $seatType->id,
                            'min_percentage' => $seatConstraint['min'],
                            'max_percentage' => $seatConstraint['max'],
                            'is_required' => $seatConstraint['required'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }

    public function down()
    {
        DB::table('room_seat_type_constraints')->truncate();
    }
};
