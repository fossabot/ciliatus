<?php

use Illuminate\Support\Facades\Route;

$controller_namespace = 'Ciliatus\Monitoring\Http\Controllers';

Route::middleware('api')->prefix('api/v1/monitoring')->group(function () use ($controller_namespace) {
    Route::resource('physical_sensors', \Ciliatus\Monitoring\Http\Controllers\PhysicalSensorController::class);
    Route::resource('logical_sensors', \Ciliatus\Monitoring\Http\Controllers\LogicalSensorController::class);
    Route::post('ingest', $controller_namespace . '\IngestController@ingest__store');
    Route::post('batch_ingest', $controller_namespace . '\IngestController@batch_ingest__store');
});
