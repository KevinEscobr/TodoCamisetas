<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CamisetaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\TallaController;

Route::apiResource('camisetas', CamisetaController::class);
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('tallas', TallaController::class);
Route::get('clientes/{id}/camisetas', [ClienteController::class, 'camisetas']);
