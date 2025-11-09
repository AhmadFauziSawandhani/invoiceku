<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryShippingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delivery_shippings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('delivery_id')->unsigned();
            $table->bigInteger('shipping_id')->unsigned();
            $table->string('resi_shipping')->nullable();
            $table->string('destination')->nullable();
            $table->float('colly')->nullable();
            $table->float('weight')->nullable();
            $table->float('chargeable_weight')->nullable();
            $table->float('volume')->nullable();
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
        Schema::dropIfExists('delivery_shippings');
    }
}
