<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCapabilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__capabilities', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('icon')->nullable()->default('help');
            $table->string('affected_metric_name');
            $table->boolean('lowers_affected_metric')->nullable()->default(false);
            $table->boolean('rises_affected_metric')->nullable()->default(false);

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
        Schema::dropIfExists('ciliatus_automation__capabilities');
    }
}
