<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\RootController;
use App\Http\Controllers\InventoryAIController;

Route::get('/', RootController::class)->name('root');
Route::get('/lang/{lang}', [LocaleController::class, 'switch'])->name('lang.switch');


Route::get('/', [ProfileController::class, 'test'])->name('profile.index');

Route::post('/inventory/ai-scan', [InventoryAIController::class, 'scan'])
    ->name('inventory.ai.scan');
Route::middleware(['auth'])
    ->group(function () {

        Route::get('/perfil', [ProfileController::class, 'index'])->name('profile.index');
        Route::post('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/perfil/foto', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');
        Route::post('/perfil/password', [ProfileController::class, 'updatePassword'])->name('profile.update.password');
    });

Route::middleware(['auth', 'role:provider', 'active.provider'])
    ->prefix('provider')
    ->name('provider.')
    ->group(function () {


    });


Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {


    });


require __DIR__ . '/auth.php';
