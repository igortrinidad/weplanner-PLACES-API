<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColuminsToPromotionalDates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotional_dates', function(Blueprint $table) {
            $table->boolean('all_day')->default(1)->after('date');
            $table->json('slots')->after('all_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promotional_dates', function(Blueprint $table) {
            $table->dropColumn('all_day');
            $table->dropColumn('slots');
        });
    }
}
