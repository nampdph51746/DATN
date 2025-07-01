<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
    {
        Schema::create('room_seat_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('seat_type_id')->constrained()->onDelete('cascade');
            $table->decimal('percentage', 5, 2); // Tỷ lệ phần trăm (ví dụ: 50.00)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('room_seat_configurations');
    }
};
