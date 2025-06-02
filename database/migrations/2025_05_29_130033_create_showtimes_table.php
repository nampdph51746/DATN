<?php

use App\Enums\ShowtimeStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('showtimes', function (Blueprint $table) {
            $table->id()->comment('ID suất chiếu');
            $table->foreignId('movie_id')->constrained()->comment('ID phim');
            $table->foreignId('room_id')->constrained()->comment('ID phòng chiếu');
            $table->timestamp('start_time')->comment('Thời gian bắt đầu');
            $table->timestamp('end_time')->comment('Thời gian kết thúc');
            $table->decimal('base_price', 10, 2)->comment('Giá vé cơ bản');
            $table->enum('status', array_column(ShowtimeStatus::cases(), 'value'))->nullable()->comment('Trạng thái suất chiếu');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('showtimes');
    }
};