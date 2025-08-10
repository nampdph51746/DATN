<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_genres', function (Blueprint $table) {
            $table->foreignId('movie_id')->constrained()->comment('ID phim');
            $table->foreignId('genre_id')->constrained()->comment('ID thể loại');
            $table->primary(['movie_id', 'genre_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_genres');
    }
};