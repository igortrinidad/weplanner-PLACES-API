<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CrColOriginalValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promotional_dates', function (Blueprint $table) {
            $table->decimal('original_value', 15, 2)->after('title');
        });
    }

    public function down()
    {
        Schema::table('promotional_dates', function (Blueprint $table) {
            $table->dropColumn('original_value');
        });
    }
}
