<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->tinyInteger('status')->nullable()->default(0)->comment('0=send 1=not send');
            $table->char('userId', 36)->nullable();
            $table->foreign('userId')->references('id')->on('users')->onDelete('cascade');
            $table->string('title', 255)->nullable();
            $table->text('message')->nullable();
            $table->text('data')->nullable();
            $table->integer('categoryId')->nullable();
            $table->integer('messageType')->nullable()->comment('1=Order,2=general');
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
        Schema::dropIfExists('notifications');
    }
}
