<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('age_limits', function (Blueprint $table) {
            $table->id()->comment('ID giới hạn độ tuổi');
            $table->string('name', 50)->unique()->comment('Tên giới hạn độ tuổi (duy nhất)');
            $table->text('description')->nullable()->comment('Mô tả giới hạn độ tuổi');
            $table->integer('min_age')->nullable()->comment('Độ tuổi tối thiểu');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('age_limits');
    }
};