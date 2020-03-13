<?php

namespace Ciliatus\Automation\Http\Requests;

use Ciliatus\Api\Http\Requests\Request;
use Ciliatus\Automation\Http\Requests\Rules\HealthIndicatorStatusRule;
use Ciliatus\Automation\Http\Requests\Rules\WorkflowActionExecutionStateRule;

class ControlunitReportActionStateRequest extends Request
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'state'                 => ['string', 'required', new WorkflowActionExecutionStateRule()],
            'action_execution_id'   => 'int|required'
        ];
    }

}