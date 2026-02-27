<?php

use App\Http\Controllers\Api\ConsultaCepController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/consulta-cep', [ConsultaCepController::class, 'index']);
    Route::post('/consulta-cep', [ConsultaCepController::class, 'store']);
});
