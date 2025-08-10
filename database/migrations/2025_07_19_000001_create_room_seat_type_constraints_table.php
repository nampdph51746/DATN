<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tạo bảng room_seat_type_constraints để định nghĩa seat types được phép cho mỗi room type
        Schema::create('room_seat_type_constraints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->foreignId('seat_type_id')->constrained('seat_types')->onDelete('cascade');
            $table->decimal('min_percentage', 5, 2)->default(0); // Tỷ lệ tối thiểu
            $table->decimal('max_percentage', 5, 2)->default(100); // Tỷ lệ tối đa
            $table->boolean('is_required')->default(false); // Bắt buộc phải có
            $table->timestamps();
            
            $table->unique(['room_type_id', 'seat_type_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_seat_type_constraints');
    }
};
