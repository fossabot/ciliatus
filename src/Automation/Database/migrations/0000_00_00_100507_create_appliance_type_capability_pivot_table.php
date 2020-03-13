<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplianceTypeCapabilityPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__appliance_type_capability_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appliance_type_id');
            $table->unsignedBigInteger('capability_id');
        });

        Schema::table('ciliatus_automation__appliance_type_capability_pivot', function (Blueprint $table) {
            $table->foreign('appliance_type_id', 'appliance_type_capability__appliance_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliance_types');

            $table->foreign('capability_id', 'appliance_type_capability__capability_foreign')
                ->references('id')
                ->on('ciliatus_automation__capabilities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_automation__appliance_type_capability_pivot');
    }
}
