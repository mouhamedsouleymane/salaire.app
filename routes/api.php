<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ConfigurationController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\EmployerController;
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

// Public API routes (no authentication required)
Route::prefix('v1')->group(function () {

    // Employers API
    Route::prefix('employers')->group(function () {
        Route::get('/', [EmployerController::class, 'apiIndex'])->name('api.employers.index');
        Route::get('/{employer}', [EmployerController::class, 'apiShow'])->name('api.employers.show');
    });

    // Departements API
    Route::prefix('departements')->group(function () {
        Route::get('/', [DepartementController::class, 'apiIndex'])->name('api.departements.index');
        Route::get('/{departement}', [DepartementController::class, 'apiShow'])->name('api.departements.show');
    });

    // Configurations API
    Route::prefix('configurations')->group(function () {
        Route::get('/', [ConfigurationController::class, 'apiIndex'])->name('api.configurations.index');
        Route::get('/{configuration}', [ConfigurationController::class, 'apiShow'])->name('api.configurations.show');
    });

    // Salaires API (if needed)
    // Route::get('/salaires', [SalaireController::class, 'apiIndex'])->name('api.salaires.index');

});

// Protected API routes (authentication required)
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {

    // Admin routes
    Route::prefix('admin')->group(function () {
        Route::get('/stats', [AppController::class, 'apiStats'])->name('api.admin.stats');
    });

    // Full CRUD for authenticated users
    Route::apiResource('employers', EmployerController::class);
    Route::apiResource('departements', DepartementController::class);
    Route::apiResource('configurations', ConfigurationController::class);
    Route::apiResource('administrateurs', AdminController::class);

});