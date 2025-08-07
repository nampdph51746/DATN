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
        Schema::table('showtimes', function (Blueprint $table) {
            // Cập nhật enum để thêm giá trị 'postponed'
            DB::statement("ALTER TABLE showtimes MODIFY COLUMN status ENUM('scheduled', 'ongoing', 'completed', 'cancelled', 'postponed') NOT NULL DEFAULT 'scheduled'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('showtimes', function (Blueprint $table) {
            // Trở về enum cũ (xóa 'postponed')
            DB::statement("ALTER TABLE showtimes MODIFY COLUMN status ENUM('scheduled', 'ongoing', 'completed', 'cancelled') NOT NULL DEFAULT 'scheduled'");
        });
    }
};
