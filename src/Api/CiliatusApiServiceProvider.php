<?php

namespace Ciliatus\Api;

use Ciliatus\Common\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CiliatusApiServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->load();
        $this->authorize();
    }

    private function load()
    {
        $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
    }

    private function authorize()
    {
        Gate::define('read-api', function (User $user) {
            return $user->hasPermission('Api', 'read');
        });

        Gate::define('write-api', function (User $user) {
            return $user->hasPermission('Api', 'write');
        });

        Gate::define('admin-api', function (User $user) {
            return $user->hasPermission('Api', 'admin');
        });
    }

}
