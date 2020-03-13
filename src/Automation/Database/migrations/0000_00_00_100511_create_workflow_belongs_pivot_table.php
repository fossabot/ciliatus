<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowBelongsPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__workflow_belongs_pivot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('belongsToModel_type');
            $table->unsignedBigInteger('belongsToModel_id');
            $table->unsignedBigInteger('workflow_id');
        });

        Schema::table('ciliatus_automation__workflow_belongs_pivot', function (Blueprint $table) {
            $table->foreign('workflow_id', 'workflow_belongs__workflow_foreign')
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
        Schema::dropIfExists('ciliatus_automation__workflow_belongs_pivot');
    }
}
