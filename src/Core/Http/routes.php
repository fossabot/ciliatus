<?php

use Ciliatus\Core\Http\Controllers\AnimalController;
use Ciliatus\Core\Http\Controllers\HabitatController;
use Ciliatus\Core\Http\Controllers\HabitatTypeController;
use Ciliatus\Core\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api/v1/core')->group(function () {
    Route::resource('locations', LocationController::class);
    Route::resource('habitats', HabitatController::class);
    Route::resource('habitat_types', HabitatTypeController::class);
    Route::resource('animals', AnimalController::class);
});
