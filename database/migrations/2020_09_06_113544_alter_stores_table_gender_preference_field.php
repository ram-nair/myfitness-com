<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoresTableGenderPreferenceField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->tinyInteger('male')->after('on_my_location_charge')->default(0)->comment("1 Enabled");
            $table->tinyInteger('female')->after('male')->default(0)->comment("1 Enabled");
            $table->tinyInteger('any')->after('female')->default(0)->comment("1 Enabled");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stores', function (Blueprint $table) {
            //
        });
    }
}
