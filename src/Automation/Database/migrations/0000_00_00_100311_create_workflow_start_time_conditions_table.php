<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowStartTimeConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__workflow_start_time_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workflow_id');

            $table->string('name');
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('start_after_min_interval_asap')->nullable()->default(false);
            $table->time('start_at');

            $table->timestamps();
        });

        Schema::table('ciliatus_automation__workflow_start_time_conditions', function (Blueprint $table) {
            $table->foreign('workflow_id', 'workflow_start_time_conditions__workflow_foreign')
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
        Schema::dropIfExists('ciliatus_automation__workflow_start_time_conditions');
    }
}
