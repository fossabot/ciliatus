<?php

namespace Ciliatus\Automation\Enum;

use Ciliatus\Common\Enum\Enum;

/**
 * @method static WorkflowExecutionStateEnum STATE_DRAFT
 * @method static WorkflowExecutionStateEnum STATE_WAITING
 * @method static WorkflowExecutionStateEnum STATE_CLAIMED
 * @method static WorkflowExecutionStateEnum STATE_READY_TO_START
 * @method static WorkflowExecutionStateEnum STATE_RUNNING
 * @method static WorkflowExecutionStateEnum STATE_ENDED
 * @method static WorkflowExecutionStateEnum STATE_RUNTIME_EXCEEDED
 * @method static WorkflowExecutionStateEnum STATE_ERROR
 * @method static WorkflowExecutionStateEnum STATE_UNKNOWN
 */
class WorkflowExecutionStateEnum extends Enum
{

    private const STATE_DRAFT = 'draft';
    private const STATE_WAITING = 'waiting';
    private const STATE_CLAIMED = 'claimed';
    private const STATE_READY_TO_START = 'ready_to_start';
    private const STATE_RUNNING = 'running';
    private const STATE_ENDED = 'ended';
    private const STATE_RUNTIME_EXCEEDED = 'runtime_exceeded';
    private const STATE_ERROR = 'error';
    private const STATE_UNKNOWN = 'unknown';

}
