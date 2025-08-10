<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('combo_package_items', function (Blueprint $table) {
            $table->id()->comment('ID mục trong gói combo');
            $table->foreignId('combo_product_variant_id')->constrained('product_variants')->comment('ID biến thể combo');
            $table->foreignId('item_product_variant_id')->constrained('product_variants')->comment('ID biến thể sản phẩm con');
            $table->integer('quantity')->default(1)->comment('Số lượng sản phẩm con trong combo');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('combo_package_items');
    }
};