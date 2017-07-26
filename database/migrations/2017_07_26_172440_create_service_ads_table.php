<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceAdsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('service_ads', function(Blueprint $table) {
            $table->uuid('id');
            $table->uuid('advertiser_id')->index();
            $table->date('expire_at')->index();
            $table->uuid('place_id')->nullable();
            $table->string('type')->index();
            $table->string('action')->index();
            $table->string('title');
            $table->text('description');
            $table->json('action_data');
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->boolean('is_active');
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
		Schema::drop('service_ads');
	}

}
