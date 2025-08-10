<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_options', function (Blueprint $table) {
            $table->foreignId('product_variant_id')->constrained()->comment('ID biến thể sản phẩm');
            $table->foreignId('attribute_value_id')->constrained()->comment('ID giá trị thuộc tính');
            $table->primary(['product_variant_id', 'attribute_value_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_options');
    }
};