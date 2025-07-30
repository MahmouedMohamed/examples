<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\KafkaController;
use Illuminate\Support\Facades\Route;

Route::apiResource('books', BookController::class);
Route::post('/kafka-initialize', KafkaController::class);
