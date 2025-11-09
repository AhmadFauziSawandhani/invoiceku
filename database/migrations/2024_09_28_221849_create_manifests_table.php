<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->date('date_manifest')->nullable();
            $table->bigInteger('invoice_type');
            $table->string('sales_name')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('shipping_name')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('destination')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('receipt_number')->nullable();
            $table->string('moda')->nullable();
            $table->string('drop_pickup')->nullable();
            $table->string('photo_product')->nullable();
            $table->string('photo_travel_document')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('manifests');
    }
}
