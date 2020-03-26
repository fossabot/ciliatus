<?php

namespace Ciliatus\Monitoring;

use Ciliatus\Common\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CiliatusMonitoringServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publish();
        $this->load();
        $this->authorize();
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

    private function authorize()
    {
        Gate::define('read-monitoring', function (User $user) {
            return $user->hasPermission('monitoring', 'read');
        });

        Gate::define('write-monitoring', function (User $user) {
            return $user->hasPermission('monitoring', 'write');
        });

        Gate::define('admin-monitoring', function (User $user) {
            return $user->hasPermission('monitoring', 'admin');
        });
    }

}
