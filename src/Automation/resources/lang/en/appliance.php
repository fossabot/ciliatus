<?php

return [
    'state_text' => [
        'error' => [
            'invalid_state' => 'Tried to set invalid state for this appliance',
            'null_state' => 'Tried to set null state'
        ]
    ],
    'capability' => [
        'heat' => 'Heat',
        'cool' => 'Cool',
        'irrigate' => 'Irrigate',
        'ventilate' => 'Ventilate',
        'shade' => 'Shade',
        'light_source' => 'Light source'
    ],
    'type' => [
        'heat_lamp' => 'Heat lamp',
        'warm_lamp' => 'Lamp (warm)',
        'cold_lamp' => 'Lamp (cold)',
        'ac_compressor' => 'AC compressor',
        'pump' => 'Pump',
        'fan' => 'Fan'
    ],
    'type_states' => [
        'running_binary' => 'Running (on)',
        'running_variable' => 'Running (variable)',
        'stopped' => 'Stopped',
    ],
    'group' => [
        'ungrouped' => 'Ungrouped'
    ]
];