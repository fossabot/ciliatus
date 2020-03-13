<?php

namespace Ciliatus\Api\Http\Controllers;


use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function check()
    {
        return $this->respondWithData(Auth::user()->transform());
    }

}
