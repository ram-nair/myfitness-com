<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_brand', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('phone_number')->nullable();
            $table->time('working_start_hour')->nullable();
            $table->time('working_end_hour')->nullable();
            $table->longText('description')->nullable();
            $table->text('location')->nullable();
            $table->double('latitude', 15, 6)->nullable();
            $table->double('longitude', 15, 6)->nullable();
            $table->tinyInteger('status')->default(1);
            $table->uuid('by_user_id');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_brand');
    }
}
