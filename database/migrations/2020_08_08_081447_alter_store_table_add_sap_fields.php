<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterStoreTableAddSapFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stores', function (Blueprint $table) {
            //new fields
            $table->string('sap_id')->after('on_my_location_charge')->nullable();
            $table->double('service_charge', 8, 2)->after('sap_id')->nullable();
            $table->double('payment_charge', 8, 2)->after('service_charge')->nullable();
            $table->double('payment_charge_store_dividend', 8, 2)->after('payment_charge')->nullable();
            $table->double('payment_charge_provis_dividend', 8, 2)->after('payment_charge_store_dividend')->nullable();
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
