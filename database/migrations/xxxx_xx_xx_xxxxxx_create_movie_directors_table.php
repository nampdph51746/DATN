<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieDirectorsTable extends Migration
{
    public function up()
    {
        Schema::create('movie_directors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('movie_id');
            $table->unsignedBigInteger('director_id');
            $table->timestamps();

            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->foreign('director_id')->references('id')->on('directors')->onDelete('cascade');
            $table->unique(['movie_id', 'director_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('movie_directors');
    }
}