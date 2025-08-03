<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/api/v1/')->group(function() {
    Route::get('/', function () {
        return '321';
    });
})->name('api');
