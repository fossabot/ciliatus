<?php

namespace Ciliatus\Common;

use Ciliatus\Common\Models\Model;
use Ciliatus\Common\Exceptions\ModelNotFoundException;

class Factory
{

    /**
     * @param string $class
     * @param int $id
     * @param array $with
     * @return Model
     * @throws ModelNotFoundException
     */
    public static function findOrFail(string $class, int $id, array $with = []): Model
    {
        try {
            return $class::with($with)->findOrFail($id);
        } catch (Illuminate\Database\Eloquent\ModelNotFoundException $ex) {
            throw new ModelNotFoundException($class, $id);
        }
    }

}