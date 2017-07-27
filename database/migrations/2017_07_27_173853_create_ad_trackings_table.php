<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdTrackingsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ad_trackings', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('ad_id')->index();
            $table->string('ad_type')->index();
            $table->date('reference')->index();
            $table->integer('exhibitions');
            $table->integer('clicks');
            $table->integer('call_clicks');
            $table->integer('contact_clicks');
            $table->integer('whatsapp_clicks');
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
		Schema::drop('ad_trackings');
	}

}
