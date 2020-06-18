<?php

use Illuminate\Support\Facades\Route;

$controller_namespace = 'Ciliatus\Common\Http\Controllers';

Route::middleware('api')->prefix('api/v1/common')->group(function () use ($controller_namespace) {
    Route::resource('users', \Ciliatus\Common\Http\Controllers\UserController::class);
    Route::put('alerts/acknowledge', $controller_namespace . '\AlertController@acknowledge__update');
});
