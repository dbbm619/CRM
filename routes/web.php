<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('clientes', ClienteController::class);

Route::resource('ventas', VentaController::class);

Route::resource('facturas', FacturaController::class);


