<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id()->comment('ID thành phố');
            $table->string('name', 100)->unique()->comment('Tên thành phố (duy nhất)');
            $table->foreignId('country_id')->nullable()->constrained()->comment('ID quốc gia');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};