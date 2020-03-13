<?php

namespace Ciliatus\Automation;

use Ciliatus\Automation\Events\ApplianceErrorEvent;
use Ciliatus\Automation\Events\ApplianceRecoveryEvent;
use Ciliatus\Automation\Events\WorkflowExecutionRuntimeExceededEvent;
use Ciliatus\Automation\Listeners\ApplianceErrorEventListener;
use Ciliatus\Automation\Listeners\WorkflowRuntimeExceededEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider;

class CiliatusAutomationEventServiceProvider extends EventServiceProvider
{

    /**
     * @var array
     */
    protected $listen = [
        ApplianceErrorEvent::class => [
            ApplianceErrorEventListener::class
        ],

        ApplianceRecoveryEvent::class => [

        ],

        WorkflowExecutionRuntimeExceededEvent::class => [
            WorkflowRuntimeExceededEventListener::class
        ]
    ];

}