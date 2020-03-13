<?php

namespace Ciliatus\Automation\Listeners;

use Ciliatus\Automation\Models\Appliance;
use Ciliatus\Automation\Models\ApplianceGroup;
use Ciliatus\Automation\Models\WorkflowAction;
use Ciliatus\Automation\Models\WorkflowActionExecution;
use Ciliatus\Common\Events\Event;
use Ciliatus\Common\Listeners\Listener;

class ApplianceErrorEventListener extends Listener
{

    public function handle(Event $event): void
    {
        /** @var Appliance $appliance */
        $appliance = $event->model;

        $appliance->alertCritical($event->text);

        $appliance->groups->each(function(ApplianceGroup $group) {
            $group->error('Group member is in error state');
        });

        $appliance->actions->each(function(WorkflowAction $action) {
            $action->each(function(WorkflowActionExecution $execution) {
                $execution->error('Appliance is in error state');
            });
        });

    }

}