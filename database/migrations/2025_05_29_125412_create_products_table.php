<?php

use App\Enums\ProductType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id()->comment('ID sản phẩm');
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->comment('ID danh mục sản phẩm');
            $table->string('name', 255)->comment('Tên sản phẩm');
            $table->text('description')->nullable()->comment('Mô tả sản phẩm');
            $table->string('image_url', 255)->nullable()->comment('URL ảnh chung (có thể bị ghi đè bởi biến thể)');
            $table->enum('product_type', array_column(ProductType::cases(), 'value'))->comment('Loại sản phẩm');
            $table->boolean('is_active')->default(true)->comment('Trạng thái sản phẩm');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};