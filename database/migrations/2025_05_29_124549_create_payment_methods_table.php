<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id()->comment('ID phương thức thanh toán');
            $table->string('name', 100)->unique()->comment('Tên phương thức (duy nhất)');
            $table->string('code', 50)->unique()->nullable()->comment('Mã phương thức (duy nhất)');
            $table->boolean('is_active')->default(true)->comment('Trạng thái hoạt động');
            $table->string('logo_url', 255)->nullable()->comment('URL logo phương thức');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};