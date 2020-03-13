<?php

namespace Ciliatus\Automation\Listeners;

use Ciliatus\Automation\Models\WorkflowExecution;
use Ciliatus\Common\Events\Event;
use Ciliatus\Common\Listeners\Listener;

class WorkflowRuntimeExceededEventListener extends Listener
{

    public function handle(Event $event): void
    {
        /** @var WorkflowExecution $workflow_execution */
        $workflow_execution = $event->model;

        if ($event->is_critical) {
            $workflow_execution->alertCritical($event->text);
        } else {
            $workflow_execution->alertWarning($event->text);
        }

    }

}