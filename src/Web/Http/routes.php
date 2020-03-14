<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;


Route::middleware('web')->group(function () {
    Route::get(
        '/web/{any1?}/{any2?}/{any3?}/{any4?}',
        fn() => File::get(public_path() . '/ciliatus_index.html')
    );

    Route::get(
        '/',
        fn() => redirect('/web')
    );
});