<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('combos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('combo_product_variant_id');
            $table->decimal('price', 15, 2)->default(0);
            $table->integer('stock_quantity')->default(0);
            $table->timestamps();

            $table->foreign('combo_product_variant_id')
                ->references('id')->on('product_variants')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('combos');
    }
};
