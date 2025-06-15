<?php

use App\Enums\CinemaStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cinemas', function (Blueprint $table) {
            $table->id()->comment('ID rạp chiếu phim');
            $table->string('name', 255)->comment('Tên rạp');
            $table->text('address')->comment('Địa chỉ rạp');
            $table->foreignId('city_id')->nullable()->constrained()->comment('ID thành phố');
            $table->string('hotline', 20)->nullable()->comment('Số hotline');
            $table->string('email', 255)->nullable()->comment('Email liên hệ');
            $table->string('map_url', 500)->nullable()->comment('URL bản đồ');
            $table->string('image_url', 255)->nullable()->comment('URL ảnh rạp');
            $table->string('opening_hours', 255)->nullable()->comment('Giờ mở cửa');
            $table->text('description')->nullable()->comment('Mô tả rạp');
            $table->enum('status', array_column(CinemaStatus::cases(), 'value'))->nullable()->comment('Trạng thái rạp');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cinemas');
    }
};