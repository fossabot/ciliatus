<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowCapabilityPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__workflow_capability_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('workflow_id');
            $table->unsignedBigInteger('capability_id');
        });

        Schema::table('ciliatus_automation__workflow_capability_pivot', function (Blueprint $table) {
            $table->foreign('workflow_id', 'workflow_capability__workflow_foreign')
                ->references('id')
                ->on('ciliatus_automation__workflows');

            $table->foreign('capability_id', 'workflow_capability__capability_foreign')
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
        Schema::dropIfExists('ciliatus_automation__workflow_capability_pivot');
    }
}
