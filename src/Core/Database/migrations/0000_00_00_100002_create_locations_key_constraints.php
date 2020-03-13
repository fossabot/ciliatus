<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ciliatus_core__locations', function (Blueprint $table) {
            $table->foreign('location_type_id', 'location__location_type_foreign')
                ->references('id')
                ->on('ciliatus_core__location_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ciliatus_core__locations', function (Blueprint $table) {
            $table->dropForeign('location_type_id');
        });
    }
}
