<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payment_methods', function (Blueprint $table) {
            $table->uuid('id');
            $table->primary('id');
            $table->char('user_id', 36)->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->tinyInteger('provider')->default(1)->comment('1=CCavenue');
            $table->text('si_reference')->nullable()->comment('Card Unique Reference');
            $table->text('response_data')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1=Enabled,2=Disabled');
            $table->tinyInteger('default_card')->default(0)->comment('1=Default,0=Other');
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
        Schema::dropIfExists('user_payment_methods');
    }
}
