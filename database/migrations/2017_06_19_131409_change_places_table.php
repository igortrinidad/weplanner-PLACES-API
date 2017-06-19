<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangePlacesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('places', function(Blueprint $table) {

            $table->dropColumn('category_id');

            //Category
            $table->boolean('cerimony')->nullable()->after('user_id');
            $table->boolean('party_space')->nullable()->after('cerimony');

            //Style
            $table->boolean('style_rustic')->nullable()->after('max_guests');
            $table->boolean('style_modern')->nullable()->after('style_rustic');
            $table->boolean('style_authentic')->nullable()->after('style_modern');

            // Localization
            $table->boolean('localization_city')->nullable()->after('style_authentic');
            $table->boolean('localization_countryside')->nullable()->after('localization_city');

            // Accessibility
            $table->boolean('accessibility')->nullable()->after('localization_countryside');

            //Parking
            $table->boolean('parking')->nullable()->after('accessibility');

            // Spaces
            $table->boolean('covered')->nullable()->after('parking');
            $table->boolean('outdoor')->nullable()->after('covered');


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
