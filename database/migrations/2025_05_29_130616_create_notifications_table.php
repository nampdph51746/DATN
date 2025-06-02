<?php

use App\Enums\NotificationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id()->comment('ID thông báo');
            $table->foreignId('user_id')->constrained()->comment('ID người dùng nhận thông báo');
            $table->string('title', 255)->comment('Tiêu đề thông báo');
            $table->text('message')->comment('Nội dung thông báo');
            $table->enum('type', array_column(NotificationType::cases(), 'value'))->nullable()->comment('Loại thông báo');
            $table->string('link_url', 255)->nullable()->comment('URL liên kết khi nhấp vào thông báo');
            $table->boolean('is_read')->default(false)->comment('Trạng thái đã đọc');
            $table->timestamp('read_at')->nullable()->comment('Thời gian đọc thông báo');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};