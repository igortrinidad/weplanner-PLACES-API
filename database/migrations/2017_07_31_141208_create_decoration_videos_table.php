<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDecorationVideosTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('decoration_videos', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('decoration_id')->index();
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
		Schema::drop('decoration_videos');
	}

}
