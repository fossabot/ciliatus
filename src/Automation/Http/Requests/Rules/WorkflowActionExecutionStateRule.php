<?php

namespace Ciliatus\Automation\Http\Requests\Rules;

use Ciliatus\Automation\Enum\WorkflowExecutionStateEnum;
use Illuminate\Validation\Rule;

class WorkflowActionExecutionStateRule extends Rule
{

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return WorkflowExecutionStateEnum::isValidKey($value);
    }


    /**
     * @return string
     */
    public function message()
    {
        return 'Value is not a valid workflow action execution state';
    }

}