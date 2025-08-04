<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameImageUrlToImagePathInMoviesTable extends Migration
{
    public function up()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->renameColumn('image_url', 'image_path');
        });
    }

    public function down()
    {
        Schema::table('movies', function (Blueprint $table) {
            $table->renameColumn('image_path', 'image_url');
        });
    }
}