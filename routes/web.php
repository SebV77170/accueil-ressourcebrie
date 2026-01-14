<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ConfigurationController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\FileManagerController;

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
    Route::get('/configuration', [ConfigurationController::class, 'edit'])->name('configuration.edit');
    Route::patch('/configuration', [ConfigurationController::class, 'update'])->name('configuration.update');
});



Route::middleware(['auth'])->group(function () {
    Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
    Route::get('/fichiers', [FileManagerController::class, 'index'])->name('files.index');
    Route::post('/fichiers', [FileManagerController::class, 'store'])->name('files.store');
    Route::get('/fichiers/telecharger/{path}', [FileManagerController::class, 'download'])
        ->where('path', '.*')
        ->name('files.download');
});

Route::post('/sites', [SiteController::class, 'store'])
    ->name('sites.store');

Route::put('/sites/{id}', [SiteController::class, 'update']);
Route::delete('/sites/{id}', [SiteController::class, 'destroy']);


use App\Http\Controllers\CategoryController;

Route::post('/categories', [CategoryController::class, 'store']);




use App\Http\Controllers\CaTaskController;
use App\Http\Controllers\SubTaskController;
use App\Http\Controllers\TaskCommentController;

Route::prefix('ca/tasks')->name('ca.tasks.')->group(function () {

    Route::get('/', [CaTaskController::class, 'index'])->name('index');
    Route::post('/', [CaTaskController::class, 'store'])->name('store');
    Route::put('/{id}', [CaTaskController::class, 'update'])->name('update');

    Route::patch('/{id}/complete', [CaTaskController::class, 'complete'])->name('complete');
    Route::patch('/{id}/archive', [CaTaskController::class, 'archive'])->name('archive');
    Route::patch('/{id}/unarchive', [CaTaskController::class, 'unarchive'])->name('unarchive');

    Route::delete('/{id}', [CaTaskController::class, 'destroy'])->name('destroy');
});

Route::post('/ca/tasks/{task}/comments', [TaskCommentController::class, 'store'])
    ->name('ca.tasks.comments.store');

Route::prefix('ca/tasks/{task}/sub-tasks')->name('ca.tasks.subTasks.')->group(function () {
    Route::post('/', [SubTaskController::class, 'store'])->name('store');
    Route::put('/{subTask}', [SubTaskController::class, 'update'])->name('update');
    Route::patch('/{subTask}/complete', [SubTaskController::class, 'complete'])->name('complete');
    Route::patch('/{subTask}/archive', [SubTaskController::class, 'archive'])->name('archive');
    Route::delete('/{subTask}', [SubTaskController::class, 'destroy'])->name('destroy');
});

Route::post('/ca/tasks/{task}/sub-tasks/{subTask}/comments', [TaskCommentController::class, 'storeForSubTask'])
    ->name('ca.tasks.subTasks.comments.store');







require __DIR__.'/auth.php';
