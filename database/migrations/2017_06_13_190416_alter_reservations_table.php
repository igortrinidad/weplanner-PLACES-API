<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('place_reservations', function(Blueprint $table) {

            $table->dropColumn('date');

            $table->timestamp('start')->after('client_id')->nullable();
            $table->timestamp('end')->after('start')->nullable();
            $table->boolean('is_pre_reservation')->after('end')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}