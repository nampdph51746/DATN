<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Thêm seat types mới phù hợp với các room types
        DB::table('seat_types')->insert([
            // IMAX seat types
            [
                'name' => 'IMAX Standard',
                'price_modifier' => 1.00,
                'color_code' => '#4CAF50',
                'description' => 'Ghế tiêu chuẩn IMAX với âm thanh và hình ảnh siêu nét',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'IMAX Premium',
                'price_modifier' => 1.15,
                'color_code' => '#FF9800',
                'description' => 'Ghế cao cấp IMAX với vị trí tốt nhất',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // 4DX seat types
            [
                'name' => '4DX Motion',
                'price_modifier' => 1.20,
                'color_code' => '#E91E63',
                'description' => 'Ghế chuyển động 4DX với hiệu ứng đặc biệt',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Couple seat types
            [
                'name' => 'Couple Sofa',
                'price_modifier' => 1.25,
                'color_code' => '#9C27B0',
                'description' => 'Ghế sofa đôi dành cho cặp đôi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Couple Bed',
                'price_modifier' => 1.50,
                'color_code' => '#673AB7',
                'description' => 'Ghế giường đôi cao cấp dành cho cặp đôi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Premium seat types
            [
                'name' => 'Premium Recliner',
                'price_modifier' => 1.15,
                'color_code' => '#3F51B5',
                'description' => 'Ghế dựa cao cấp có thể nằm',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Cine & Living seat types
            [
                'name' => 'Living Sofa',
                'price_modifier' => 1.05,
                'color_code' => '#607D8B',
                'description' => 'Ghế sofa thoải mái như ở nhà',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        // Xóa các seat types đã thêm
        DB::table('seat_types')->whereIn('name', [
            'IMAX Standard', 'IMAX Premium', '4DX Motion', 
            'Couple Sofa', 'Couple Bed', 'Premium Recliner', 'Living Sofa'
        ])->delete();
    }
};
