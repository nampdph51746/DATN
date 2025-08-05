<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('admin_note')->nullable()->comment('Ghi chú từ admin');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->comment('Admin đã duyệt');
            $table->timestamp('reviewed_at')->nullable()->comment('Thời gian duyệt');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['admin_note', 'reviewed_by', 'reviewed_at']);
        });
    }
};
