<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('user_id')->index();
            $table->uuid('category_id')->index();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('city');
            $table->string('state');
            $table->json('address');
            $table->integer('min_guests');
            $table->integer('max_guests');
            $table->json('informations');
            $table->boolean('confirmed')->default(false)->index();
            $table->string('slug')->index();
            $table->json('therms');
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
        Schema::dropIfExists('places');
    }
}
