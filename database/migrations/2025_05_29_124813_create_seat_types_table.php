<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seat_types', function (Blueprint $table) {
            $table->id()->comment('ID loại ghế');
            $table->string('name', 100)->unique()->comment('Tên loại ghế (duy nhất)');
            $table->decimal('price_modifier', 10, 2)->nullable()->comment('Hệ số giá (thêm vào giá cơ bản)');
            $table->string('color_code', 7)->nullable()->comment('Mã màu hiển thị ghế (hex)');
            $table->text('description')->nullable()->comment('Mô tả loại ghế');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seat_types');
    }
};