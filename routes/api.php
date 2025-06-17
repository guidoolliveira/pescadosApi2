<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BiometriaController;
use App\Http\Controllers\ViveiroController;
use App\Http\Controllers\AuthController; 

Route::apiResource('viveiros', ViveiroController::class);
Route::apiResource('biometrias', BiometriaController::class);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
