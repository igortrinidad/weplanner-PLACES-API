<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnerRequestsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('owner_requests', function(Blueprint $table) {
            $table->uuid('id');
            $table->uuid('place_id');
            $table->uuid('user_id');
            $table->uuid('plan_id')->nullable();
            $table->json('therms');
            $table->boolean('confirmed')->default(0);
            $table->boolean('canceled')->default(0);
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
		Schema::drop('owner_requests');
	}

}
