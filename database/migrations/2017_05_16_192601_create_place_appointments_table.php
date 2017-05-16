<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceAppointmentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('place_appointments', function(Blueprint $table) {
            $table->uuid('id');
            $table->uuid('place_id');
            $table->string('title');
            $table->boolean('allDay');
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('url')->nullable();
            $table->string('className')->nullable();
            $table->boolean('editable')->default(0);
            $table->boolean('startEditable')->default(0);
            $table->boolean('durationEditable')->default(0);
            $table->boolean('resourceEditable')->default(0);
            $table->string('rendering')->nullable();
            $table->boolean('overlap')->default(0);
            $table->string('constraint')->nullable();
            $table->string('source')->nullable();
            $table->string('color')->nullable();
            $table->string('backgroundColor')->nullable();
            $table->string('borderColor')->nullable();
            $table->string('textColor')->nullable();
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
		Schema::drop('place_appointments');
	}

}
