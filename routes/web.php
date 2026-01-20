<?php

use App\Http\Controllers\Web\ConsultaCepController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Index\IndexController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('home.index');

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/cep', [ConsultaCepController::class, 'obterListagemCep'])
        ->name('cep.index');
    Route::get('/cep/consultar', [ConsultaCepController::class, 'consultarViaTela'])
        ->middleware(['auth'])
        ->name('cep.consultar');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
