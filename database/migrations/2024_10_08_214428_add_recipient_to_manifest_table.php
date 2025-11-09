<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecipientToManifestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manifests', function (Blueprint $table) {
            $table->string('recipient_company')->nullable();
            $table->longText('recipient_address')->nullable();
            $table->string('recipient_city')->nullable();
            $table->string('recipient_zip')->nullable();
            $table->string('recipient_name')->nullable();
            $table->string('recipient_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manifest', function (Blueprint $table) {
            //
        });
    }
}
