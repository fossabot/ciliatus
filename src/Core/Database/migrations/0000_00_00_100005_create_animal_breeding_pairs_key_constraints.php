<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalBreedingPairsKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ciliatus_core__animal_breeding_pairs', function (Blueprint $table) {
            $table->foreign('animal_id', 'animal_breeding_pair__animal_foreign')
                ->references('id')
                ->on('ciliatus_core__animals');

            $table->foreign('partner_id', 'animal_breeding_pair__partner_foreign')
                ->references('id')
                ->on('ciliatus_core__animals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ciliatus_core__animal_breeding_pairs', function (Blueprint $table) {
            $table->dropForeign('animal_id');
            $table->dropForeign('partner_id');
        });
    }
}
