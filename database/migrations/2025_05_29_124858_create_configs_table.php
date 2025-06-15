<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('configs', function (Blueprint $table) {
            $table->id()->comment('ID cấu hình');
            $table->string('config_key', 100)->unique()->comment('Khóa cấu hình (duy nhất)');
            $table->text('config_value')->nullable()->comment('Giá trị cấu hình');
            $table->text('description')->nullable()->comment('Mô tả cấu hình');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};