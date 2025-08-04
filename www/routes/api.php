<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/')
    ->middleware([\App\Http\Middleware\ApiTokenValideted::class])->group(function () {
    Route::match(
        ['get', 'post'],
        '/building',
        [\App\Http\Controllers\BuildingController::class, 'index']
    );

    Route::match(
        ['get', 'post'],
        '/activity',
        [\App\Http\Controllers\ActivityController::class, 'index']
    );

    Route::get(
        '{id}/organization',
        [\App\Http\Controllers\OrganizationController::class, 'index']
    )->name('organization.index');

    Route::post(
        'search/',
        [\App\Http\Controllers\OrganizationController::class, 'index']
    )->name('organization.search');

})->name('api');
