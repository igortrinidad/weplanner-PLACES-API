<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColumnsPlaceCalendar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('place_calendar_settings', function(Blueprint $table) {
            $table->boolean('calendar_is_public')->after('available_days_config')->default(0);
            $table->boolean('calendar_is_active')->after('available_days_config')->default(0);
            $table->boolean('workday_is_active')->after('calendar_is_public')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('place_calendar_settings', function(Blueprint $table) {
            $table->dropColumn('calendar_is_public');
            $table->dropColumn('calendar_is_active');
            $table->dropColumn('workday_is_active');
        });
    }
}
