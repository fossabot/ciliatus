<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_core__animal_relations', function (Blueprint $table) {
            $table->bigIncrements('id');

            /*
             * [animal_is_id] is [type] of [animal_of_id]
             */
            $table->unsignedBigInteger('animal_is_id');
            $table->string('relation_type');
            $table->unsignedBigInteger('animal_of_id');

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
        Schema::dropIfExists('ciliatus_core__animal_relations');
    }
}
