<?php

return [
    'model' => 'Ciliatus\Automation\Models\Capability',
    'items' => [
        [
            'name' => trans('ciliatus.automation::appliance.capability.heat'),
            'affected_metric_name' => 'temperature',
            'rises_affected_metric' => true,
            'lowers_affected_metric' => false,
            'icon' => 'mdi-weather-sunset-up'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.capability.cool'),
            'affected_metric_name' => 'temperature',
            'rises_affected_metric' => false,
            'lowers_affected_metric' => true,
            'icon' => 'mdi-snowflake'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.capability.irrigate'),
            'affected_metric_name' => 'humidity',
            'rises_affected_metric' => true,
            'lowers_affected_metric' => false,
            'icon' => 'mdi-weather-pouring'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.capability.ventilate'),
            'affected_metric_name' => 'humidity',
            'rises_affected_metric' => false,
            'lowers_affected_metric' => true,
            'icon' => 'mdi-weather-windy'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.capability.shade'),
            'affected_metric_name' => 'light_intensity',
            'rises_affected_metric' => false,
            'lowers_affected_metric' => true,
            'icon' => 'mdi-weather-cloudy'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.capability.light_source'),
            'affected_metric_name' => 'light_intensity',
            'rises_affected_metric' => true,
            'lowers_affected_metric' => false,
            'icon' => 'mdi-weather-sunny'
        ]
    ]
];
