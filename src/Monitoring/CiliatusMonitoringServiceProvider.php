<?php

namespace Ciliatus\Monitoring;

use Illuminate\Support\ServiceProvider;

class CiliatusMonitoringServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publish();
        $this->load();
    }

    private function load()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'ciliatus.monitoring');
    }

    private function publish()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('ciliatus_monitoring.php')
        ]);
    }

}
