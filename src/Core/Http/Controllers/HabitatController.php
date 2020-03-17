<?php

namespace Ciliatus\Core\Http\Controllers;


use Ciliatus\Core\Http\Requests\StoreHabitatRequest;
use Illuminate\Http\JsonResponse;

class HabitatController extends Controller
{

    /**
     * @param StoreHabitatRequest $request
     * @return JsonResponse
     * @throws \Ciliatus\Api\Exceptions\MissingRequestFieldException
     */
    public function store(StoreHabitatRequest $request): JsonResponse
    {
        return $this->_store($request);
    }

}
