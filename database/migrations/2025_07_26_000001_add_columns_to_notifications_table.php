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
        Schema::table('notifications', function (Blueprint $table) {
            $table->string('entity_type', 100)
                ->nullable()
                ->after('user_id')
                ->comment('Tên model entity liên quan (booking, movie, ...)');
            $table->unsignedBigInteger('entity_id')
                ->nullable()
                ->after('entity_type')
                ->comment('ID của entity liên quan');
            $table->string('priority', 50)
                ->nullable()
                ->after('type')
                ->comment('Mức độ ưu tiên của thông báo (low, medium, high)');
            $table->string('old_status', 50)
                ->nullable()
                ->after('priority')
                ->comment('Trạng thái cũ của entity (nếu có)');
            $table->string('new_status', 50)
                ->nullable()
                ->after('old_status')
                ->comment('Trạng thái mới của entity (nếu có)');
            $table->text('event_details')
                ->nullable()
                ->after('new_status')
                ->comment('Chi tiết sự kiện bổ sung (nếu có)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn([
                'entity_type',
                'entity_id',
                'priority',
                'old_status',
                'new_status',
                'is_global',
                'event_details',
            ]);
        });
    }
};
