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
        Schema::table('reviews', function (Blueprint $table) {
            // Kiểm tra và thêm các cột nếu chưa tồn tại
            if (!Schema::hasColumn('reviews', 'admin_note')) {
                $table->text('admin_note')->nullable()->after('comment');
            }
            if (!Schema::hasColumn('reviews', 'reviewed_by')) {
                $table->unsignedBigInteger('reviewed_by')->nullable()->after('admin_note');
            }
            if (!Schema::hasColumn('reviews', 'reviewed_at')) {
                $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            }
            if (!Schema::hasColumn('reviews', 'status')) {
                $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('comment');
            }
            
            // Thêm index cho performance
            $table->index('status');
            $table->index(['movie_id', 'status']);
            
            // Foreign key constraint nếu cần
            if (!Schema::hasColumn('reviews', 'reviewed_by')) {
                $table->foreign('reviewed_by')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Xóa foreign key trước
            if (Schema::hasColumn('reviews', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
            }
            
            // Xóa các cột đã thêm
            $table->dropColumn(['admin_note', 'reviewed_by', 'reviewed_at']);
            
            // Không xóa cột status vì có thể đã tồn tại từ trước
            // $table->dropColumn('status');
        });
    }
};
