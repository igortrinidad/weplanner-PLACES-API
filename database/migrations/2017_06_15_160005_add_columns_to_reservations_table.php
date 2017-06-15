<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('place_reservations', function(Blueprint $table) {

            $table->json('therms')->after('is_pre_reservation');
            $table->timestamp('confirmed_at')->nullable()->after('is_confirmed');
            $table->timestamp('canceled_at')->nullable()->after('is_canceled');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
