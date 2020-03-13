<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitatsKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ciliatus_core__habitats', function (Blueprint $table) {
            $table->foreign('habitat_type_id', 'habitat__habitat_type_foreign')
                ->references('id')
                ->on('ciliatus_core__habitat_types');

            $table->foreign('location_id', 'habitat__location_foreign')
                ->references('id')
                ->on('ciliatus_core__locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ciliatus_core__habitats', function (Blueprint $table) {
            $table->dropForeign('habitat_type_id');
            $table->dropForeign('location_id');
        });
    }
}
