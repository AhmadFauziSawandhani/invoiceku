<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifestTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifest_trackings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('manifest_id')->unsigned();
            $table->string('vendor_type')->nullable();
            $table->bigInteger('vendor_id')->nullable();
            $table->string('vendor_name')->nullable();
            $table->string('resi_vendor')->nullable();
            $table->string('status')->nullable();
            $table->longText('note')->nullable();
            $table->dateTime('tracking_date')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manifest_trackings');
    }
}
