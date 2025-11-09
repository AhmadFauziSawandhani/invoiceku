<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifestProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifest_products', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('manifest_id')->unsigned();
            $table->string('product_name');
            $table->float('colly');
            $table->float('weight');
            $table->float('dimension_p');
            $table->float('dimension_l');
            $table->float('dimension_t');
            $table->float('volume')->nullable();
            $table->float('volume_m3')->nullable();
            $table->float('actual')->nullable();
            $table->float('chargeable_weight')->nullable();
            $table->float('packaging')->nullable();
            $table->float('charge_packaging')->nullable();
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
        Schema::dropIfExists('manifest_products');
    }
}
