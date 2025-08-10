<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_rank_promotions', function (Blueprint $table) {
            $table->foreignId('customer_rank_id')->constrained()->comment('ID xếp hạng khách hàng');
            $table->foreignId('promotion_id')->constrained()->comment('ID khuyến mãi');
            $table->text('description')->nullable()->comment('Mô tả khuyến mãi cho xếp hạng');
            $table->primary(['customer_rank_id', 'promotion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_rank_promotions');
    }
};