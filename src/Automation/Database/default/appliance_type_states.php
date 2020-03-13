<?php

return [
    'model' => 'Ciliatus\Automation\Models\ApplianceTypeState',
    'items' => [
        [
            'appliance_type_id' => 1,
            'name' => trans('ciliatus.automation::appliance.type_states.running_binary'),
            'icon' => 'mdi-play-circle-outline',
            'is_appliance_on' => true,
            'has_level' => false
        ],
        [
            'appliance_type_id' => 1,
            'name' => trans('ciliatus.automation::appliance.type_states.stopped'),
            'icon' => 'mdi-close-circle-outline',
            'is_appliance_on' => false,
            'has_level' => false
        ],
        [
            'appliance_type_id' => 2,
            'name' => trans('ciliatus.automation::appliance.type_states.running_binary'),
            'icon' => 'mdi-play-circle-outline',
            'is_appliance_on' => true,
            'has_level' => false
        ],
        [
            'appliance_type_id' => 2,
            'name' => trans('ciliatus.automation::appliance.type_states.stopped'),
            'icon' => 'mdi-close-circle-outline',
            'is_appliance_on' => false,
            'has_level' => false
        ],
        [
            'appliance_type_id' => 3,
            'name' => trans('ciliatus.automation::appliance.type_states.running_binary'),
            'icon' => 'mdi-play-circle-outline',
            'is_appliance_on' => true,
            'has_level' => false
        ],
        [
            'appliance_type_id' => 3,
            'name' => trans('ciliatus.automation::appliance.type_states.stopped'),
            'icon' => 'mdi-close-circle-outline',
            'is_appliance_on' => false,
            'has_level' => false
        ],
        [
            'appliance_type_id' => 4,
            'name' => trans('ciliatus.automation::appliance.type_states.running_variable'),
            'icon' => 'mdi-play-circle-outline',
            'is_appliance_on' => true,
            'has_level' => true
        ],
        [
            'appliance_type_id' => 4,
            'name' => trans('ciliatus.automation::appliance.type_states.stopped'),
            'icon' => 'mdi-close-circle-outline',
            'is_appliance_on' => false,
            'has_level' => false
        ],
        [
            'appliance_type_id' => 5,
            'name' => trans('ciliatus.automation::appliance.type_states.running_variable'),
            'icon' => 'mdi-play-circle-outline',
            'is_appliance_on' => true,
            'has_level' => true
        ],
        [
            'appliance_type_id' => 5,
            'name' => trans('ciliatus.automation::appliance.type_states.stopped'),
            'icon' => 'mdi-close-circle-outline',
            'is_appliance_on' => false,
            'has_level' => false
        ],
        [
            'appliance_type_id' => 6,
            'name' => trans('ciliatus.automation::appliance.type_states.running_variable'),
            'icon' => 'mdi-play-circle-outline',
            'is_appliance_on' => true,
            'has_level' => true
        ],
        [
            'appliance_type_id' => 6,
            'name' => trans('ciliatus.automation::appliance.type_states.stopped'),
            'icon' => 'mdi-close-circle-outline',
            'is_appliance_on' => false,
            'has_level' => false
        ]
    ]
];
