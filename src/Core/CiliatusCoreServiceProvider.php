<?php

namespace Ciliatus\Core;

use Ciliatus\Core\Models\Animal;
use Ciliatus\Core\Observers\AnimalObserver;
use Illuminate\Support\ServiceProvider;

class CiliatusCoreServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->publish();
        $this->load();
        $this->observe();
    }

    private function load()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'ciliatus.core');
    }

    private function publish()
    {
        $this->publishes([
            __DIR__ . '/config.php' => config_path('ciliatus_core.php')
        ]);
    }

    private function observe()
    {
        Animal::observe(AnimalObserver::class);
    }

}
