<?php

use App\Enums\SeatStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seats', function (Blueprint $table) {
            $table->id()->comment('ID ghế');
            $table->foreignId('room_id')->constrained()->comment('ID phòng chiếu');
            $table->foreignId('seat_type_id')->constrained()->comment('ID loại ghế');
            $table->string('row_char', 5)->comment('Hàng ghế (A, B, C,...)');
            $table->string('seat_number', 5)->comment('Số ghế trong hàng');
            $table->enum('status', array_column(SeatStatus::cases(), 'value'))->nullable()->comment('Trạng thái ghế');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
            $table->unique(['room_id', 'row_char', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};