<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDecorationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('decorations', function(Blueprint $table) {
            $table->dropColumn('decor_name');
            $table->dropColumn('phone');
            $table->dropColumn('whatsapp');
            $table->dropColumn('email');
            $table->dropColumn('views');
            $table->dropColumn('phone_clicks');
            $table->dropColumn('whatsapp_clicks');
            $table->dropColumn('email_clicks');


            //new columns
            $table->uuid('advertiser_id')->after('place_id')->index();
            $table->string('action')->after('advertiser_id')->index();
            $table->json('action_data')->after('action');
            $table->string('title')->after('action_data');
            $table->text('description')->after('title');
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
