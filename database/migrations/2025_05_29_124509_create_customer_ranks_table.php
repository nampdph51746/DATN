<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_ranks', function (Blueprint $table) {
            $table->id()->comment('ID xếp hạng khách hàng');
            $table->string('name', 100)->unique()->comment('Tên xếp hạng (duy nhất)');
            $table->integer('min_points_required')->nullable()->comment('Số điểm tối thiểu để đạt xếp hạng');
            $table->decimal('discount_percentage', 5, 2)->nullable()->comment('Phần trăm giảm giá cho xếp hạng');
            $table->text('description')->nullable()->comment('Mô tả xếp hạng');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_ranks');
    }
};