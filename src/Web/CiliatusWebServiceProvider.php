<?php

namespace Ciliatus\Web;

use Illuminate\Support\ServiceProvider;

class CiliatusWebServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->load();
    }

    private function load()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'ciliatus');
    }

}