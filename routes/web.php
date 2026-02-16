<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RootController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryAIController;

/*
|--------------------------------------------------------------------------
| RUTA PRINCIPAL
|--------------------------------------------------------------------------
*/

// Root decide a dónde enviar según sesión y rol
Route::get('/', RootController::class)->name('root');


/*
|--------------------------------------------------------------------------
| CAMBIO DE IDIOMA
|--------------------------------------------------------------------------
*/

Route::get('/lang/{lang}', [LocaleController::class, 'switch'])
    ->name('lang.switch');


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (REQUIEREN LOGIN)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */

    Route::get('/perfil', [ProfileController::class, 'index'])
        ->name('profile.index');

    Route::post('/perfil/actualizar', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/perfil/foto', [ProfileController::class, 'updatePhoto'])
        ->name('profile.update.photo');

    Route::post('/perfil/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.update.password');

    /*
    |--------------------------------------------------------------------------
    | INVENTARIO IA (PROTEGIDO)
    |--------------------------------------------------------------------------
    */

    Route::get('/test', [ProfileController::class, 'test'])->name('dashboard');

    Route::post('/inventory/ai-scan', [InventoryAIController::class, 'scan'])
        ->name('inventory.ai.scan');
});


/*
|--------------------------------------------------------------------------
| RUTAS PROVIDER
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:provider', 'active.provider'])
    ->prefix('provider')
    ->name('provider.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('provider.dashboard');
        })->name('dashboard');

    });


/*
|--------------------------------------------------------------------------
| RUTAS ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

    });


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
