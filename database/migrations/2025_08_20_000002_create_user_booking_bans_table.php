<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_booking_bans', function (Blueprint $table) {
            $table->id()->comment('ID của lệnh cấm');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('ID người dùng bị cấm');
            $table->integer('failed_attempts')->comment('Số lần đặt vé thất bại liên tiếp');
            $table->timestamp('banned_at')->comment('Thời gian bắt đầu cấm');
            $table->timestamp('banned_until')->comment('Thời gian kết thúc cấm');
            $table->boolean('is_active')->default(true)->comment('Trạng thái cấm có đang hiệu lực');
            $table->text('reason')->nullable()->comment('Lý do cấm');
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
            $table->index(['banned_until']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_booking_bans');
    }
};
