<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GestionNoticia;
use App\Http\Controllers\PokemonController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\NoticiaController;
use App\Http\Controllers\EquipoController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);

Route::get('/listar', [PokemonController::class, 'index']);

Route::get('/movimientos', [MovimientoController::class, 'index']);

Route::get('/noticias', [NoticiaController::class, 'index']);          
Route::post('/noticias', [NoticiaController::class, 'store']);        
Route::get('/noticias/{id}', [NoticiaController::class, 'show']);     
Route::put('/noticias/{id}', [NoticiaController::class, 'update']);   
Route::delete('/noticias/{id}', [NoticiaController::class, 'destroy']); 



Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/equipos', [EquipoController::class, 'store']);
    Route::get('/mis-equipos', [EquipoController::class, 'misEquipos']);
});
