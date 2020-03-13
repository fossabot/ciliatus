<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowStartMetricConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__workflow_start_metric_conditions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('logical_sensor_type_id');

            $table->string('name');
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('is_above')->nullable()->default(false);
            $table->boolean('is_below')->nullable()->default(false);
            $table->integer('in_state_amount');
            $table->integer('in_state_duration_minutes');
            $table->integer('in_state_bucket_size_minutes')->nullable()->default(1);
            $table->time('timeframe_start')->nullable()->default(null);
            $table->time('timeframe_end')->nullable()->default(null);

            $table->timestamps();
        });

        Schema::table('ciliatus_automation__workflow_start_metric_conditions', function (Blueprint $table) {
            $table->foreign('workflow_id', 'workflow_start_metric_conditions__workflow_foreign')
                ->references('id')
                ->on('ciliatus_automation__workflows');

            $table->foreign('logical_sensor_type_id', 'workflow_start_metric_conditions__logical_sensor_type_foreign')
                ->references('id')
                ->on('ciliatus_monitoring__logical_sensor_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_automation__workflow_start_metric_conditions');
    }
}
