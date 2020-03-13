<?php

namespace Ciliatus\Api\Http\Transformers;

use Ciliatus\Common\Models\Model;

interface TransformerInterface
{

    public function transform(Model $model): array;

}