<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceReservationsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('place_reservations', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('place_id')->index();
            $table->uuid('client_id')->index();
            $table->uuid('date')->index();
            $table->boolean('is_confirmed')->nullable();
            $table->boolean('is_canceled')->nullable();
            $table->timestamps();
            $table->primary('id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('place_reservations');
	}

}
