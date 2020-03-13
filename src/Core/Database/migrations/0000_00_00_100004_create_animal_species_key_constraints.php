<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalSpeciesKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ciliatus_core__animal_species', function (Blueprint $table) {
            $table->foreign('animal_class_id', 'animal_species__animal_class_foreign')
                ->references('id')
                ->on('ciliatus_core__animal_classes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ciliatus_core__animal_species', function (Blueprint $table) {
            $table->dropForeign('animal_class_id');
        });
    }
}
