<?php

use App\Http\Controllers\Api\FileManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth'])->prefix('files')->name('api.files.')->group(function () {
    Route::get('/', [FileManagerController::class, 'index'])->name('index');
    Route::get('/download', [FileManagerController::class, 'download'])->name('download');
    Route::post('/upload', [FileManagerController::class, 'upload'])->name('upload');
    Route::post('/folders', [FileManagerController::class, 'createFolder'])->name('folders.create');
    Route::patch('/move', [FileManagerController::class, 'move'])->name('move');
    Route::delete('/', [FileManagerController::class, 'destroy'])->name('destroy');
});
