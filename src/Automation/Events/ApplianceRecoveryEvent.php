<?php

namespace Ciliatus\Automation\Events;

use Ciliatus\Common\Events\EventInterface;

class ApplianceRecoveryEvent extends Event implements EventInterface
{

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'Automation.ApplianceRecoveryEvent';
    }

}