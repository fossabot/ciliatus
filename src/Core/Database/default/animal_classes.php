<?php

return [
    'model' => 'Ciliatus\Core\Models\AnimalClass',
    'items' => [
        [
            'name' => trans('ciliatus.core::animal.class.reptile'),
            'litter_name' => trans('ciliatus.core::animal.litter.clutch'),
        ],
        [
            'name' => trans('ciliatus.core::animal.class.fish'),
            'litter_name' => trans('ciliatus.core::animal.litter.clutch'),
        ],
        [
            'name' => trans('ciliatus.core::animal.class.amphibious'),
            'litter_name' => trans('ciliatus.core::animal.litter.clutch'),
        ],
        [
            'name' => trans('ciliatus.core::animal.class.mammal'),
            'litter_name' => trans('ciliatus.core::animal.litter.litter'),
        ],
        [
            'name' => trans('ciliatus.core::animal.class.bird'),
            'litter_name' => trans('ciliatus.core::animal.litter.brood'),
        ]
    ]
];
