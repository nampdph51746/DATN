<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_attempts', function (Blueprint $table) {
            $table->id()->comment('ID của lần thử đặt vé');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ID người dùng');
            $table->foreignId('showtime_id')->constrained('showtimes')->onDelete('cascade')->comment('ID suất chiếu');
            $table->json('seat_ids')->comment('Danh sách ID ghế đã đặt');
            $table->enum('status', ['reserved', 'timeout', 'cancelled', 'completed'])->default('reserved')->comment('Trạng thái: reserved(đang giữ), timeout(hết hạn), cancelled(hủy), completed(hoàn thành)');
            $table->timestamp('reserved_at')->comment('Thời gian bắt đầu giữ ghế');
            $table->timestamp('expired_at')->comment('Thời gian hết hạn giữ ghế');
            $table->timestamp('completed_at')->nullable()->comment('Thời gian hoàn thành thanh toán');
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'reserved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_attempts');
    }
};
