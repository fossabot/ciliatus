<?php

namespace Ciliatus\Automation\Http\Requests;

use Ciliatus\Api\Http\Requests\Request;
use Ciliatus\Automation\Http\Requests\Rules\HealthIndicatorStatusRule;

class ControlunitReportApplianceStateRequest extends Request
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'state_id'      => 'int|required',
            'appliance_id'  => 'int|required'
        ];
    }

}