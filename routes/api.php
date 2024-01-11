<?php

use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ProveedoresController;
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

//declarando una ruta
//tipo de peticion http, asignacion del controlador y su metodo
Route::get('/lista_proveedores',[ProveedoresController::class, 'index']);
Route::post('/registro_proveedor',[ProveedoresController::class, 'store']);
//ruta con parametro
Route::get('/proveedor/{id}',[ProveedoresController::class, 'getById']);
Route::put('/actualizar_proveedor/{id}',[ProveedoresController::class, 'update']);
Route::delete('/eliminar_proveedor/{id}',[ProveedoresController::class, 'destroy']);

Route::get('/lista_productos',[ProductosController::class, 'index']);


