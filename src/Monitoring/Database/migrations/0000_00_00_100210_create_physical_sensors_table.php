<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePhysicalSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_monitoring__physical_sensors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('physical_sensor_type_id');
            $table->unsignedBigInteger('belongsToModel_id')->nullable();
            $table->string('belongsToModel_type')->nullable();

            $table->string('name')->unique();
            $table->string('state')->nullable()->default('unknown');
            $table->string('state_text')->nullable()->default('');

            $table->string('position_name')->default('default');
            $table->integer('position_x')->nullable();
            $table->integer('position_y')->nullable();
            $table->integer('position_z')->nullable();

            $table->string('health')->nullable();

            $table->timestamps();
        });

        Schema::table('ciliatus_monitoring__physical_sensors', function (Blueprint $table) {
            $table->foreign('physical_sensor_type_id', 'physical_sensor__physical_sensor_type_foreign')
                ->references('id')
                ->on('ciliatus_monitoring__physical_sensor_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_monitoring__physical_sensors');
    }
}
