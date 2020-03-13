<?php

namespace Ciliatus\Api;

use Illuminate\Support\ServiceProvider;

class CiliatusApiServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->load();
    }

    private function load()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

}
