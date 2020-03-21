<?php

namespace Ciliatus\Core\Console;

use Ciliatus\Core\Models\Habitat;
use Ciliatus\Core\Models\Location;
use Illuminate\Console\Command;

class RefreshMonitorCacheCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'ciliatus:core.refresh_monitor_cache';

    /**
     * @var string
     */
    protected $description = 'Refreshes the monitor cache on each habitat and location';

    /**
     *
     */
    public function handle()
    {
        echo "Refreshing habitat monitors" . PHP_EOL;

        Habitat::get()->each(function(Habitat $h) {
            $h->renderMonitor();
        });

        echo "Refreshing location monitor" . PHP_EOL;

        Location::get()->each(function(Location $l) {
            $l->renderMonitor();
        });
    }

}