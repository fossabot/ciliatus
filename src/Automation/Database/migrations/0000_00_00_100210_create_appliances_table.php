<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__appliances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appliance_type_id');
            $table->unsignedBigInteger('current_state_id')->nullable()->default(null);

            $table->string('name');
            $table->string('state')->nullable()->default('unknown');
            $table->string('state_text')->nullable()->default('');
            $table->boolean('is_active')->nullable()->default(true);
            $table->integer('maintenance_interval_days')->nullable();
            $table->integer('next_maintenance_due_days')->nullable();
            $table->date('last_maintenance_at')->nullable();
            $table->date('next_maintenance_due_at')->nullable();
            $table->boolean('is_active_on_conflict')->nullable()->default(false);
            $table->string('health')->nullable();

            $table->timestamps();
        });

        Schema::table('ciliatus_automation__appliances', function (Blueprint $table) {
            $table->foreign('appliance_type_id', 'appliance__appliance_type_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliance_types');

            $table->foreign('current_state_id', 'appliance__current_state_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliance_type_states');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_automation__appliances');
    }
}
