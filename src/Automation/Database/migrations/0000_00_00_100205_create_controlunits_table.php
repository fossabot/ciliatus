<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateControlunitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__controlunits', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('health')->nullable();

            $table->timestamp('last_checkin_at')->nullable();
            $table->timestamp('last_claim_at')->nullable();
            $table->timestamp('last_start_times_at')->nullable();
            $table->timestamp('last_config_at')->nullable();

            $table->string('client_version')->nullable();
            $table->timestamp('client_datetime')->nullable();
            $table->integer('client_datetime_offset_seconds')->nullable();

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
        Schema::dropIfExists('ciliatus_automation__controlunits');
    }
}
