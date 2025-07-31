<?php

use App\Modules\Tenant\Middlewares\PreventAccessFromTenantDomains;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

Route::middleware([
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    require __DIR__.'/../app/Modules/Book/Routes/api.php';
    require __DIR__.'/../app/Modules/Kafka/Routes/api.php';
    require __DIR__.'/../app/Modules/User/Routes/api.php';
});
Route::middleware([
    PreventAccessFromTenantDomains::class,
])->group(function () {
    require base_path('app/Modules/Tenant/Routes/api.php');
});
