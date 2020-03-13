<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogicalSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_monitoring__logical_sensors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('logical_sensor_type_id');
            $table->unsignedBigInteger('physical_sensor_id');

            $table->string('name');
            $table->double('reading_correction')->nullable();
            $table->double('current_reading_raw')->nullable();
            $table->double('current_reading_corrected')->nullable();
            $table->boolean('is_in_batch_mode')->nullable()->default(false);
            $table->string('state')->nullable()->default('unknown');
            $table->string('state_text')->nullable()->default('');

            $table->timestamps();
        });

        Schema::table('ciliatus_monitoring__logical_sensors', function (Blueprint $table) {
            $table->foreign('logical_sensor_type_id', 'logical_sensor__logical_sensor_type_foreign')
                ->references('id')
                ->on('ciliatus_monitoring__logical_sensor_types');

            $table->foreign('physical_sensor_id', 'logical_sensor__physical_sensor_foreign')
                ->references('id')
                ->on('ciliatus_monitoring__physical_sensors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_monitoring__logical_sensors');
    }
}
