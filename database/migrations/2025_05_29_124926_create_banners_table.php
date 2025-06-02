<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id()->comment('ID banner');
            $table->string('title', 255)->comment('Tiêu đề banner');
            $table->string('image_url', 255)->comment('URL ảnh banner');
            $table->string('link_url', 255)->nullable()->comment('URL liên kết khi nhấp vào banner');
            $table->integer('display_order')->nullable()->comment('Thứ tự hiển thị');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động');
            $table->timestamp('start_date')->nullable()->comment('Ngày bắt đầu hiển thị');
            $table->timestamp('end_date')->nullable()->comment('Ngày kết thúc hiển thị');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};