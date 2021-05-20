<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('brand_id')->nullable();
            $table->string('name');
            $table->string('sku')->unique()->index();
            $table->text('description')->nullable();
            $table->foreignId('category_id')->index();
            $table->foreignId('sub_category_id')->index();
            $table->double('unit_price', 8, 2);
            $table->string('quantity')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('featured')->default(0);
            $table->uuid('by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('sub_category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('by_user_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
