<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/cuentas', [App\Http\Controllers\CuentaController::class, 'index'])->name('cuentas.index');
    Route::post('/cuentas', [App\Http\Controllers\CuentaController::class, 'store'])->name('cuentas.store');
    Route::get('/transacciones', [App\Http\Controllers\TransaccionController::class, 'index'])->name('web.transacciones.index');
    Route::get('/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('web.categorias.index');
});
