<?php

use App\Enums\PointReasonType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('point_history', function (Blueprint $table) {
            $table->id()->comment('ID lịch sử điểm');
            $table->foreignId('user_id')->constrained()->comment('ID người dùng');
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->comment('ID đơn đặt vé');
            $table->integer('points_change')->comment('Số điểm thay đổi (dương: kiếm được, âm: sử dụng)');
            $table->enum('reason_type', array_column(PointReasonType::cases(), 'value'))->comment('Lý do thay đổi điểm');
            $table->text('description')->nullable()->comment('Mô tả chi tiết');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('point_history');
    }
};