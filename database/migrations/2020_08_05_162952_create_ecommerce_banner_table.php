<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEcommerceBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ecommerce_banner', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('in_category')->default(0);
            $table->tinyInteger('in_product')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->uuid('by_user_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('banner_stores', function (Blueprint $table) {
            // $table->uuid('id')->primary();
            $table->uuid('banner_id')->index();
            $table->uuid('store_id')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('banner_id')->references('id')->on('ecommerce_banner')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banner_stores');
        Schema::dropIfExists('ecommerce_banner');
    }
}
