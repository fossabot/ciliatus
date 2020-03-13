<?php

namespace Ciliatus\Common\Traits;

use Ciliatus\Common\Enum\DatabaseDataTypesEnum;
use Ciliatus\Common\Exceptions\InvalidPropertyDataTypeException;

trait HasMultipleDataTypeFieldsTrait
{
    /**
     * @return mixed
     * @throws InvalidPropertyDataTypeException
     */
    public function getValueAttribute()
    {
        if (!in_array($this->datatype, DatabaseDataTypesEnum::values())) {
            throw new InvalidPropertyDataTypeException($this->datatype);
        }

        $column_name = 'value_' . $this->datatype;
        return $this->$column_name;
    }
}
