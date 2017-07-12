<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromotionalDatesTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('promotional_dates', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('place_id')->index();
            $table->date('date')->index();
            $table->string('title');
            $table->text('rules');
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
		Schema::drop('promotional_dates');
	}

}
