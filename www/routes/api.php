<?php
use Illuminate\Support\Facades\Route;

Route::prefix('/v1/')
    ->middleware([\App\Http\Middleware\ApiTokenValideted::class, \App\Http\Middleware\JsonErrorHandler::class])
    ->group(function () {

        Route::match(
            ['get', 'post'],
            '/building',
            [\App\Http\Controllers\BuildingController::class, 'index']
        )->name('building.index');

        Route::match(
            ['get', 'post'],
            '/activity',
            [\App\Http\Controllers\ActivityController::class, 'index']
        )->name('activity.index');

        Route::get(
            '{id}/organization',
            [\App\Http\Controllers\OrganizationController::class, 'index']
        )->name('organization.index');

        Route::post(
            'search/',
            [\App\Http\Controllers\OrganizationController::class, 'index']
        )->name('organization.search');

    })->name('api');
