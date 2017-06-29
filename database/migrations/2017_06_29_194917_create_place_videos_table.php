<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceVideosTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('place_videos', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('place_id')->index();
            $table->string('provider')->index();
            $table->string('title')->nullable();
            $table->string('url');
            $table->boolean('published')->default(0);
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
		Schema::drop('place_videos');
	}

}
