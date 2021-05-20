<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVlogBlogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vlog_blog', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('category_id')->index();
            $table->uuid('author_id')->index();
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('blog_type')->default('blog');
            $table->string('reading_minute');
            $table->tinyInteger('status')->default(1);
            $table->uuid('by_user_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('vlog_blog_category')->onDelete('cascade');
            $table->foreign('author_id')->references('id')->on('vlog_blog_author')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vlog_blog');
    }
}
