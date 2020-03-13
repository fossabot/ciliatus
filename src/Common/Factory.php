<?php

namespace Ciliatus\Common;

use Ciliatus\Common\Models\Model;
use Ciliatus\Common\Exceptions\ModelNotFoundException;

class Factory
{

    /**
     * @param string $class
     * @param int $id
     * @return Model
     * @throws ModelNotFoundException
     */
    public static function findOrFail(string $class, int $id): Model
    {
        try {
            return $class::findOrFail($id);
        } catch (Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            throw new ModelNotFoundException('LogicalSensor', $id);
        }
    }

}