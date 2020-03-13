<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_core__animals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('animal_species_id');
            $table->unsignedBigInteger('animal_litter_id')->nullable();
            $table->unsignedBigInteger('habitat_id')->nullable();
            $table->unsignedBigInteger('breeding_with_animal_id')->nullable();
            $table->unsignedBigInteger('breeding_pair_id')->nullable();

            $table->string('name');
            $table->string('sex')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('generation')->default(0);

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
        Schema::dropIfExists('ciliatus_core__animals');
    }
}
