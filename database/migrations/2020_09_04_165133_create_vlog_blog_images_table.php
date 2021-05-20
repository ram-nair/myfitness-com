<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVlogBlogImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vlog_blog_images', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('vb_id')->index();
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('upload_type')->default('image');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('vb_id')->references('id')->on('vlog_blog')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vlog_blog_images');
    }
}
