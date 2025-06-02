<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id()->comment('ID mục trong đơn đặt vé');
            $table->foreignId('booking_id')->constrained()->comment('ID đơn đặt vé');
            $table->foreignId('product_variant_id')->constrained()->comment('ID biến thể sản phẩm hoặc combo');
            $table->integer('quantity')->comment('Số lượng sản phẩm');
            $table->decimal('price_at_purchase', 10, 2)->comment('Giá tại thời điểm mua');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};