<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceAdPhotosTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('service_ad_photos', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('service_ad_id')->index();
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
		Schema::drop('service_ad_photos');
	}

}
