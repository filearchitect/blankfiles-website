<?php

use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FileController::class, 'index'])->middleware(['throttle:30,1'])->name('home');

// Same-origin download proxy (must be before /files/{category}/{type} so "download" isn't matched as category)
Route::get('/files/download/{category}/{type}', [FileController::class, 'download'])
    ->middleware(['throttle:60,1'])
    ->where(['category' => '[A-Za-z0-9\-]+', 'type' => '[A-Za-z0-9\-]+'])
    ->name('files.download');

// SEO-friendly file detail route
Route::get('/files/{category}/{type}', [FileController::class, 'show'])
    ->where(['category' => '[A-Za-z0-9\-]+', 'type' => '[A-Za-z0-9\-]+'])
    ->name('files.show');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

require __DIR__ . '/auth.php';
