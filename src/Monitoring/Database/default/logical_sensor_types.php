<?php

return [
    'model' => 'Ciliatus\Monitoring\Models\LogicalSensorType',
    'items' => [
        [
            'name' => trans('ciliatus.monitoring::logical_sensor.name.temperature'),
            'icon' => 'mdi-thermometer',
            'reading_minimum' => -50,
            'reading_maximum' => 100,
            'reading_type_name' => 'temperature',
            'reading_type_unit' => 'celsius',
            'reading_type_symbol' => '°C'
        ],
        [
            'name' => trans('ciliatus.monitoring::logical_sensor.name.humidity'),
            'icon' => 'mdi-water-percent',
            'reading_minimum' => 0,
            'reading_maximum' => 100,
            'reading_type_name' => 'humidity',
            'reading_type_unit' => 'percent',
            'reading_type_symbol' => '%'
        ],
        [
            'name' => trans('ciliatus.monitoring::logical_sensor.name.light_intensity'),
            'icon' => 'mdi-lightbulb-on-outline',
            'reading_minimum' => 0,
            'reading_maximum' => 1000000,
            'reading_type_name' => 'light_intensity',
            'reading_type_unit' => 'lumen',
            'reading_type_symbol' => 'lm'
        ],
    ]
];
