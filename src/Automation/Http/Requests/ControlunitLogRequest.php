<?php

namespace Ciliatus\Automation\Http\Requests;

use Ciliatus\Api\Http\Requests\Request;
use Ciliatus\Automation\Http\Requests\Rules\HealthIndicatorStatusRule;

class ControlunitLogRequest extends Request
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'log' => 'required|json'
        ];
    }

}