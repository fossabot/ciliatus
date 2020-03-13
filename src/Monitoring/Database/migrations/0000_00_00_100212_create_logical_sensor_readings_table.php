<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogicalSensorReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_monitoring__logical_sensor_readings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('logical_sensor_id');

            $table->double('reading_raw');
            $table->double('reading_corrected');
            $table->double('reading_applied_correction')->nullable();

            $table->timestamp('read_at')->useCurrent();
            $table->timestamps();
        });

        Schema::table('ciliatus_monitoring__logical_sensor_readings', function (Blueprint $table) {
            $table->foreign('logical_sensor_id', 'logical_sensor_type__logical_sensor_foreign')
                ->references('id')
                ->on('ciliatus_monitoring__logical_sensors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_monitoring__logical_sensor_readings');
    }
}
