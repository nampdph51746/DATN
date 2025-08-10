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
        Schema::create('room_seat_type_constraints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->foreignId('seat_type_id')->constrained('seat_types')->onDelete('cascade');
            $table->decimal('min_percentage', 5, 2)->default(0.00)->comment('Tỷ lệ tối thiểu %');
            $table->decimal('max_percentage', 5, 2)->default(100.00)->comment('Tỷ lệ tối đa %');
            $table->boolean('is_required')->default(false)->comment('Loại ghế bắt buộc');
            $table->integer('priority_order')->default(0)->comment('Thứ tự ưu tiên');
            $table->timestamps();
            
            // Unique constraint để đảm bảo mỗi room_type chỉ có 1 constraint cho mỗi seat_type
            $table->unique(['room_type_id', 'seat_type_id']);
            
            // Index để tăng performance
            $table->index(['room_type_id', 'is_required']);
            $table->index(['priority_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_seat_type_constraints');
    }
};
