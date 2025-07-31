<?php

use App\Modules\Tenant\Controllers\TenantController;
use Illuminate\Support\Facades\Route;

Route::apiResource('tenants', TenantController::class);
