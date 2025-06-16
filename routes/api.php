<?php

use App\Http\Controllers\BiometriaController;
use App\Http\Controllers\ViveiroController;

use Illuminate\Support\Facades\Route;
// Route::get('/user', function (Request $request) {

// })->middleware('auth:sanctum');
    Route::apiResource('viveiros', ViveiroController::class);
    Route::delete('viveiros', [ViveiroController::class, 'destroy']);
    Route::put('viveiros', [ViveiroController::class, 'update']);

    Route::apiResource('biometrias', BiometriaController::class);
    Route::delete('biometrias', [BiometriaController::class, 'destroy']);
    Route::put('biometrias', [BiometriaController::class, 'update']);