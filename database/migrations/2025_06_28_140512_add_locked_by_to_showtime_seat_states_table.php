<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
    {
        Schema::table('showtime_seat_states', function (Blueprint $table) {
            $table->string('locked_by')->nullable()->after('locked_until');
        });
    }

    public function down()
    {
        Schema::table('showtime_seat_states', function (Blueprint $table) {
            $table->dropColumn('locked_by');
        });
    }
};
