<?php

namespace Ciliatus\Automation\Console;

use Ciliatus\Automation\Models\Appliance;
use Ciliatus\Monitoring\Jobs\CalculateMaintenanceJob;
use Illuminate\Console\Command;

class CalculateMaintenanceCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'ciliatus:automation.calculate_maintenance';

    /**
     * @var string
     */
    protected $description = 'Calculates next appliance maintenances';

    /**
     *
     */
    public function handle()
    {
        Appliance::get()->each(function (Appliance $appliance) {
            dispatch(new CalculateMaintenanceJob($appliance));
        });
    }

}