<?php

namespace Ciliatus\Common\Listeners;

use Ciliatus\Common\Events\Event;

interface ListenerInterface
{

    public function handle(Event $event): void;

}