<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacePhotosTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('place_photos', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('place_id')->index();
            $table->string('path');
            $table->boolean('is_cover')->default(0);
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
		Schema::drop('place_photos');
	}

}
