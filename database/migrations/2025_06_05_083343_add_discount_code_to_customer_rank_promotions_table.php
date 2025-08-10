<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customer_rank_promotions', function (Blueprint $table) {
            $table->string('discount_code', 50)->nullable()->after('promotion_id')->comment('Mã giảm giá riêng cho từng liên kết');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_rank_promotions', function (Blueprint $table) {
            $table->dropColumn('discount_code');
        });
    }
};
