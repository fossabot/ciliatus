<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHabitatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_core__habitats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('habitat_type_id');
            $table->unsignedBigInteger('location_id');

            $table->string('name')->unique();
            $table->boolean('is_monitor_refresh_queued')->nullable()->default(false);
            $table->boolean('is_active')->nullable()->default(true);
            $table->boolean('is_alert_broadcasting_enabled_warning')->nullable()->default(false);
            $table->boolean('is_alert_broadcasting_enabled_critical')->nullable()->default(false);

            $table->integer('width')->nullable()->default(80);
            $table->integer('height')->nullable()->default(80);
            $table->integer('depth')->nullable()->default(80);

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
        Schema::dropIfExists('ciliatus_core__habitats');
    }
}
