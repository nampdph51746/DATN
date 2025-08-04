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
        Schema::table('tickets', function (Blueprint $table) {
            $table->timestamp('used_at')->nullable()->after('status');
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
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['used_at', 'scanned_by']);
        });
    }
};
