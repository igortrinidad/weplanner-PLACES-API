<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPlaceTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('place_trackings', function(Blueprint $table) {
            $table->integer('contact_call')->default(0)->after('duration');
            $table->integer('contact_whatsapp')->default(0)->after('contact_call');
            $table->integer('contact_message')->default(0)->after('contact_whatsapp');
            $table->integer('share_copy')->default(0)->after('contact_message');
            $table->integer('share_facebook')->default(0)->after('share_copy');
            $table->integer('share_whatsapp')->default(0)->after('share_facebook');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
