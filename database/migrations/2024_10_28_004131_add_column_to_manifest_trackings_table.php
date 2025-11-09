<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToManifestTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manifest_trackings', function (Blueprint $table) {
            $table->string('courier_name')->nullable();
            $table->string('courier_phone')->nullable();
            $table->string('proof')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manifest_trackings', function (Blueprint $table) {
            //
        });
    }
}
