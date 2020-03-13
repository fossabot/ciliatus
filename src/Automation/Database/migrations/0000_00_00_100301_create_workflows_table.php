<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__workflows', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('name');
            $table->integer('minimum_interval_between_executions_minutes')->nullable()->default(0);
            $table->integer('runtime_exceedance_warn')->nullable()->default(0);
            $table->integer('runtime_exceedance_crit')->nullable()->default(0);
            $table->boolean('is_running')->nullable()->default(false);
            $table->timestamp('last_run_started_at')->nullable()->default(null);
            $table->timestamp('last_run_ended_at')->nullable()->default(null);
            $table->boolean('is_active')->nullable()->default(true);

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
        Schema::dropIfExists('ciliatus_automation__workflows');
    }
}
