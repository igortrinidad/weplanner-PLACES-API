<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDecorationPhotosTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('decoration_photos', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('decoration_id')->index();
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
		Schema::drop('decoration_photos');
	}

}
