<?php

return [
    'class' => [
        'reptile' => 'Reptile',
        'mammal' => 'Mammal',
        'fish' => 'Fish',
        'amphibious' => 'Amphibious'
    ],
    'species' => [
        'rhacodactylus' => [
            'ciliatus' => 'Crested Gecko',
            'leachianus' => 'New Caledonian giant gecko',
            'auriculatus' => 'Gargoyle Gecko'
        ]
    ],
    'litter' => [
        'clutch' => 'Clutch',
        'brood' => 'Brood',
        'litter' => 'Litter'
    ],
    'events' => [
        'created' => 'Animal was created',
        'breeding' => [
            'started' => 'Started breeding with {{0}}',
            'ended' => 'Stopped breeding with {{0}}',
            'offspring' => '{{0}} and {{1}} got offspring {{2}}',
            'birth' => 'Offspring of {{0}} and {{1}}',
            'offspring_with' => 'Got offspring {{0}} with {{1}}'
        ]
    ]
];
