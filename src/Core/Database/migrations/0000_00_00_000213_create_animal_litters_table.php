<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalLittersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_core__animal_litters', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('species_name');

            $table->timestamp('conception_at');
            $table->timestamp('birth_at');
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
        Schema::dropIfExists('ciliatus_core__animal_litters');
    }
}
