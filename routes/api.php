<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;

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
Route::prefix('v1')->group(function () {
    Route::prefix('files')->middleware(['throttle:30,1'])->group(function () {
        Route::get('/', [FileController::class, 'index']);
        Route::get('{category}/{type}', [FileController::class, 'show'])
            ->where(['category' => '[A-Za-z0-9\-]+', 'type' => '[A-Za-z0-9\-]+']);
        Route::get('{type}', [FileController::class, 'getByType']);
    });
});
