<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id()->comment('ID đánh giá');
            $table->foreignId('user_id')->constrained()->comment('ID người dùng');
            $table->foreignId('movie_id')->constrained()->comment('ID phim');
            $table->integer('rating_star')->comment('Số sao đánh giá (1-5)');
            $table->text('comment')->nullable()->comment('Nội dung đánh giá');
            $table->string('status', 20)->default('pending')->comment('Trạng thái đánh giá (pending, approved, rejected)');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
            $table->unique(['user_id', 'movie_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};