<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id()->comment('ID phòng chiếu');
            $table->foreignId('cinema_id')->constrained()->comment('ID rạp chiếu');
            $table->foreignId('room_type_id')->nullable()->constrained()->comment('ID loại phòng');
            $table->string('name', 100)->comment('Tên phòng');
            $table->integer('capacity')->comment('Sức chứa');
            $table->string('status', 20)->nullable()->comment('Trạng thái phòng (active, maintenance)');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
            $table->unique(['cinema_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};