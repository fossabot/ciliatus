<?php

namespace Ciliatus\Api\Traits;

use Ciliatus\Api\Http\Requests\Request;
use Illuminate\Http\JsonResponse;

trait UsesDefaultCreateMethodTrait
{

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->_create($request);
    }

}