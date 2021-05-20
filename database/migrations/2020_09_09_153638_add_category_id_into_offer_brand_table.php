<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryIdIntoOfferBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offer_brand', function (Blueprint $table) {
            $table->uuid('category_id')->after('id')->index();
            $table->foreign('category_id')->references('id')->on('offer_category')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offer_brand', function (Blueprint $table) {
            //
        });
    }
}
