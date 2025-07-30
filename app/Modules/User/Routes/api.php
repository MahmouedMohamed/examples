<?php

use App\Modules\User\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::apiResource('users', UserController::class);

// Additional user routes
Route::group(['prefix' => 'users'], function () {
    Route::patch('/{user}/activate', [UserController::class, 'activate']);
    Route::patch('/{user}/deactivate', [UserController::class, 'deactivate']);
});
