<?php

namespace App\Modules\Tenant\Controllers;

use App\Modules\Tenant\Interfaces\TenantServiceInterface;
use App\Modules\Tenant\Models\Tenant;
use App\Modules\Tenant\Requests\StoreTenantRequest;
use App\Modules\Tenant\Requests\UpdateTenantRequest;
use App\Modules\Tenant\Resources\TenantCollectionResource;
use App\Modules\Tenant\Resources\TenantResource;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Http\Request;

class TenantController
{
    use ApiResponse;

    public function __construct(private TenantServiceInterface $tenantService) {}

    public function index(Request $request)
    {
        try {
            $tenants = $this->tenantService->index($request);

            return $this->success('Tenants retrieved successfully', new TenantCollectionResource($tenants));
        } catch (Exception $e) {
            return $this->error('Failed to retrieve tenants: '.$e->getMessage());
        }
    }

    public function store(StoreTenantRequest $request)
    {
        try {
            $tenant = $this->tenantService->store($request);

            return $this->success('Tenant created successfully', new TenantResource($tenant), 'item');
        } catch (Exception $e) {
            return $this->error('Failed to create tenant: '.$e->getMessage());
        }
    }

    public function show(Tenant $tenant)
    {
        try {
            return $this->success('Tenant retrieved successfully', new TenantResource($tenant), 'item');
        } catch (Exception $e) {
            return $this->error('Tenant not found', 404);
        }
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        try {
            $tenant = $this->tenantService->update($request, $tenant);

            return $this->success('Tenant updated successfully', new TenantResource($tenant), 'item');
        } catch (Exception $e) {
            return $this->error('Failed to update tenant: '.$e->getMessage());
        }
    }

    public function destroy(Tenant $tenant)
    {
        try {
            $this->tenantService->destroy($tenant);

            return $this->success('Tenant deleted successfully', [], 'item');
        } catch (Exception $e) {
            return $this->error('Failed to delete tenant: '.$e->getMessage());
        }
    }
}
