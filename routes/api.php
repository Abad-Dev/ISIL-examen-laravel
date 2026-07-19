<?php

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PresupuestoController;
use App\Http\Controllers\Api\TransaccionController;
use App\Http\Controllers\Api\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('usuario', [UsuarioController::class, 'show']);
    Route::match(['put', 'patch'], 'usuario', [UsuarioController::class, 'update']);

    Route::apiResource('categorias', CategoriaController::class);
    Route::apiResource('transacciones', TransaccionController::class);
    Route::apiResource('presupuestos', PresupuestoController::class);
});
