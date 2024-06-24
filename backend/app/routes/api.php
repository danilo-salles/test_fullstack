<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\DesenvolvedorController;
use App\Http\Controllers\api\NiveisController;

Route::get('desenvolvedores', [DesenvolvedorController::class, 'index']);
Route::get('desenvolvedores/{id?}', [DesenvolvedorController::class, 'show']);
Route::get('niveis/desenvolvedores/count', [DesenvolvedorController::class, 'countByNivel']);
Route::post('desenvolvedores', [DesenvolvedorController::class, 'store']);
Route::put('desenvolvedores/{id}', [DesenvolvedorController::class, 'update']);
Route::patch('desenvolvedores/{id}', [DesenvolvedorController::class, 'update']);
Route::delete('desenvolvedores/{id}', [DesenvolvedorController::class, 'destroy']);

Route::get('niveis', [NiveisController::class, 'index']);
Route::get('niveis/{id}', [NiveisController::class, 'show']);
Route::post('niveis', [NiveisController::class, 'store']);
Route::put('niveis/{id}', [NiveisController::class, 'update']);
Route::patch('niveis/{id}', [NiveisController::class, 'update']);
Route::delete('niveis/{id}', [NiveisController::class, 'destroy']);

