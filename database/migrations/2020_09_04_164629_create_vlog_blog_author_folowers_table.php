<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVlogBlogAuthorFolowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vlog_blog_author_followers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('author_id')->index();
            $table->uuid('user_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('author_id')->references('id')->on('vlog_blog_author')->onDelete('cascade');
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
        Schema::dropIfExists('vlog_blog_author_followers');
    }
}
