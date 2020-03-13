<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplianceApplianceGroupPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__appliance_appliance_group_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appliance_id');
            $table->unsignedBigInteger('appliance_group_id');
        });

        Schema::table('ciliatus_automation__appliance_appliance_group_pivot', function (Blueprint $table) {
            $table->foreign('appliance_id', 'appliance_appliance_group__appliance_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliances');

            $table->foreign('appliance_group_id', 'appliance_appliance_group__appliance_group_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliance_groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_automation__appliance_appliance_group_pivot');
    }
}
