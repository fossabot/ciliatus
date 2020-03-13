<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalSensorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_monitoring__physical_sensor_types', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('model');
            $table->string('vendor')->nullable();
            $table->string('protocol');

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
        Schema::dropIfExists('ciliatus_monitoring__physical_sensor_types');
    }
}
