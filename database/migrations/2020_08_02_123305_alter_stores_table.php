<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            $table->string('location_type')->after('longitude')->nullable();
            $table->tinyInteger('my_location')->after('location_type')->default(0)->comment("1 Enabled");
            $table->tinyInteger('in_store')->after('my_location')->default(0)->comment("1 Enabled");
            $table->double('on_my_location_charge', 8, 2)->after('in_store')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('stores');
    }
}
