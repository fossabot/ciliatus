<?php

namespace Ciliatus\Common\Console;

use Carbon\Carbon;
use Ciliatus\Monitoring\Models\LogicalSensor;
use Ciliatus\Monitoring\Models\LogicalSensorType;
use Exception;
use Illuminate\Console\Command;

class SensorReadingSeedCommand extends Command
{

    /**
     * @var string
     */
    protected $signature = 'ciliatus:sensor_reading_seed';

    /**
     * @var string
     */
    protected $description = 'Seed sensor reading test data';

    /**
     * @throws Exception
     */
    public function handle()
    {
        $types = [
            'temperature' => [
                'unit' => 'celsius',
                'base' => 20,
                'factor' => 1,
                'random' => [-1, 1]
            ],
            'humidity' => [
                'unit' => 'percent',
                'base' => 50,
                'factor' => 6,
                'random' => [-2, 2]
            ]
        ];

        $now = Carbon::now();
        $start = (clone $now)->subDay();

        foreach ($types as $type_name => $settings) {
            $type = LogicalSensorType::where('name', trans('ciliatus.monitoring::logical_sensor.name.' . $type_name))->first();
            $type->logical_sensors->each(function(LogicalSensor $sensor) use ($now, $start, $settings) {
                echo 'Seeding ' . $sensor->name . ' ... ';
                $sensor->enterBatchMode();

                $cursor = clone $start;
                do {
                    $diff = $now->diffInMinutes($cursor);
                    $variable = $diff / 720 * pi();
                    $value = $settings['base'] + $variable * $settings['factor'] + random_int($settings['random'][0] * 100, $settings['random'][1] * 100) / 100;
                    $sensor->addReading($value, $cursor);
                    $cursor->addMinutes(5);
                } while ($cursor->isBefore($now));

                $sensor->endBatchMode();
                echo 'done' . PHP_EOL;
            });
        }

        echo "Seeding done" . PHP_EOL;
    }

}