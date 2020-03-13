<?php

namespace Ciliatus\Common\Console;

use Ciliatus\Common\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class SetupCommand extends Command
{

    protected $signature = 'ciliatus:setup {--db-only}';

    protected $description = 'Initial Setup';

    public function handle()
    {
        if (!$this->option('db-only')) {
            echo '# Setting up authentication provider ...' . PHP_EOL;
            Artisan::call('vendor:publish --provider="Laravel\Airlock\AirlockServiceProvider"');
            Artisan::call('ui vue --auth');
        }

        echo '# Migrating database ...' . PHP_EOL;
        Artisan::call('migrate');

        echo '# Setting up defaults ...' . PHP_EOL;
        $defaults = [
            'Core' => [
                'location_types',
                'habitat_types',
                'animal_classes',
                'animal_species'
            ],
            'Monitoring' => [
                'physical_sensor_types',
                'logical_sensor_types'
            ],
            'Automation' => [
                'appliance_capabilities',
                'appliance_types',
                'appliance_type_states'
            ]
        ];

        foreach ($defaults as $namespace=>$files) {
            foreach ($files as $file) {
                $default = include __DIR__ . '/../../' . $namespace . '/Database/default/' . $file . '.php';
                echo "Creating " . $file . " ..." . PHP_EOL;
                $class = $default['model'];
                if (isset($default['items'])) {
                    foreach ($default['items'] as $item) {
                        $class::create($item);
                    }                    
                }
                
                if (isset($default['attach'])) {
                    foreach ($default['attach'] as $id=>$relations) {
                        foreach ($relations as $attach) {
                            $model = $default['model']::find($id);
                            $relation = $attach['relation'];
                            $model->$relation()->attach($attach['id']);
                        }
                    }
                }
            }
        }

        $password = 'a'; #Str::random(10);
        $user = [
            'name' => 'Admin',
            'email' => 'a@a.aa', #' Str::random(6) . '@ciliatus.io',
            'password' => bcrypt($password)
        ];

        User::create($user);

        echo "################################" . PHP_EOL;
        echo "######### Initial User #########" . PHP_EOL;
        echo "# Username: " . $user['email'] . " #" . PHP_EOL;
        echo "# Password: " . $password . " #########" . PHP_EOL;
        echo "################################" . PHP_EOL;

        echo "Setup done" . PHP_EOL;
        echo "If you wish to seed demo data run: php artisan ciliatus:seed && php artisan ciliatus:sensor_reading_seed" . PHP_EOL;
    }

}
