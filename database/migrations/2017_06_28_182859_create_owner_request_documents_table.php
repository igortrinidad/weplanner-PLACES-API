<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOwnerRequestDocumentsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('owner_request_documents', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('owner_request_id')->index();
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
		Schema::drop('owner_request_documents');
	}

}
