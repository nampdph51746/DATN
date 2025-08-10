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
            $table->enum('ticket_status', ['valid', 'used', 'cancelled'])->default('valid')->after('price_at_purchase');
            $table->timestamp('used_at')->nullable()->after('ticket_status');
            $table->unsignedBigInteger('scanned_by')->nullable()->after('used_at');
            
            // Foreign key cho người quét vé (nếu có hệ thống user cho nhân viên)
            // $table->foreign('scanned_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->dropColumn(['ticket_status', 'used_at', 'scanned_by']);
        });
    }
};
