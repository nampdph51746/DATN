<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoomType;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        $roomTypes = [
            [
                'name' => 'Standard',
                'description' => 'Phòng chiếu tiêu chuẩn với màn hình 2D thông thường',
                'base_price' => 80000,
                'status' => 'active'
            ],
            [
                'name' => 'Premium',
                'description' => 'Phòng chiếu cao cấp với ghế da, âm thanh tốt hơn',
                'base_price' => 120000,
                'status' => 'active'
            ],
            [
                'name' => 'VIP',
                'description' => 'Phòng chiếu VIP với ghế sofa đôi, dịch vụ cao cấp',
                'base_price' => 180000,
                'status' => 'active'
            ],
            [
                'name' => 'IMAX',
                'description' => 'Phòng chiếu IMAX với màn hình khổng lồ và âm thanh vòm',
                'base_price' => 250000,
                'status' => 'active'
            ],
            [
                'name' => '4DX',
                'description' => 'Phòng chiếu 4DX với ghế chuyển động và hiệu ứng đặc biệt',
                'base_price' => 300000,
                'status' => 'active'
            ],
            [
                'name' => 'Couple',
                'description' => 'Phòng chiếu dành cho đôi lứa với ghế đôi riêng tư',
                'base_price' => 200000,
                'status' => 'active'
            ]
        ];

        foreach ($roomTypes as $roomType) {
            RoomType::updateOrCreate(
                ['name' => $roomType['name']],
                $roomType
            );
        }
    }
}
