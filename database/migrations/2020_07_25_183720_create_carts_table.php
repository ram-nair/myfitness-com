<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->uuid('id')->primary();          
            $table->uuid('product_id')->index();
            $table->integer('quantity');
            $table->uuid('store_id')->index();
            $table->uuid('user_id')->index();
            // $table->string('product_name');
            // $table->double('unit_price', 8, 2);
            // $table->double('item_total', 8, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product_stores')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
