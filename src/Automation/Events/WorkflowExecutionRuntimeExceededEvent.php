<?php

namespace Ciliatus\Automation\Events;

use Ciliatus\Common\Events\EventInterface;
use Ciliatus\Common\Models\Model;

class WorkflowExecutionRuntimeExceededEvent extends Event implements EventInterface
{

    /**
     * @var string
     */
    public string $text;

    /**
     * @var bool
     */
    public bool $is_critical;

    /**
     * @param Model $model
     * @param string $text
     * @param bool $is_critical
     */
    public function __construct(Model $model, string $text, bool $is_critical)
    {
        parent::__construct($model);
        $this->text = $text;
        $this->is_critical = $is_critical;
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'Automation.WorkflowExecutionRuntimeExceededEvent';
    }

}