<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplianceTypeStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciliatus_automation__appliance_type_states', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('appliance_type_id');

            $table->string('name');
            $table->string('icon')->nullable()->default('help');
            $table->boolean('is_appliance_on')->nullable()->default(false);
            $table->boolean('has_level')->nullable()->default(false);

            $table->timestamps();
        });

        Schema::table('ciliatus_automation__appliance_type_states', function (Blueprint $table) {
            $table->foreign('appliance_type_id', 'appliance_type_state__appliance_type_id_foreign')
                ->references('id')
                ->on('ciliatus_automation__appliance_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciliatus_automation__appliance_type_states');
    }
}
