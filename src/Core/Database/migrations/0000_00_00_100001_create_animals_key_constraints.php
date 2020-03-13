<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalsKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ciliatus_core__animals', function (Blueprint $table) {
            $table->foreign('animal_species_id', 'animals__animal_species_foreign')
                ->references('id')
                ->on('ciliatus_core__animal_species');

            $table->foreign('animal_litter_id', 'animals__animal_litter_foreign')
                ->references('id')
                ->on('ciliatus_core__animal_litters');

            $table->foreign('habitat_id', 'animals__habitat_foreign')
                ->references('id')
                ->on('ciliatus_core__habitats');

            $table->foreign('breeding_with_animal_id', 'animals__breeding_with_animal_foreign')
                ->references('id')
                ->on('ciliatus_core__animals');

            $table->foreign('breeding_pair_id', 'animals__breeding_pair_foreign')
                ->references('id')
                ->on('ciliatus_core__animal_breeding_pairs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('ciliatus_core__animals', function (Blueprint $table) {
            $table->dropForeign('animal_species_id');
            $table->dropForeign('animal_litter_id');
            $table->dropForeign('habitat_id');
            $table->dropForeign('breeding_with_animal_id');
            $table->dropForeign('breeding_pair_id');
        });
    }
}
