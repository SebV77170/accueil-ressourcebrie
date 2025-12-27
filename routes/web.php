<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
});

use App\Http\Controllers\CaTaskController;

Route::prefix('ca/tasks')->name('ca.tasks.')->group(function () {

    Route::get('/', [CaTaskController::class, 'index'])->name('index');
    Route::post('/', [CaTaskController::class, 'store'])->name('store');
    Route::put('/{id}', [CaTaskController::class, 'update'])->name('update');

    Route::patch('/{id}/complete', [CaTaskController::class, 'complete'])->name('complete');
    Route::patch('/{id}/archive', [CaTaskController::class, 'archive'])->name('archive');

    Route::delete('/{id}', [CaTaskController::class, 'destroy'])->name('destroy');
});




require __DIR__.'/auth.php';
