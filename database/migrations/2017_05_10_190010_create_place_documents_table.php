<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlaceDocumentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('place_documents', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('place_id')->index();
            $table->string('path');
            $table->string('filename');
            $table->string('extension');
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
		Schema::drop('place_documents');
	}

}
