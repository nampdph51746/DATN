<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('combo_package_items', function (Blueprint $table) {
            $table->unsignedBigInteger('combo_id')->nullable()->after('id');
            $table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('combo_package_items', function (Blueprint $table) {
            $table->dropForeign(['combo_id']);
            $table->dropColumn('combo_id');
        });
    }
};
