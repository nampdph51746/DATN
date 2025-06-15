<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id()->comment('ID biến thể sản phẩm');
            $table->foreignId('product_id')->constrained()->comment('ID sản phẩm chính');
            $table->string('sku', 100)->unique()->nullable()->comment('Mã SKU (duy nhất)');
            $table->decimal('price', 10, 2)->comment('Giá bán của biến thể');
            $table->integer('stock_quantity')->default(0)->comment('Số lượng tồn kho');
            $table->string('image_url', 255)->nullable()->comment('URL ảnh riêng (ghi đè image_url của sản phẩm)');
            $table->boolean('is_active')->default(true)->comment('Trạng thái biến thể');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};