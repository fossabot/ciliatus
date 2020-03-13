<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_core__locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('location_type_id');

            $table->string('name')->unique();
            $table->boolean('is_monitor_refresh_queued')->default(false);
            $table->boolean('is_active')->default(true);

            $table->timestamp('last_monitor_refresh_at')->nullable()->default(null);
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
        Schema::dropIfExists('ciliatus_core__locations');
    }
}
