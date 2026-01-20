<?php

use App\Http\Controllers\Api\ConsultaCepController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/cep/{cep}', [ConsultaCepController::class, 'consultarApiCep']);
