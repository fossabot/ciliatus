<?php

use Illuminate\Support\Facades\Route;

$controller_namespace = 'Ciliatus\Common\Http\Controllers';

Route::prefix('api/v1/common')->group(function () use ($controller_namespace) {
    Route::get('alerts.active', 'AlertController@active');
    Route::put('alerts/{id}/acknowledge', 'AlertController@acknowledge');
});
