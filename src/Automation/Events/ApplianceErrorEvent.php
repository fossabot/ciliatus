<?php

namespace Ciliatus\Automation\Events;

use Ciliatus\Common\Events\EventInterface;
use Ciliatus\Common\Models\Model;

class ApplianceErrorEvent extends Event implements EventInterface
{

    /**
     * @var string
     */
    public string $text;

    /**
     * @param Model $model
     * @param string $text
     */
    public function __construct(Model $model, string $text)
    {
        parent::__construct($model);
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'Automation.ApplianceErrorEvent';
    }

}