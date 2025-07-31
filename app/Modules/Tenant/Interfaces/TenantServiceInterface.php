<?php

namespace App\Modules\Tenant\Interfaces;

use App\Modules\Tenant\Models\Tenant;
use Illuminate\Pagination\LengthAwarePaginator;

interface TenantServiceInterface
{
    public function index($request): LengthAwarePaginator;

    public function store($request): Tenant;

    public function update($request, $tenant): Tenant;

    public function destroy($tenant): bool;
}
