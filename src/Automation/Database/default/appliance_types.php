<?php

return [
    'model' => 'Ciliatus\Automation\Models\ApplianceType',
    'items' => [
        [
            'name' => trans('ciliatus.automation::appliance.type.heat_lamp'),
            'model' => trans('ciliatus.common::misc.generic'),
            'vendor' => null,
            'protocol' => 'GPIO',
            'icon' => 'mdi-lightbulb-on'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.type.warm_lamp'),
            'model' => trans('ciliatus.common::misc.generic'),
            'vendor' => null,
            'protocol' => 'GPIO',
            'icon' => 'mdi-lightbulb-on-outline'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.type.cold_lamp'),
            'model' => trans('ciliatus.common::misc.generic'),
            'vendor' => null,
            'protocol' => 'GPIO',
            'icon' => 'mdi-lightbulb-cfl'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.type.ac_compressor'),
            'model' => trans('ciliatus.common::misc.generic'),
            'vendor' => null,
            'protocol' => 'GPIO',
            'icon' => 'mdi-air-conditioner'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.type.pump'),
            'model' => trans('ciliatus.common::misc.generic'),
            'vendor' => null,
            'protocol' => 'GPIO',
            'icon' => 'mdi-car-turbocharger'
        ],
        [
            'name' => trans('ciliatus.automation::appliance.type.fan'),
            'model' => trans('ciliatus.common::misc.generic'),
            'vendor' => null,
            'protocol' => 'GPIO',
            'icon' => 'mdi-fan'
        ],
    ],
    'attach' => [
        1 => [
            [
                'relation' => 'capabilities',
                'id' => 1
            ]
        ],
        2 => [
            [
                'relation' => 'capabilities',
                'id' => 1
            ],
            [
                'relation' => 'capabilities',
                'id' => 6
            ]
        ],
        3 => [
            [
                'relation' => 'capabilities',
                'id' => 6
            ]
        ],
        4 => [
            [
                'relation' => 'capabilities',
                'id' => 2
            ]
        ],
        6 => [
            [
                'relation' => 'capabilities',
                'id' => 2
            ],
            [
                'relation' => 'capabilities',
                'id' => 4
            ]
        ]
    ]
];
