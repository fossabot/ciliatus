<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowActionExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__workflow_action_executions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workflow_execution_id');
            $table->unsignedBigInteger('workflow_action_id');

            $table->integer('workflow_time_offset_seconds');
            $table->double('target_level')->nullable();
            $table->double('target_level_rampup_seconds')->nullable()->default(0);

            $table->boolean('is_completed')->nullable()->default(false);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('status')->nullable()->default('draft');
            $table->string('status_text')->nullable();

            $table->unsignedBigInteger('claimed_by_controlunit_id');
            $table->timestamp('claimed_at');

            $table->timestamps();
        });

        Schema::table('ciliatus_automation__workflow_action_executions', function (Blueprint $table) {
            $table->foreign('workflow_execution_id', 'workflow_action_executions__workflow_execution_foreign')
                ->references('id')
                ->on('ciliatus_automation__workflow_executions');

            $table->foreign('workflow_action_id', 'workflow_action_executions__workflow_action_foreign')
                ->references('id')
                ->on('ciliatus_automation__workflow_actions');

            $table->foreign('claimed_by_controlunit_id', 'workflow_action_executions__controlunit_foreign')
                ->references('id')
                ->on('ciliatus_automation__controlunits');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_automation__workflow_action_executions');
    }
}
