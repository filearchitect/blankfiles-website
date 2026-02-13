<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FileController;
use App\Http\Middleware\TrackApiUsage;

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
    Route::middleware(['throttle:files-api', TrackApiUsage::class])->group(function () {
        Route::get('/status', [FileController::class, 'status']);
    });

    Route::prefix('files')->middleware(['throttle:files-api', TrackApiUsage::class])->group(function () {
        Route::get('/', [FileController::class, 'index']);
        Route::get('{category}/{type}', [FileController::class, 'show'])
            ->where(['category' => '[A-Za-z0-9\-]+', 'type' => '[A-Za-z0-9\-]+']);
        Route::get('{type}', [FileController::class, 'getByType']);
    });
});
