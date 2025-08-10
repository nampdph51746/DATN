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
        Schema::table('promotions', function (Blueprint $table) {
            $table->unsignedBigInteger('rank_id')->nullable()->after('id')->comment('ID hạng khách hàng');
            // Nếu muốn liên kết với bảng ranks:
            $table->foreign('rank_id')->references('id')->on('customer_ranks')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promotions', function (Blueprint $table) {
            // Nếu có foreign key thì dropForeign trước:
            // $table->dropForeign(['rank_id']);
            $table->dropColumn('rank_id');
        });
    }
};
