<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points', function (Blueprint $table) {
            $table->id()->comment('ID điểm tích lũy');
            $table->foreignId('user_id')->constrained()->unique()->comment('ID người dùng');
            $table->integer('total_points')->nullable()->comment('Tổng điểm hiện tại');
            $table->date('points_expiry_date')->nullable()->comment('Ngày hết hạn điểm');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};