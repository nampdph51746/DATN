<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('product_categories')->comment('ID danh mục sản phẩm');
            $table->string('name')->comment('Tên sản phẩm');
            $table->string('sku')->unique()->nullable()->comment('Mã sản phẩm');
            $table->text('description')->nullable()->comment('Mô tả sản phẩm');
            $table->string('image_url')->nullable()->comment('Đường dẫn ảnh sản phẩm');
            $table->enum('product_type', ['food', 'drink', 'combo'])->comment('Loại sản phẩm');
            $table->boolean('is_active')->default(true)->comment('Trạng thái sản phẩm');
            $table->decimal('base_price', 15, 2)->default(0)->nullable()->comment('Giá gốc sản phẩm');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};