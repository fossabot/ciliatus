<?php

return [
    'events' => [
        'start' => [
            'not_ready' => 'Tried to start but workflow is not ready'
        ],
        'executions' => [
            'ready' => 'Workflow Execution ready',
            'created' => 'Workflow Execution created',
            'completed' => 'Workflow Execution completed',
            'error' => 'Workflow Execution ended with error'
        ]
    ],
    'alerts' => [
        'executions' => [
            'runtime_exceeded' => 'Runtime prediction exceeded'
        ]
    ]
];