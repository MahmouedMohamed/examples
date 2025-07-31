<?php

namespace App\Modules\Tenant\Services;

use App\Modules\Tenant\Interfaces\TenantRepositoryInterface;
use App\Modules\Tenant\Interfaces\TenantServiceInterface;
use App\Modules\Tenant\Models\Tenant;
use Illuminate\Pagination\LengthAwarePaginator;

class TenantService implements TenantServiceInterface
{
    public function __construct(private TenantRepositoryInterface $tenantRepository) {}

    public function index($request): LengthAwarePaginator
    {
        return $this->tenantRepository->index($request);
    }

    public function store($request): Tenant
    {
        return $this->tenantRepository->store($request);
    }

    public function update($request, $tenant): Tenant
    {
        return $this->tenantRepository->update($request, $tenant);
    }

    public function destroy($tenant): bool
    {
        return $this->tenantRepository->destroy($tenant);
    }
}
