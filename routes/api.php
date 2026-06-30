<?php

use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\PresupuestoController;
use App\Http\Controllers\Api\TransaccionController;
use App\Http\Controllers\Api\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::apiResource('usuarios', UsuarioController::class);
Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('transacciones', TransaccionController::class);
Route::apiResource('presupuestos', PresupuestoController::class);
