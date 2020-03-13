<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__workflow_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appliance_id');
            $table->unsignedBigInteger('appliance_type_state_id');
            $table->unsignedBigInteger('workflow_id');

            $table->string('name');
            $table->boolean('is_active')->nullable()->default(true);
            $table->integer('workflow_time_offset_seconds');
            $table->double('target_level')->nullable();
            $table->double('target_level_rampup_seconds')->nullable()->default(0);

            $table->timestamps();
        });

        Schema::table('ciliatus_automation__workflow_actions', function (Blueprint $table) {
            $table->foreign('appliance_id', 'desired_state__appliance_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliances');

            $table->foreign('appliance_type_state_id', 'desired_state__appliance_type_state_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliance_type_states');

            $table->foreign('workflow_id', 'desired_state__workflow_foreign')
                ->references('id')
                ->on('ciliatus_automation__workflows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_automation__desired_states');
    }
}
