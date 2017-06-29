<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewColumnsToPlaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function(Blueprint $table) {
            $table->text('virtual_tour_url')->after('pre_reservation_price')->nullable();
            $table->integer('featured_position')->default(0)->after('virtual_tour_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('places', function(Blueprint $table) {
            $table->dropColumn('virtual_tour_url');
            $table->dropColumn('featured_position');
        });
    }
}
