<?php

namespace Ciliatus\Common\Http\Controllers;


use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{


    public function show(int $id): JsonResponse
    {
        if (Auth::user()->id != $id) {
            return $this->respondUnauthorized();
        }

        return parent::show($id);
    }

}
