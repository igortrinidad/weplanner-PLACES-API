<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function(Blueprint $table) {

            $table->json('instructions')->nullable()->after('therms');
            $table->decimal('reservation_price', 15, 2)->nullable()->after('instructions');
            $table->decimal('pre_reservation_price', 15, 2)->nullable()->after('reservation_price');

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

            $table->dropColumn('instructions');
            $table->dropColumn('reservation_price');
            $table->dropColumn('pre_reservation_price');

        });
    }
}
