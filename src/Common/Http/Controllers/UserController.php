<?php

namespace Ciliatus\Common\Http\Controllers;


use Ciliatus\Api\Traits\UsesDefaultCreateMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultDestroyMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultIndexMethodTrait;
use Ciliatus\Api\Traits\UsesDefaultUpdateMethodTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    use UsesDefaultCreateMethodTrait,
        UsesDefaultIndexMethodTrait,
        UsesDefaultUpdateMethodTrait,
        UsesDefaultDestroyMethodTrait;


    /**
     * @param int $id
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function show(int $id): JsonResponse
    {
        if (Auth::user()->id != $id) {
            return $this->respondUnauthorized();
        }

        return $this->_show($id);
    }

}
