<?php

use Ciliatus\Automation\Http\Controllers\ApplianceController;
use Ciliatus\Automation\Http\Controllers\ApplianceGroupController;
use Ciliatus\Automation\Http\Controllers\ControlunitController;
use Ciliatus\Automation\Http\Controllers\WorkflowExecutionController;
use Illuminate\Support\Facades\Route;

$controller_namespace = 'Ciliatus\Automation\Http\Controllers';

Route::prefix('api/v1/automation')->group(function () use ($controller_namespace) {
    Route::resource('appliances', ApplianceController::class);
    Route::resource('appliance_groups', ApplianceGroupController::class);
    Route::resource('controlunits', ControlunitController::class);
    Route::resource('workflow_executions', WorkflowExecutionController::class);
});
