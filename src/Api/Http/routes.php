<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

$controller_namespace = 'Ciliatus\Api\Http\Controllers';

Route::middleware('api')->prefix('api/v1/auth')->group(function () use ($controller_namespace) {
    Route::get('check', $controller_namespace . '\AuthController@check__show');
});