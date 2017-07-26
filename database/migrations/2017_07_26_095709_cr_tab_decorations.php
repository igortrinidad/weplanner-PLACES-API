<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrTabDecorations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('decorations', function(Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('place_id')->index();
            $table->date('expire_at');
            $table->boolean('is_active')->default(0);
            $table->string('decor_name');
            $table->string('phone');
            $table->string('whatsapp');
            $table->string('email');
            $table->integer('views')->default(0);
            $table->integer('phone_clicks')->default(0);
            $table->integer('whatsapp_clicks')->default(0);
            $table->integer('email_clicks')->default(0);
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
        Schema::drop('decorations');
    }
}
