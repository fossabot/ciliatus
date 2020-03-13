<?php

namespace Ciliatus\Common;

use Ciliatus\Common\Console\SeedCommand;
use Ciliatus\Common\Console\SensorReadingSeedCommand;
use Ciliatus\Common\Console\SetupCommand;
use Ciliatus\Common\Models\Alert;
use Ciliatus\Common\Observers\AlertObserver;
use Illuminate\Support\ServiceProvider;

class CiliatusCommonServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->load();
        $this->schedule();
        $this->observe();
    }

    private function load()
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
    }

    private function schedule()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupCommand::class,
                SeedCommand::class,
                SensorReadingSeedCommand::class
            ]);
        }
    }

    private function observe()
    {
        Alert::observe(AlertObserver::class);
    }

}
