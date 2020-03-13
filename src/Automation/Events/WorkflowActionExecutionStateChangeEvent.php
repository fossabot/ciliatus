<?php

namespace Ciliatus\Automation\Events;

use Ciliatus\Automation\Enum\WorkflowExecutionStateEnum;
use Ciliatus\Common\Events\EventInterface;
use Ciliatus\Common\Models\Model;

class WorkflowActionExecutionStateChangeEvent extends Event implements EventInterface
{

    /**
     * @var WorkflowExecutionStateEnum
     */
    public WorkflowExecutionStateEnum $state;

    /**
     * @param Model $model
     * @param WorkflowExecutionStateEnum $state
     */
    public function __construct(Model $model, WorkflowExecutionStateEnum $state)
    {
        parent::__construct($model);
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function broadcastAs(): string
    {
        return 'Automation.WorkflowActionExecutionStateChangeEvent';
    }

}