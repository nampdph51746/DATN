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
        Schema::table('booking_items', function (Blueprint $table) {
            $table->enum('product_status', ['valid', 'checked', 'used', 'cancelled'])->default('valid')->after('ticket_status')->comment('Trạng thái sản phẩm/combo');
            $table->foreignId('combo_id')->nullable()->constrained('combos')->onDelete('set null')->after('product_status')->comment('ID combo nếu item này là combo');
            $table->index(['combo_id']); // Index để tìm kiếm nhanh
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->dropIndex(['combo_id']);
            $table->dropForeign(['combo_id']);
            $table->dropColumn(['product_status', 'combo_id']);
        });
    }
};
