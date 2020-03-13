<?php

return [
    'model' => 'Ciliatus\Monitoring\Models\PhysicalSensorType',
    'items' => [
        [
            'model' => 'AM2302',
            'vendor' => null,
            'protocol' => '1-wire bus'
        ],
        [
            'model' => 'BME280',
            'vendor' => 'Bosch',
            'protocol' => 'I2C'
        ]
    ]
];
