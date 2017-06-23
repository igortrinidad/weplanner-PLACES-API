<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOracleSocialProvidersTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('oracle_social_providers', function(Blueprint $table) {
            $table->uuid('id');
            $table->uuid('user_id');
            $table->string('provider');
            $table->string('provider_id');
            $table->string('access_token');
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
		Schema::drop('oracle_social_providers');
	}

}
