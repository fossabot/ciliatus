<?php

namespace Ciliatus\Api\Http\Transformers;

use Ciliatus\Common\Models\Model;

abstract class Transformer implements TransformerInterface
{

    public function __invoke(Model $model): array
    {
        return $this->transform($model);
    }

    abstract public function transform(Model $model): array;

}