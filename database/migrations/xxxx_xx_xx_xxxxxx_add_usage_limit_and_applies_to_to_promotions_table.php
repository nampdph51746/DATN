<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->integer('usage_limit_per_user')->nullable()->after('quantity');
            $table->string('applies_to')->nullable()->after('usage_limit_per_user');
        });
    }

    public function down()
    {
        Schema::table('promotions', function (Blueprint $table) {
            $table->dropColumn('usage_limit_per_user');
            $table->dropColumn('applies_to');
        });
    }
};