<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryShippingTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_shipping_trackings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('delivery_shipping_id')->unsigned();
            $table->string('status')->nullable();
            $table->longText('note')->nullable();
            $table->dateTime('tracking_date')->nullable();
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
        Schema::dropIfExists('delivery_shipping_trackings');
    }
}
