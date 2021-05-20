<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('business_type_id');
            $table->foreignId('business_type_category_id');
            $table->string('service_type')->nullable();
            $table->string('name');
            $table->string('email', 191)->unique();
            $table->string('mobile')->nullable();
            $table->string('password');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('store_timing')->nullable();
            $table->text('location');
            $table->tinyInteger('credit_card')->default(0);
            $table->tinyInteger('cash_accept')->default(0);
            $table->tinyInteger('bring_card')->default(0);
            $table->tinyInteger('featured')->default(0);
            $table->string('speed')->nullable();
            $table->string('accuracy')->nullable();
            $table->string('time_to_deliver')->nullable();
            $table->string('min_order_amount')->nullable();
            $table->double('latitude', 15, 6);
            $table->double('longitude', 15, 6);
            $table->tinyInteger('active')->default(1);
            $table->timestamp('email_verified_at')->nullable();
            $table->uuid('vendor_id');
            $table->uuidMorphs('by_user');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('business_type_id')->references('id')->on('business_types')->onDelete('cascade');
            $table->foreign('business_type_category_id')->references('id')->on('business_type_categories')->onDelete('cascade');
            $table->foreign('vendor_id')
                ->references('id')
                ->on('vendors')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
