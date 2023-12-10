<?php

use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\ContribuyenteController;
use App\Http\Controllers\Api\EmpleadoController;
use App\Http\Controllers\Api\EstablecimientoController;
use App\Http\Controllers\Api\FacturaController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\PuntoEmisionController;
use App\Http\Controllers\AuthController;
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

// Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['controller' => ContribuyenteController::class, 'prefix' => 'contribuyentes'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update')->middleware('aut:sanctum');
    Route::delete('delete/{id}', 'destroy')->middleware('aut:sanctum');
});

Route::group(['controller' => EstablecimientoController::class, 'prefix' => 'establecimientos', 'middleware' => 'auth:sanctum'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => PuntoEmisionController::class, 'prefix' => 'puntosemision', 'middleware' => 'auth:sanctum'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => EmpleadoController::class, 'prefix' => 'empleados', 'middleware' => 'auth:sanctum'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => ProveedorController::class, 'prefix' => 'proveedores', 'middleware' => 'auth:sanctum'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => ProductoController::class, 'prefix' => 'productos', 'middleware' => 'auth:sanctum'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});

Route::group(['controller' => ClienteController::class, 'prefix' => 'clientes', 'middleware' => 'auth:sanctum'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');

});

Route::group(['controller' => FacturaController::class, 'prefix' => 'facturas', 'middleware' => 'auth:sanctum'], function () {
    Route::post('index', 'index');
    Route::post('store', 'store');
    Route::get('show/{id}', 'show');
    Route::put('update/{id}', 'update');
    Route::delete('delete/{id}', 'destroy');
});
