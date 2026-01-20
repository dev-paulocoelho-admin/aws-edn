<?php

use App\Http\Controllers\Api\ConsultaCepController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('cep')->group(function () {
    Route::get('/listar-ceps-consultados', [ConsultaCepController::class, 'index']);
    Route::get('/{cep}', [ConsultaCepController::class, 'show']);
});
