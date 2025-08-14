<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Cập nhật các booking có status 'confirmed' thành 'confirmed_not_printed'
        DB::table('bookings')
            ->where('status', 'confirmed')
            ->update(['status' => 'confirmed_not_printed']);

        // Thay đổi enum để include các status mới
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed_not_printed', 'confirmed_printed', 'cancelled') COMMENT 'Trạng thái đơn đặt vé'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert về trạng thái cũ
        DB::table('bookings')
            ->whereIn('status', ['confirmed_not_printed', 'confirmed_printed'])
            ->update(['status' => 'confirmed']);

        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'cancelled') COMMENT 'Trạng thái đơn đặt vé'");
    }
};
