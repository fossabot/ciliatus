<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplianceGroupCapabilityPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__appliance_group_capability_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appliance_group_id');
            $table->unsignedBigInteger('capability_id');
        });

        Schema::table('ciliatus_automation__appliance_group_capability_pivot', function (Blueprint $table) {
            $table->foreign('appliance_group_id', 'appliance_group_capability__appliance_group_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliance_groups');

            $table->foreign('capability_id', 'appliance_group_capability__capability_foreign')
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
        Schema::dropIfExists('ciliatus_automation__appliance_group_capability_pivot');
    }
}
