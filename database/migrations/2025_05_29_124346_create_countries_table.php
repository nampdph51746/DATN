<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id()->comment('ID quốc gia');
            $table->string('name', 100)->unique()->comment('Tên quốc gia (duy nhất)');
            $table->string('code', 10)->unique()->nullable()->comment('Mã quốc gia (duy nhất)');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};