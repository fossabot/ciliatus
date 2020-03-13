<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplianceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__appliance_types', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('icon')->nullable()->default('help');
            $table->string('model');
            $table->string('vendor')->nullable();
            $table->string('protocol');
            $table->double('level_minimum')->nullable();
            $table->double('level_maximum')->nullable();
            $table->double('level_step_size')->nullable()->default(1);

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
        Schema::dropIfExists('ciliatus_automation__appliance_types');
    }
}
