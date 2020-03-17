<?php

namespace Ciliatus\Common\Traits;

use Ciliatus\Common\Enum\DatabaseDataTypesEnum;
use Ciliatus\Common\Exceptions\MissingRequestFieldException;

trait HasMultipleDataTypeFieldsTrait
{
    /**
     * @return mixed
     * @throws MissingRequestFieldException
     */
    public function getValueAttribute()
    {
        if (!in_array($this->datatype, DatabaseDataTypesEnum::values())) {
            throw new MissingRequestFieldException($this->datatype);
        }

        $column_name = 'value_' . $this->datatype;
        return $this->$column_name;
    }
}
