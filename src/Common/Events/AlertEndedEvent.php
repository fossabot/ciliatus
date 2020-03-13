<?php

namespace Ciliatus\Common\Events;

class AlertEndedEvent extends Event implements EventInterface
{

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'Common.AlertEndedEvent';
    }

}