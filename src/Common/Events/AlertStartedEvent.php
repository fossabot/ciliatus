<?php

namespace Ciliatus\Common\Events;

class AlertStartedEvent extends Event implements EventInterface
{

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'Common.AlertStartedEvent';
    }

}