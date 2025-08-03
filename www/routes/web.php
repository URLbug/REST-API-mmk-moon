<?php

use Illuminate\Support\Facades\Route;

Route::prefix('/api/v1/')->group(function() {
    Route::get('/get', [\App\Http\Controllers\OrganizationController::class, 'index']);
})->name('api');
