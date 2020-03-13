<?php

namespace Ciliatus\Automation\Enum;

use Ciliatus\Common\Enum\Enum;

/**
 * @method static WorkflowHistoryEventsEnum WORKFLOW_START_NOTREADY
 * @method static WorkflowHistoryEventsEnum WORKFLOW_EXECUTION_READY
 * @method static WorkflowHistoryEventsEnum WORKFLOW_EXECUTION_COMPLETED
 * @method static WorkflowHistoryEventsEnum WORKFLOW_EXECUTION_ERROR
 * @method static WorkflowHistoryEventsEnum WORKFLOW_EXECUTION_CREATED
 * @method static WorkflowHistoryEventsEnum WORKFLOW_ACTION_EXECUTION_CREATED
 */
class WorkflowHistoryEventsEnum extends Enum
{

    public const WORKFLOW_START_NOTREADY = 'ciliatus.automation::workflow.events.start.not_ready';
    public const WORKFLOW_EXECUTION_READY = 'ciliatus.automation::workflow.events.execution.ready';
    public const WORKFLOW_EXECUTION_COMPLETED = 'ciliatus.automation::workflow.events.execution.completed';
    public const WORKFLOW_EXECUTION_ERROR = 'ciliatus.automation::workflow.events.execution.error';
    public const WORKFLOW_EXECUTION_CREATED = 'ciliatus.automation::workflow.events.execution.created';
    public const WORKFLOW_ACTION_EXECUTION_CREATED = 'ciliatus.automation::workflow_action.events.execution.created';

}
