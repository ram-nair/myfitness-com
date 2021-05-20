<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('brand_id')->index();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->longText('redeem_text')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('purchase_validity');
            $table->integer('coupon_code')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->uuid('by_user_id');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('offer_brand')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer');
    }
}
