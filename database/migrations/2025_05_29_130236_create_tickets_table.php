<?php

use App\Enums\TicketStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id()->comment('ID vé');
            $table->foreignId('booking_id')->constrained()->comment('ID đơn đặt vé');
            $table->foreignId('showtime_id')->constrained()->comment('ID suất chiếu');
            $table->foreignId('seat_id')->constrained()->comment('ID ghế');
            $table->string('ticket_code', 30)->unique()->comment('Mã vé (duy nhất)');
            $table->decimal('price_at_purchase', 10, 2)->comment('Giá vé tại thời điểm mua');
            $table->enum('status', array_column(TicketStatus::cases(), 'value'))->nullable()->comment('Trạng thái vé');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
            $table->unique(['showtime_id', 'seat_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};