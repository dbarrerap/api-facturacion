<?php

use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ContribuyenteController;
use App\Http\Controllers\Api\EstablecimientoController;
use App\Http\Controllers\Api\FacturaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\PuntoEmisionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['controller' => ContribuyenteController::class, 'prefix' => 'contribuyentes'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => EstablecimientoController::class, 'prefix' => 'establecimientos'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => PuntoEmisionController::class, 'prefix' => 'puntosemision'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => ProveedorController::class, 'prefix' => 'proveedores'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => ProductoController::class, 'prefix' => 'productos'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => ClienteController::class, 'prefix' => 'clientes'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');

});

Route::group(['controller' => FacturaController::class, 'prefix' => 'facturas'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});
