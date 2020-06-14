<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->prefix('api/v1/core')->group(function () {
    Route::resource('locations', \Ciliatus\Core\Http\Controllers\LocationController::class);
    Route::resource('habitats', \Ciliatus\Core\Http\Controllers\HabitatController::class);
    Route::resource('habitat_types', \Ciliatus\Core\Http\Controllers\HabitatTypeController::class);
    Route::resource('animals', \Ciliatus\Core\Http\Controllers\AnimalController::class);
});
