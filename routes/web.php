<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterMaterialController;
use App\Http\Controllers\PalletController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard (if authenticated) or login
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('pallet.index')
        : redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Main Pallet System routes (Protected by Auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PalletController::class, 'index'])->name('pallet.index');
    Route::get('/pallet', [PalletController::class, 'index']);
    Route::get('/components', [PalletController::class, 'components'])->name('pallet.components');
    Route::get('/pallet/create', [PalletController::class, 'create'])->name('pallet.create');
    Route::post('/pallet', [PalletController::class, 'store'])->name('pallet.store');
    Route::get('/pallet/pdf/{id?}', [PalletController::class, 'downloadPdf'])->name('pallet.pdf.download');
    Route::get('/pallet/{id}', [PalletController::class, 'show'])->whereNumber('id')->name('pallet.show');
    Route::get('/pallet/{id}/edit', [PalletController::class, 'edit'])->whereNumber('id')->name('pallet.edit');
    Route::put('/pallet/{id}', [PalletController::class, 'update'])->whereNumber('id')->name('pallet.update');
    Route::delete('/pallet/{id}', [PalletController::class, 'destroy'])->whereNumber('id')->name('pallet.destroy');

    // Master Material / Spare Part routes
    Route::get('/master-materials', [MasterMaterialController::class, 'index'])->name('master-materials.index');
    Route::post('/master-materials', [MasterMaterialController::class, 'store'])->name('master-materials.store');
    Route::post('/master-materials/import', [MasterMaterialController::class, 'import'])->name('master-materials.import');
    Route::get('/master-materials/template', [MasterMaterialController::class, 'downloadTemplate'])->name('master-materials.template');
    Route::put('/master-materials/{id}', [MasterMaterialController::class, 'update'])->whereNumber('id')->name('master-materials.update');
    Route::delete('/master-materials/{id}', [MasterMaterialController::class, 'destroy'])->whereNumber('id')->name('master-materials.destroy');
    Route::get('/api/master-materials/search', [MasterMaterialController::class, 'search'])->name('api.master-materials.search');

    // Password Management
    Route::get('/password/change', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/password/change', [AuthController::class, 'updatePassword'])->name('password.update');
});
