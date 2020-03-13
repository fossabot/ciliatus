<?php

namespace Ciliatus\Automation;

use Ciliatus\Automation\Observers\HabitatObserver;
use Ciliatus\Automation\Console\CalculateMaintenanceCommand;
use Ciliatus\Core\Models\Habitat;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class CiliatusAutomationServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publish();
        $this->load();
        $this->observe();
        $this->providers();
        $this->schedule();
    }

    private function load()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'ciliatus.automation');
    }

    private function publish()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('ciliatus_automation.php')
        ]);
    }

    private function observe()
    {
        Habitat::observe(HabitatObserver::class);
    }

    private function providers()
    {
        $this->app->register(CiliatusAutomationEventServiceProvider::class);
    }

    private function schedule()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CalculateMaintenanceCommand::class
            ]);

            $this->app->booted(function () {
                $schedule = $this->app->make(Schedule::class);
                $schedule->command(CalculateMaintenanceCommand::class)->dailyAt('06:00');
            });
        }
    }

}
