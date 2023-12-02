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

Route::apiResource('contribuyentes', ContribuyenteController::class);
Route::apiResource('establecimientos', EstablecimientoController::class);
Route::apiResource('puntosemision', PuntoEmisionController::class);
Route::apiResource('proveedores', ProveedorController::class);
Route::apiResource('productos', ProductoController::class);
Route::apiResource('clientes', ClienteController::class);
Route::apiResource('facturas', FacturaController::class);
