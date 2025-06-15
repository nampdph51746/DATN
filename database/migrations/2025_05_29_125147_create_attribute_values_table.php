<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->id()->comment('ID giá trị thuộc tính');
            $table->foreignId('attribute_id')->constrained()->comment('ID thuộc tính');
            $table->string('value', 100)->comment('Giá trị thuộc tính');
            $table->timestamp('created_at')->nullable()->comment('Thời gian tạo');
            $table->timestamp('updated_at')->nullable()->comment('Thời gian cập nhật');
            $table->unique(['attribute_id', 'value']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attribute_values');
    }
};