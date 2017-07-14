<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterPlaceTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('place_trackings', function(Blueprint $table) {

            //create new columns
            $table->date('reference')->after('place_id');
            $table->integer('views')->after('reference')->default(0);
            //rename columns
            $table->renameColumn('contact_call', 'call_clicks');
            $table->renameColumn('contact_whatsapp', 'whatsapp_clicks');
            $table->renameColumn('contact_message', 'contact_clicks');
            $table->renameColumn('share_copy', 'link_shares');
            $table->renameColumn('share_whatsapp', 'whatsapp_shares');
            $table->renameColumn('share_facebook', 'facebook_shares');
            $table->renameColumn('duration', 'permanence');
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
