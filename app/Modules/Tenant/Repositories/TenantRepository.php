<?php

namespace App\Modules\Tenant\Repositories;

use App\Modules\Tenant\Interfaces\TenantRepositoryInterface;
use App\Modules\Tenant\Models\Tenant;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class TenantRepository implements TenantRepositoryInterface
{
    public function index($request): LengthAwarePaginator
    {
        return Tenant::paginate($request->get('per_page', 15));
    }

    public function store($request): ?Tenant
    {
        try {
            DB::beginTransaction();
            $tenant = Tenant::create([
                'data' => $request['data'],
            ]);

            $baseDomain = config('app.env') === 'local'
                ? '.localhost' // local development
                : '.example.com'; // production

            $fullDomain = $request['domain'].$baseDomain;

            $tenant->domains()->create([
                'domain' => $fullDomain,
            ]);

            DB::commit();

            return $tenant;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }

    }

    public function update($request, $tenant): Tenant
    {
        $tenant->update([
            'data' => $request['data'],
        ]);

        return $tenant->fresh();
    }

    public function destroy($tenant): bool
    {
        DB::beginTransaction();
        $tenant->domains()->delete();

        return $tenant->delete();
        DB::commit();
    }
}
