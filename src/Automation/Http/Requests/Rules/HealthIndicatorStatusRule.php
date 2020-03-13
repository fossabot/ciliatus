<?php

namespace Ciliatus\Automation\Http\Requests\Rules;

use Ciliatus\Common\Enum\HealthIndicatorStatusEnum;
use Illuminate\Validation\Rule;

class HealthIndicatorStatusRule extends Rule
{

    /**
     * @param $attribute
     * @param $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return HealthIndicatorStatusEnum::isValidKey($value);
    }


    /**
     * @return string
     */
    public function message()
    {
        return 'Value is not a valid health indicator state';
    }

}