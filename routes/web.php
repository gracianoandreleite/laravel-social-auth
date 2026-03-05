<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\SocialController;
use Illuminate\Support\Facades\Route;

/**
 * Página inicial
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * Dashboard - exige usuário autenticado e verificado
 */
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/**
 * Rotas de perfil do usuário
 */
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Rotas de login social via OAuth
 * 
 * @route GET /auth/{provider}/redirect  - Redireciona para o provedor
 * @route GET /auth/{provider}/callback  - Recebe callback e loga usuário
 */
Route::get('/auth/{provider}/redirect', [SocialController::class, 'redirect'])->name('provider.redirect');
Route::get('/auth/{provider}/callback', [SocialController::class, 'callback'])->name('provider.callback');

require __DIR__.'/auth.php';