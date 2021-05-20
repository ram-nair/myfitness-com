<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('order_id')->unique()->index();
            $table->uuid('store_id')->index();
            // $table->uuid('cart_id')->index();
            $table->uuid('user_id')->index();
            $table->uuid('address_id')->index();
            $table->text('delivery_address')->nullable();
            $table->decimal('amount_exclusive_vat', 8, 2);
            $table->decimal('vat_amount', 8, 2);
            $table->string('vat_percentage');
            $table->double('total_amount', 8, 2);
            $table->string('payment_type');
            // $table->date('payment_date')->nullable();
            $table->string('order_type')->comment("Normal, Scheduled")->default("Normal");
            //$table->uuid('slot_id')->nullable();
            $table->text('scheduled_notes')->nullable();
            $table->tinyInteger('payment_status')->comment("0 Pending, 1 Completed, 2 Cancelled, 3 Failed")->default(0);
            $table->enum('order_status', ["submitted", "assigned", "out_for_delivery", "delivered", "cancelled"])->default("submitted");
            $table->dateTime('time_of_item_update')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('address_id')->references('id')->on('user_addresses')->onDelete('cascade');
            // $table->foreign('cart_id')->references('id')->on('carts')->onDelete('cascade');
            //$table->foreign('slot_id')->references('id')->on('slots')->onDelete('cascade');
           // $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
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
        Schema::dropIfExists('orders');
    }
}
