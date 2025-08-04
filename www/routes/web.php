<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/swagger/gen/', function () {
    $openapi = \OpenApi\Generator::scan([
        base_path('app/Http/Controllers'),  // Make sure this path is correct
    ]);

    return response($openapi->toJson())->header('Content-Type', 'application/json');
});
