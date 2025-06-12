<?php

use App\Http\Controllers\ViveiroController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Route::get('/user', function (Request $request) {

// })->middleware('auth:sanctum');
    Route::apiResource('viveiros', ViveiroController::class);
