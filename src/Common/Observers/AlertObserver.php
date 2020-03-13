<?php

namespace Ciliatus\Common\Observers;


use Ciliatus\Common\Enum\AlertEventsEnum;
use Ciliatus\Common\Events\AlertStartedEvent;
use Ciliatus\Common\Models\Alert;
use Ciliatus\Common\Traits\HasHistoryTrait;
use ReflectionObject;

class AlertObserver
{

    /**
     * @param Alert $alert
     */
    public function created(Alert $alert)
    {
        if (in_array(HasHistoryTrait::class, (new ReflectionObject($alert))->getTraitNames())) {
            $alert->writeHistory(AlertEventsEnum::ALERT_STARTED(), [], $alert->started_at);
        }

        event(new AlertStartedEvent($alert));
    }

}
