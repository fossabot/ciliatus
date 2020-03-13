<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogicalSensorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_monitoring__logical_sensor_types', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name')->unique();
            $table->string('icon');

            $table->double('reading_minimum')->nullable();
            $table->double('reading_maximum')->nullable();

            $table->string('reading_type_name'); //temperature, humidity, ...
            $table->string('reading_type_unit'); //celsius, percent, ...
            $table->string('reading_type_symbol'); //°C, %, ...

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
        Schema::dropIfExists('ciliatus_monitoring__logical_sensor_types');
    }
}
