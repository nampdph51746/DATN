<?php

use App\Enums\SeatStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('showtime_seat_states', function (Blueprint $table) {
            $table->id()->comment('ID trạng thái ghế trong suất chiếu');
            $table->foreignId('showtime_id')->constrained()->comment('ID suất chiếu');
            $table->foreignId('seat_id')->constrained()->comment('ID ghế');
            $table->enum('status', array_column(SeatStatus::cases(), 'value'))->comment('Trạng thái ghế trong suất chiếu');
            $table->timestamp('locked_until')->nullable()->comment('Thời gian khóa ghế (nếu có)');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->comment('ID đơn đặt vé liên kết');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
            $table->unique(['showtime_id', 'seat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('showtime_seat_states');
    }
};