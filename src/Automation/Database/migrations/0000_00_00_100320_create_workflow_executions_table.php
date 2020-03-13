<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowExecutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__workflow_executions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workflow_id');

            $table->boolean('is_ready_to_start')->nullable()->default(false);
            $table->boolean('is_completed')->nullable()->default(false);
            $table->timestamp('fetched_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->string('status')->nullable()->default('draft');
            $table->string('status_text')->nullable();
            $table->timestamp('expected_completion_at')->nullable();
            $table->integer('expected_runtime_seconds')->nullable();
            $table->integer('actual_runtime_seconds')->nullable();
            $table->integer('runtime_exceedance_warn')->nullable()->default(0);
            $table->integer('runtime_exceedance_crit')->nullable()->default(0);
            $table->boolean('has_runtime_exceedance_warn_alerted')->nullable()->default(false);
            $table->boolean('has_runtime_exceedance_crit_alerted')->nullable()->default(false);

            $table->timestamp('sync_at')->nullable();
            $table->integer('sync_timeout_seconds')->nullable();

            $table->timestamps();
        });

        Schema::table('ciliatus_automation__workflow_executions', function (Blueprint $table) {
            $table->foreign('workflow_id', 'workflow_executions__workflow_foreign')
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
        Schema::dropIfExists('ciliatus_automation__workflow_executions');
    }
}
