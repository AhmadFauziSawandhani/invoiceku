<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToManifestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manifests', function (Blueprint $table) {
            $table->float('total_colly')->nullable();
            $table->float('total_weight')->nullable();
            $table->float('total_volume')->nullable();
            $table->float('total_volume_m3')->nullable();
            $table->float('total_actual')->nullable();
            $table->float('total_chargeable_weight')->nullable();
            $table->float('total_charge_packaging')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manifests', function (Blueprint $table) {
            //
        });
    }
}
