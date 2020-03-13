<?php

namespace Ciliatus\Automation\Http\Requests;

use Ciliatus\Api\Http\Requests\Request;
use Ciliatus\Automation\Http\Requests\Rules\HealthIndicatorStatusRule;

class ControlunitCheckinRequest extends Request
{

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'datetime'  => 'required|date_format:c',
            'version'   => 'required|string'
        ];
    }

}