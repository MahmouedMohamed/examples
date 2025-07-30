<?php

use App\Modules\Kafka\Controllers\KafkaController;
use Illuminate\Support\Facades\Route;

Route::post('/kafka-initialize', KafkaController::class);
